<?php
/**
 * FA_CRM Module Hooks for FrontAccounting
 *
 * Handles module installation, activation, and database setup
 *
 * @package FA_CRM
 * @version 1.0.0
 * @author KSFII Development Team
 */

// Module metadata
$module_name = 'FA_CRM';
$module_version = '1.0.0';
$module_description = 'Advanced Customer Relationship Management for FrontAccounting';
$module_author = 'KSFII Development Team';
$module_category = 'Sales';

/**
 * Install hook - called when module is installed
 */
function fa_crm_install() {
    global $db;

    @include_once __DIR__ . '/vendor-src/Ksfraser/Common/ComposerDependencyManager.php';
    if (class_exists('Ksfraser\Common\ComposerDependencyManager')) {
        $composerMgr = new \Ksfraser\Common\ComposerDependencyManager(__DIR__);
        $composerMgr->ensureDependencies();
        @include_once $composerMgr->getAutoloadPath();
    }

    // Create database tables
    if (!fa_crm_create_tables()) {
        return false;
    }

    // Insert initial data
    if (!fa_crm_insert_initial_data()) {
        return false;
    }

    // Set default preferences
    if (!fa_crm_set_default_preferences()) {
        return false;
    }

    return true;
}

/**
 * Activate hook - called when module is activated
 */
function fa_crm_activate() {
    @include_once __DIR__ . '/vendor-src/Ksfraser/Common/ComposerDependencyManager.php';
    if (class_exists('Ksfraser\Common\ComposerDependencyManager')) {
        $composerMgr = new \Ksfraser\Common\ComposerDependencyManager(__DIR__);
        $composerMgr->ensureDependencies();
        @include_once $composerMgr->getAutoloadPath();
    }

    // Register hooks
    add_hook('customer_delete', 'fa_crm_customer_delete');
    add_hook('customer_update', 'fa_crm_customer_update');

    // Enable features
    fa_crm_enable_features();

    return true;
}

/**
 * Deactivate hook - called when module is deactivated
 */
function fa_crm_deactivate() {
    return true;
}

/**
 * Uninstall hook - called when module is uninstalled
 */
function fa_crm_uninstall() {
    return true;
}

/**
 * Create database tables for FA_CRM module
 */
