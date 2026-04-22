<?php
/**
 * FA_CRM Module for FrontAccounting
 *
 * Advanced Customer Relationship Management module
 *
 * @package FA_CRM
 * @version 1.0.0
 * @author KSFII Development Team
 * @license GPL-3.0
 */

// Module metadata
$module_name = 'FA_CRM';
$module_version = '1.0.0';
$module_description = 'Advanced Customer Relationship Management for FrontAccounting';
$module_author = 'KSFII Development Team';
$module_category = 'Sales';
$module_min_required_version = '2.4.0';

// Permission constants
define('CRM_VIEW_CUSTOMER', 'CRM_VIEW_CUSTOMER');
define('CRM_MANAGE_CUSTOMER', 'CRM_MANAGE_CUSTOMER');
define('CRM_VIEW_QUALIFY', 'CRM_VIEW_QUALIFY');
define('CRM_MANAGE_QUALIFY', 'CRM_MANAGE_QUALIFY');
define('CRM_VIEW_COMMUNICATIONS', 'CRM_VIEW_COMMUNICATIONS');
define('CRM_MANAGE_COMMUNICATIONS', 'CRM_MANAGE_COMMUNICATIONS');
define('CRM_VIEW_CALENDAR', 'CRM_VIEW_CALENDAR');
define('CRM_MANAGE_CALENDAR', 'CRM_MANAGE_CALENDAR');
define('CRM_VIEW_ANALYTICS', 'CRM_VIEW_ANALYTICS');
define('CRM_ADMIN', 'CRM_ADMIN');

/**
 * Initialize module
 */
function fa_crm_module_init() {
    global $fa_crm_module;

    if (!isset($fa_crm_module)) {
        $fa_crm_module = new FA_CRM_Module();
    }
}

/**
 * Main module class
 */
class FA_CRM_Module {

    public function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        // Hook into FA initialization
        add_action('fa_init', array($this, 'on_fa_init'));
        
        // Hook into customer management
        add_action('customer_created', array($this, 'on_customer_created'), 10, 2);
        add_action('customer_updated', array($this, 'on_customer_updated'), 10, 2);
    }

    /**
     * FA initialization hook
     */
    public function on_fa_init() {
        // Register custom fields for customer display
        add_action('customer_extra_fields', array($this, 'display_customer_crm_fields'));
    }

    /**
     * Handle new customer creation
     */
    public function on_customer_created($customer_id, $data) {
        // Auto-create CRM profile for new customers
        $this->create_crm_profile($customer_id, $data);
    }

    /**
     * Handle customer update
     */
    public function on_customer_updated($customer_id, $data) {
        // Log to activity
        $this->log_activity($customer_id, 'customer_update', 'Customer updated', $data);
    }

    /**
     * Create CRM profile for customer
     */
    private function create_crm_profile($debtor_no, $data = array()) {
        // Check if CRM profile already exists
        $sql = "SELECT id FROM " . TB_PREF . "fa_crm_customers WHERE debtor_no = " . db_escape($debtor_no);
        $result = db_query($sql);
        
        if (db_num_rows($result) > 0) {
            return false; // Already exists
        }

        $customer_since = isset($data['customer_since']) ? $data['customer_since'] : date('Y-m-d');
        
        $sql = "INSERT INTO " . TB_PREF . "fa_crm_customers 
            (debtor_no, customer_since, created_at) VALUES 
            (" . db_escape($debtor_no) . ", " . db_escape($customer_since) . ", NOW())";
        
        db_query($sql, "Could not create CRM profile");
        
        return true;
    }

    /**
     * Log activity
     */
    private function log_activity($debtor_no, $action, $details = '', $data = array()) {
        $user_id = isset($_SESSION['wa_current_user']) ? $_SESSION['wa_current_user']->user : 'system';
        
        $sql = "INSERT INTO " . TB_PREF . "fa_crm_activity_log 
            (entity_type, entity_id, debtor_no, user_id, action, details, new_values, created_at) VALUES 
            ('customer', 0, " . db_escape($debtor_no) . ", " . db_escape($user_id) . ", 
             " . db_escape($action) . ", " . db_escape($details) . ", 
             " . db_escape(json_encode($data)) . ", NOW())";
        
        db_query($sql, "Could not log activity");
    }

    /**
     * Display extra CRM fields on customer form
     */
    public function display_customer_crm_fields($debtor_no) {
        // This would display CRM-specific fields on the customer form
        // Implemented in pages/customer_crm.php
    }
}

/**
 * Get module info for FA module manager
 */
function fa_crm_get_module_info() {
    return array(
        'name' => $module_name,
        'version' => $module_version,
        'description' => $module_description,
        'author' => $module_author,
        'category' => $module_category,
        'depends' => array(),
    );
}

/**
 * Install module hook
 */
function fa_crm_install() {
    require_once __DIR__ . '/hooks.php';
    return fa_crm_install();
}

/**
 * Activate module hook
 */
function fa_crm_activate() {
    require_once __DIR__ . '/hooks.php';
    return fa_crm_activate();
}

/**
 * Deactivate module hook
 */
function fa_crm_deactivate() {
    require_once __DIR__ . '/hooks.php';
    return fa_crm_deactivate();
}

/**
 * Uninstall module hook
 */
function fa_crm_uninstall() {
    require_once __DIR__ . '/hooks.php';
    return fa_crm_uninstall();
}

/**
 * Get menu items for the module
 */
function fa_crm_get_menu_items() {
    return array(
        // Top-level CRM menu
        array(
            'title' => 'CRM',
            'heading' => true,
            'order' => 50,
        ),
        array(
            'title' => 'CRM Dashboard',
            'url' => '/modules/FA_CRM/pages/dashboard.php',
            'access' => 'SA_CUSTOMER',
            'parent' => 'CRM',
            'order' => 1,
        ),
        array(
            'title' => 'Enhanced Customers',
            'url' => '/modules/FA_CRM/pages/customers.php',
            'access' => 'SA_CUSTOMER',
            'parent' => 'CRM',
            'order' => 2,
        ),
        array(
            'title' => 'Opportunities',
            'url' => '/modules/FA_CRM/pages/opportunities.php',
            'access' => 'CRM_VIEW_QUALIFY',
            'parent' => 'CRM',
            'order' => 3,
        ),
        array(
            'title' => 'Communications',
            'url' => '/modules/FA_CRM/pages/communications.php',
            'access' => 'CRM_VIEW_COMMUNICATIONS',
            'parent' => 'CRM',
            'order' => 4,
        ),
        array(
            'title' => 'Calendar',
            'url' => '/modules/FA_CRM/pages/calendar.php',
            'access' => 'CRM_VIEW_CALENDAR',
            'parent' => 'CRM',
            'order' => 5,
        ),
        array(
            'title' => 'Reports',
            'url' => '/modules/FA_CRM/pages/reports.php',
            'access' => 'CRM_VIEW_ANALYTICS',
            'parent' => 'CRM',
            'order' => 6,
        ),
        array(
            'title' => 'Settings',
            'url' => '/modules/FA_CRM/pages/settings.php',
            'access' => 'CRM_ADMIN',
            'parent' => 'CRM',
            'order' => 7,
        ),
        
        // Sales sub-menu items
        array(
            'title' => 'Enhanced Customers',
            'url' => '/modules/FA_CRM/pages/customers.php',
            'access' => 'SA_CUSTOMER',
            'parent' => 'Sales',
            'order' => 20,
        ),
        array(
            'title' => 'Opportunities',
            'url' => '/modules/FA_CRM/pages/opportunities.php',
            'access' => 'CRM_VIEW_QUALIFY',
            'parent' => 'Sales',
            'order' => 21,
        ),
    );
}