function fa_crm_create_tables() {
    global $db;

    $sql_statements = array(
        // CRM Customers table
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_customers` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `debtor_no` VARCHAR(20) NOT NULL,
            `customer_type_id` INT(11) DEFAULT NULL,
            `customer_segment_id` INT(11) DEFAULT NULL,
            `territory_id` INT(11) DEFAULT NULL,
            `customer_since` DATE DEFAULT NULL,
            `website` VARCHAR(255) DEFAULT NULL,
            `industry` VARCHAR(100) DEFAULT NULL,
            `employee_count` INT(11) DEFAULT NULL,
            `annual_revenue` DECIMAL(15,2) DEFAULT NULL,
            `parent_company` VARCHAR(100) DEFAULT NULL,
            `latitude` DECIMAL(10,8) DEFAULT NULL,
            `longitude` DECIMAL(11,8) DEFAULT NULL,
            `edi_enabled` TINYINT(1) DEFAULT 0,
            `marketing_opt_out` TINYINT(1) DEFAULT 0,
            `preferred_contact_method` VARCHAR(20) DEFAULT 'email',
            `last_contact_date` DATETIME DEFAULT NULL,
            `next_followup_date` DATETIME DEFAULT NULL,
            `account_manager` VARCHAR(100) DEFAULT NULL,
            `credit_rating` VARCHAR(20) DEFAULT 'good',
            `payment_reliability` DECIMAL(5,2) DEFAULT 100.00,
            `inactive` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `idx_debtor_no` (`debtor_no`),
            KEY `idx_customer_type` (`customer_type_id`),
            KEY `idx_territory` (`territory_id`),
            KEY `idx_inactive` (`inactive`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Contacts table
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_contacts` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `debtor_no` VARCHAR(20) NOT NULL,
            `contact_role_id` INT(11) DEFAULT NULL,
            `first_name` VARCHAR(50) NOT NULL,
            `last_name` VARCHAR(50) NOT NULL,
            `title` VARCHAR(50) DEFAULT NULL,
            `department` VARCHAR(50) DEFAULT NULL,
            `phone` VARCHAR(20) DEFAULT NULL,
            `mobile` VARCHAR(20) DEFAULT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `address` TEXT,
            `notes` TEXT,
            `is_primary` TINYINT(1) DEFAULT 0,
            `inactive` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_debtor_no` (`debtor_no`),
            KEY `idx_is_primary` (`is_primary`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Opportunities table
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_opportunities` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `opportunity_name` VARCHAR(100) NOT NULL,
            `debtor_no` VARCHAR(20) DEFAULT NULL,
            `contact_id` INT(11) DEFAULT NULL,
            `sales_person` VARCHAR(100) DEFAULT NULL,
            `opportunity_type` VARCHAR(50) DEFAULT NULL,
            `realm` VARCHAR(50) DEFAULT NULL,
            `status` VARCHAR(20) DEFAULT 'prospecting',
            `stage` VARCHAR(30) DEFAULT 'qualification',
            `source` VARCHAR(50) DEFAULT NULL,
            `estimated_value` DECIMAL(15,2) DEFAULT NULL,
            `probability` DECIMAL(5,2) DEFAULT 0,
            `expected_close_date` DATE DEFAULT NULL,
            `actual_close_date` DATE DEFAULT NULL,
            `lost_reason` TEXT,
            `won_notes` TEXT,
            `notes` TEXT,
            `assigned_to` VARCHAR(100) DEFAULT NULL,
            `lead_id` INT(11) DEFAULT NULL,
            `campaign_id` INT(11) DEFAULT NULL,
            `quote_id` INT(11) DEFAULT NULL,
            `project_id` INT(11) DEFAULT NULL,
            `inactive` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_debtor_no` (`debtor_no`),
            KEY `idx_lead_id` (`lead_id`),
            KEY `idx_realm` (`realm`),
            KEY `idx_status` (`status`),
            KEY `idx_stage` (`stage`),
            KEY `idx_expected_close` (`expected_close_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Communications table
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_communications` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `debtor_no` VARCHAR(20) DEFAULT NULL,
            `contact_id` INT(11) DEFAULT NULL,
            `opportunity_id` INT(11) DEFAULT NULL,
            `communication_type` VARCHAR(20) NOT NULL,
            `direction` VARCHAR(10) DEFAULT 'outbound',
            `subject` VARCHAR(255) DEFAULT NULL,
            `message` TEXT,
            `email_from` VARCHAR(100) DEFAULT NULL,
            `email_to` VARCHAR(100) DEFAULT NULL,
            `phone_number` VARCHAR(20) DEFAULT NULL,
            `duration_minutes` INT(11) DEFAULT NULL,
            `status` VARCHAR(20) DEFAULT 'completed',
            `scheduled_date` DATETIME DEFAULT NULL,
            `completed_date` DATETIME DEFAULT NULL,
            `assigned_to` VARCHAR(100) DEFAULT NULL,
            `priority` VARCHAR(10) DEFAULT 'medium',
            `follow_up_required` TINYINT(1) DEFAULT 0,
            `follow_up_date` DATETIME DEFAULT NULL,
            `notes` TEXT,
            `email_message_id` VARCHAR(255) DEFAULT NULL,
            `attachment_path` VARCHAR(500) DEFAULT NULL,
            `created_by` VARCHAR(100) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_debtor_no` (`debtor_no`),
            KEY `idx_follow_up` (`follow_up_required`, `follow_up_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Customer Types
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_customer_types` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) DEFAULT NULL,
            `inactive` TINYINT(1) DEFAULT 0,
            `sort_order` INT(11) DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Territories
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_territories` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) DEFAULT NULL,
            `region` VARCHAR(50) DEFAULT NULL,
            `inactive` TINYINT(1) DEFAULT 0,
            `sort_order` INT(11) DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Activity Log
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_activity_log` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `activity_type` VARCHAR(30) NOT NULL,
            `entity_type` VARCHAR(30) NOT NULL,
            `entity_id` INT(11) NOT NULL,
            `debtor_no` VARCHAR(20) DEFAULT NULL,
            `user_id` VARCHAR(100) DEFAULT NULL,
            `action` VARCHAR(50) NOT NULL,
            `details` TEXT,
            `old_values` TEXT,
            `new_values` TEXT,
            `ip_address` VARCHAR(45) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_entity` (`entity_type`, `entity_id`),
            KEY `idx_debtor_no` (`debtor_no`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Leads table (links to inactive debtors as leads)
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_leads` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `debtor_no` VARCHAR(20) NOT NULL,
            `lead_source` VARCHAR(50) DEFAULT NULL,
            `lead_status` VARCHAR(30) DEFAULT 'new',
            `rating` VARCHAR(30) DEFAULT NULL,
            `annual_revenue` DECIMAL(15,2) DEFAULT NULL,
            `employee_count` INT(11) DEFAULT NULL,
            `industry` VARCHAR(50) DEFAULT NULL,
            `website` VARCHAR(255) DEFAULT NULL,
            `phone` VARCHAR(20) DEFAULT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `address` TEXT,
            `assigned_to` VARCHAR(100) DEFAULT NULL,
            `campaign_id` INT(11) DEFAULT NULL,
            `converted_date` DATETIME DEFAULT NULL,
            `converted_to_debtor_no` VARCHAR(20) DEFAULT NULL,
            `notes` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `idx_debtor_no` (`debtor_no`),
            KEY `idx_lead_status` (`lead_status`),
            KEY `idx_assigned_to` (`assigned_to`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Contact-Account Junction table (contact can link to multiple accounts)
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_contact_accounts` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `contact_id` INT(11) NOT NULL,
            `debtor_no` VARCHAR(20) NOT NULL,
            `is_primary` TINYINT(1) DEFAULT 0,
            `role` VARCHAR(50) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `idx_contact_debtor` (`contact_id`, `debtor_no`),
KEY `idx_debtor_no` (`debtor_no`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Realms (opportunity types/categories for workflow)
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_realms` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) DEFAULT NULL,
            `requires_quote` TINYINT(1) DEFAULT 0,
            `requires_project` TINYINT(1) DEFAULT 0,
            `default_stage` VARCHAR(30) DEFAULT 'qualification',
            `stages_json` TEXT,
            `inactive` TINYINT(1) DEFAULT 0,
            `sort_order` INT(11) DEFAULT 0,
            PRIMARY KEY (`id`),
            UNIQUE KEY `idx_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Sales Quotes table
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_quotes` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `quote_no` VARCHAR(30) NOT NULL,
            `opportunity_id` INT(11) DEFAULT NULL,
            `debtor_no` VARCHAR(20) DEFAULT NULL,
            `contact_id` INT(11) DEFAULT NULL,
            `quote_date` DATE DEFAULT NULL,
            `valid_until` DATE DEFAULT NULL,
            `status` VARCHAR(20) DEFAULT 'draft',
            `subtotal` DECIMAL(15,2) DEFAULT 0,
            `tax_rate` DECIMAL(5,2) DEFAULT 0,
            `tax_amount` DECIMAL(15,2) DEFAULT 0,
            `total` DECIMAL(15,2) DEFAULT 0,
            `notes` TEXT,
            `terms` TEXT,
            `created_by` VARCHAR(100) DEFAULT NULL,
            `approved_by` VARCHAR(100) DEFAULT NULL,
            `approved_date` DATETIME DEFAULT NULL,
            `sent_date` DATETIME DEFAULT NULL,
            `accepted_date` DATETIME DEFAULT NULL,
            `rejected_date` DATETIME DEFAULT NULL,
            `inactive` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `idx_quote_no` (`quote_no`),
            KEY `idx_opportunity_id` (`opportunity_id`),
            KEY `idx_debtor_no` (`debtor_no`),
            KEY `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        // CRM Quote Line Items
        "CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_quote_items` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `quote_id` INT(11) NOT NULL,
            `line_number` INT(11) DEFAULT 0,
            `item_description` VARCHAR(255) NOT NULL,
            `quantity` DECIMAL(10,2) DEFAULT 1,
            `unit_price` DECIMAL(15,2) DEFAULT 0,
            `unit` VARCHAR(20) DEFAULT NULL,
            `discount_percent` DECIMAL(5,2) DEFAULT 0,
            `discount_amount` DECIMAL(15,2) DEFAULT 0,
            `line_total` DECIMAL(15,2) DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `idx_quote_id` (`quote_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"

    );

    foreach ($sql_statements as $sql) {
        if (!db_query($sql, "Could not create FA_CRM table")) {
            return false;
        }
    }

    return true;
}

/**
 * Insert initial data for FA_CRM module
 */
function fa_crm_insert_initial_data() {
    $customer_types = array(
        array('name' => 'Prospect', 'description' => 'Potential new customer', 'sort_order' => 1),
        array('name' => 'Active', 'description' => 'Current active customer', 'sort_order' => 2),
        array('name' => 'Inactive', 'description' => 'Former customer', 'sort_order' => 3),
        array('name' => 'VIP', 'description' => 'High-value customer', 'sort_order' => 4),
        array('name' => 'Partner', 'description' => 'Business partner', 'sort_order' => 5),
    );

    foreach ($customer_types as $type) {
        $sql = "INSERT IGNORE INTO " . TB_PREF . "fa_crm_customer_types
            (name, description, sort_order) VALUES
            (" . db_escape($type['name']) . ", " . db_escape($type['description']) . ", " . db_escape($type['sort_order']) . ")";
        db_query($sql, "Could not insert customer type");
    }

    $territories = array(
        array('name' => 'North', 'description' => 'Northern region', 'region' => 'North', 'sort_order' => 1),
        array('name' => 'South', 'description' => 'Southern region', 'region' => 'South', 'sort_order' => 2),
        array('name' => 'East', 'description' => 'Eastern region', 'region' => 'East', 'sort_order' => 3),
        array('name' => 'West', 'description' => 'Western region', 'region' => 'West', 'sort_order' => 4),
        array('name' => 'Central', 'description' => 'Central region', 'region' => 'Central', 'sort_order' => 5),
    );

    foreach ($territories as $territory) {
        $sql = "INSERT IGNORE INTO " . TB_PREF . "fa_crm_territories
            (name, description, region, sort_order) VALUES
            (" . db_escape($territory['name']) . ", " . db_escape($territory['description']) . ",
             " . db_escape($territory['region']) . ", " . db_escape($territory['sort_order']) . ")";
        db_query($sql, "Could not insert territory");
    }

    return true;
}

/**
 * Set default preferences
 */
function fa_crm_set_default_preferences() {
    $preferences = array(
        'crm_default_status' => 'prospecting',
        'crm_default_stage' => 'qualification',
        'crm_activity_log_enabled' => '1',
        'crm_auto_followup_days' => '7',
        'crm_default_credit_rating' => 'good',
    );

    foreach ($preferences as $name => $value) {
        $sql = "INSERT IGNORE INTO " . TB_PREF . "crm_preferences
            (name, value) VALUES (" . db_escape($name) . ", " . db_escape($value) . ")";
        db_query($sql, "Could not set preference: $name");
    }

    return true;
}

function fa_crm_enable_features() {
    return true;
}

function fa_crm_customer_delete($customer_id) {
    $sql = "UPDATE " . TB_PREF . "fa_crm_customers SET inactive = 1 WHERE debtor_no = " . db_escape($customer_id);
    db_query($sql, "Could not soft delete CRM customer");
    $sql = "UPDATE " . TB_PREF . "fa_crm_contacts SET inactive = 1 WHERE debtor_no = " . db_escape($customer_id);
    db_query($sql, "Could not soft delete CRM contacts");
    return true;
}

function fa_crm_customer_update($customer_id, $data) {
    return true;
}

function fa_crm_get_module_info() {
    return array(
        'name' => 'FA_CRM',
        'version' => '1.0.0',
        'description' => 'Advanced Customer Relationship Management',
        'author' => 'KSFII Development Team',
        'category' => 'Sales',
    );
}
