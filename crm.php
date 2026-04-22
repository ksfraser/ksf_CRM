<?php
/**
 * FA_CRM API Controller
 *
 * Main controller for FA_CRM module routes
 *
 * @package FA_CRM
 * @version 1.0.0
 * @author KSFII Development Team
 */

// Load module components
$path_to_root = "../../..";
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");
include_once($path_to_root . "/includes/db.inc");

// Load CRM database functions
include_once(__DIR__ . "/includes/crm_db.inc");
include_once(__DIR__ . "/includes/crm_ui.inc");

// Determine section from request
$section = isset($_GET['section']) ? $_GET['section'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Security check
if (!$session->check_access('SA_CUSTOMER')) {
    display_error("Access denied");
    exit;
}

/**
 * Route requests to appropriate handler
 */
function fa_crm_route($section, $action) {
    switch ($section) {
        case 'dashboard':
            return fa_crm_dashboard($action);
        case 'customers':
            return fa_crm_customers($action);
        case 'opportunities':
            return fa_crm_opportunities($action);
        case 'communications':
            return fa_crm_communications($action);
        case 'calendar':
            return fa_crm_calendar($action);
        case 'reports':
            return fa_crm_reports($action);
        case 'settings':
            return fa_crm_settings($action);
        default:
            return fa_crm_dashboard('list');
    }
}

/**
 * Dashboard section
 */
function fa_crm_dashboard($action) {
    global $path_to_root;
    
    page(_("CRM Dashboard"), false, false, "", "");
    
    // Display CRM navigation
    start_table(TABLESTYLE_NOBORDER);
    start_row();
    crm_navigation_menu();
    end_row();
    end_table();
    
    echo '<br>';
    
    // Dashboard widgets
    $dashboard_items = array(
        'total_customers' => get_crm_customer_count(),
        'active_opportunities' => get_crm_opportunity_count('active'),
        'pending_followups' => get_crm_followup_count(),
        'communications_today' => get_crm_communication_count_today(),
    );
    
    // Display stats
    display_crm_dashboard_stats($dashboard_items);
    
    // Display recent activities
    display_crm_recent_activities();
    
    page_end();
}

/**
 * Customers section
 */
function fa_crm_customers($action) {
    switch ($action) {
        case 'list':
            fa_crm_list_customers();
            break;
        case 'edit':
            fa_crm_edit_customer();
            break;
        case 'view':
            fa_crm_view_customer();
            break;
        case 'add':
            fa_crm_add_customer();
            break;
        default:
            fa_crm_list_customers();
    }
}

/**
 * List all CRM customers
 */
function fa_crm_list_customers() {
    page(_("Enhanced Customers"), false, false, "", "");
    
    $debtor_no = isset($_GET['debtor_no']) ? $_GET['debtor_no'] : '';
    
    // Search and filter
    $search = isset($_POST['Search']) ? $_POST['Search'] : '';
    $filter_type = isset($_GET['type']) ? $_GET['type'] : '';
    
    start_table(TABLESTYLE);
    
    // Header
    echo '<tr><th colspan="8">' . _("Enhanced Customers") . '</th></tr>';
    echo '<tr>';
    echo '<th>' . _("Customer") . '</th>';
    echo '<th>' . _("Type") . '</th>';
    echo '<th>' . _("Industry") . '</th>';
    echo '<th>' . _("Territory") . '</th>';
    echo '<th>' . _("Account Manager") . '</th>';
    echo '<th>' . _("Last Contact") . '</th>';
    echo '<th>' . _("Credit Rating") . '</th>';
    echo '<th>' . _("Actions") . '</th>';
    echo '</tr>';
    
    // Get customers with CRM data
    $customers = get_crm_customers($search, $filter_type);
    
    while ($customer = db_fetch_assoc($customers)) {
        echo '<tr class="'.($i%2==0?'evenrow':'oddrow').'">';
        echo '<td>' . $customer['name'] . '</td>';
        echo '<td>' . $customer['type_name'] . '</td>';
        echo '<td>' . $customer['industry'] . '</td>';
        echo '<td>' . $customer['territory_name'] . '</td>';
        echo '<td>' . $customer['account_manager'] . '</td>';
        echo '<td>' . $customer['last_contact_date'] . '</td>';
        echo '<td>' . $customer['credit_rating'] . '</td>';
        echo '<td>';
        echo '<a href="?section=customers&action=view&debtor_no=' . $customer['debtor_no'] . '">View</a> | ';
        echo '<a href="?section=customers&action=edit&debtor_no=' . $customer['debtor_no'] . '">Edit</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    
    end_table();
    
    page_end();
}

/**
 * Edit customer CRM data
 */
function fa_crm_edit_customer() {
    $debtor_no = isset($_GET['debtor_no']) ? $_GET['debtor_no'] : '';
    
    if ($debtor_no == '') {
        display_error("No customer specified");
        return;
    }
    
    page(_("Edit Customer CRM Data"), true, false, "", "");
    
    // Handle form submission
    if (isset($_POST['UPDATE'])) {
        $crm_data = array(
            'customer_type_id' => $_POST['customer_type_id'],
            'territory_id' => $_POST['territory_id'],
            'industry' => $_POST['industry'],
            'website' => $_POST['website'],
            'annual_revenue' => $_POST['annual_revenue'],
            'account_manager' => $_POST['account_manager'],
            'credit_rating' => $_POST['credit_rating'],
            'preferred_contact_method' => $_POST['preferred_contact_method'],
            'next_followup_date' => $_POST['next_followup_date'],
        );
        
        update_customer_crm_data($debtor_no, $crm_data);
        display_notification("Customer CRM data updated");
    }
    
    // Get customer data
    $customer = get_crm_customer($debtor_no);
    $customer_types = get_crm_customer_types();
    $territories = get_crm_territories();
    
    start_form();
    start_table(TABLESTYLE);
    
    table_header(_("Edit CRM Data for") . " " . $customer['name']);
    
    // CRM Fields
    row(label_cell(_("Customer Type")));
    cell(sel_customer_type($customer['customer_type_id']));
    end_row();
    
    row(label_cell(_("Territory")));
    cell(sel_territory($customer['territory_id']));
    end_row();
    
    row(label_cell(_("Industry")));
    cell(text_input('industry', $customer['industry'], 30));
    end_row();
    
    row(label_cell(_("Website")));
    cell(text_input('website', $customer['website'], 50));
    end_row();
    
    row(label_cell(_("Annual Revenue")));
    cell(amount_input($customer['annual_revenue'], 'annual_revenue'));
    end_row();
    
    row(label_cell(_("Account Manager")));
    cell(text_input('account_manager', $customer['account_manager'], 30));
    end_row();
    
    row(label_cell(_("Credit Rating")));
    cell(sel_credit_rating($customer['credit_rating']));
    end_row();
    
    row(label_cell(_("Preferred Contact")));
    cell(sel_contact_method($customer['preferred_contact_method']));
    end_row();
    
    row(label_cell(_("Next Follow-up")));
    cell(date_input('next_followup_date', $customer['next_followup_date']));
    end_row();
    
    end_table();
    
    submit_row('UPDATE', _("Update"), true, '', 'default');
    
    end_form();
    page_end();
}

/**
 * View customer details
 */
function fa_crm_view_customer() {
    $debtor_no = isset($_GET['debtor_no']) ? $_GET['debtor_no'] : '';
    
    if ($debtor_no == '') {
        display_error("No customer specified");
        return;
    }
    
    page(_("Customer CRM Details"), true, false, "", "");
    
    $customer = get_crm_customer($debtor_no);
    
    display_heading($customer['name']);
    
    start_table(TABLESTYLE);
    
    row(label_cell(_("Customer Since")), cell($customer['customer_since']));
    row(label_cell(_("Type")), cell($customer['type_name']));
    row(label_cell(_("Industry")), cell($customer['industry']));
    row(label_cell(_("Territory")), cell($customer['territory_name']));
    row(label_cell(_("Website")), cell($customer['website']));
    row(label_cell(_("Annual Revenue")), cell($customer['annual_revenue']));
    row(label_cell(_("Account Manager")), cell($customer['account_manager']));
    row(label_cell(_("Credit Rating")), cell($customer['credit_rating']));
    row(label_cell(_("Last Contact")), cell($customer['last_contact_date']));
    row(label_cell(_("Next Follow-up")), cell($customer['next_followup_date']));
    
    end_table();
    
    echo '<br><a href="?section=customers&action=edit&debtor_no=' . $debtor_no . '">' . _("Edit CRM Data") . '</a>';
    
    page_end();
}

/**
 * Add new customer (redirects to FA customer creation)
 */
function fa_crm_add_customer() {
    // Redirect to FA customer creation
    header("Location: $path_to_root/sales/manage/customers.php?NewCustomer=Yes");
    exit;
}

/**
 * Opportunities section
 */
function fa_crm_opportunities($action) {
    switch ($action) {
        case 'list':
            fa_crm_list_opportunities();
            break;
        case 'edit':
            fa_crm_edit_opportunity();
            break;
        case 'view':
            fa_crm_view_opportunity();
            break;
        case 'add':
            fa_crm_add_opportunity();
            break;
        case 'update_stage':
            fa_crm_update_opportunity_stage();
            break;
        default:
            fa_crm_list_opportunities();
    }
}

/**
 * List opportunities
 */
function fa_crm_list_opportunities() {
    page(_("Sales Opportunities"), false, false, "", "");
    
    $filter_status = isset($_GET['status']) ? $_GET['status'] : '';
    
    start_table(TABLESTYLE);
    
    echo '<tr><th colspan="8">' . _("Sales Opportunities") . '</th></tr>';
    echo '<tr>';
    echo '<th>' . _("Opportunity") . '</th>';
    echo '<th>' . _("Customer") . '</th>';
    echo '<th>' . _("Value") . '</th>';
    echo '<th>' . _("Stage") . '</th>';
    echo '<th>' . _("Probability") . '</th>';
    echo '<th>' . _("Expected Close") . '</th>';
    echo '<th>' . _("Owner") . '</th>';
    echo '<th>' . _("Actions") . '</th>';
    echo '</tr>';
    
    $opportunities = get_crm_opportunities($filter_status);
    
    while ($opp = db_fetch_assoc($opportunities)) {
        $stage_class = get_stage_class($opp['stage']);
        
        echo '<tr>';
        echo '<td>' . $opp['opportunity_name'] . '</td>';
        echo '<td>' . $opp['customer_name'] . '</td>';
        echo '<td>' . $opp['estimated_value'] . '</td>';
        echo '<td class="' . $stage_class . '">' . $opp['stage'] . '</td>';
        echo '<td>' . $opp['probability'] . '%</td>';
        echo '<td>' . $opp['expected_close_date'] . '</td>';
        echo '<td>' . $opp['assigned_to'] . '</td>';
        echo '<td>';
        echo '<a href="?section=opportunities&action=view&id=' . $opp['id'] . '">View</a> | ';
        echo '<a href="?section=opportunities&action=edit&id=' . $opp['id'] . '">Edit</a>';
        echo '</td>';
        echo '</tr>';
    }
    
    end_table();
    
    echo '<br><a href="?section=opportunities&action=add">' . _("New Opportunity") . '</a>';
    
    page_end();
}

/**
 * Add/Edit opportunity
 */
function fa_crm_edit_opportunity() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    $debtor_no = isset($_GET['debtor_no']) ? $_GET['debtor_no'] : '';
    
    page(_("Opportunity"), true, false, "", "");
    
    if (isset($_POST['SAVE'])) {
        $opp_data = array(
            'opportunity_name' => $_POST['opportunity_name'],
            'debtor_no' => $_POST['debtor_no'],
            'opportunity_type' => $_POST['opportunity_type'],
            'stage' => $_POST['stage'],
            'estimated_value' => $_POST['estimated_value'],
            'probability' => $_POST['probability'],
            'expected_close_date' => $_POST['expected_close_date'],
            'notes' => $_POST['notes'],
            'assigned_to' => $_POST['assigned_to'],
        );
        
        if ($id > 0) {
            update_crm_opportunity($id, $opp_data);
            display_notification("Opportunity updated");
        } else {
            $id = insert_crm_opportunity($opp_data);
            display_notification("Opportunity created");
        }
    }
    
    // Get data for editing
    $opportunity = $id > 0 ? get_crm_opportunity($id) : array();
    
    start_form();
    start_table(TABLESTYLE);
    
    table_header($id > 0 ? _("Edit Opportunity") : _("New Opportunity"));
    
    row(label_cell(_("Opportunity Name")));
    cell(text_input('opportunity_name', $opportunity['opportunity_name'] ?? '', 40));
    end_row();
    
    row(label_cell(_("Customer")));
    cell(sel_customer($opportunity['debtor_no'] ?? $debtor_no));
    end_row();
    
    row(label_cell(_("Type")));
    cell(sel_opportunity_type($opportunity['opportunity_type'] ?? ''));
    end_row();
    
    row(label_cell(_("Stage")));
    cell(sel_stage($opportunity['stage'] ?? 'qualification'));
    end_row();
    
    row(label_cell(_("Estimated Value")));
    cell(amount_input($opportunity['estimated_value'] ?? 0, 'estimated_value'));
    end_row();
    
    row(label_cell(_("Probability (%)")));
    cell(text_input('probability', $opportunity['probability'] ?? 0, 5));
    end_row();
    
    row(label_cell(_("Expected Close Date")));
    cell(date_input('expected_close_date', $opportunity['expected_close_date'] ?? ''));
    end_row();
    
    row(label_cell(_("Assigned To")));
    cell(text_input('assigned_to', $opportunity['assigned_to'] ?? ''));
    end_row();
    
    row(label_cell(_("Notes")));
    cell(textarea('notes', $opportunity['notes'] ?? '', 50, 5));
    end_row();
    
    end_table();
    
    submit_row('SAVE', _("Save"), true, '', 'default');
    
    end_form();
    page_end();
}

/**
 * View opportunity
 */
function fa_crm_view_opportunity() {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    
    if ($id == 0) {
        display_error("No opportunity specified");
        return;
    }
    
    $opportunity = get_crm_opportunity($id);
    
    page(_("Opportunity Details"), true, false, "", "");
    
    display_heading($opportunity['opportunity_name']);
    
    start_table(TABLESTYLE);
    
    row(label_cell(_("Customer")), cell($opportunity['customer_name']));
    row(label_cell(_("Type")), cell($opportunity['opportunity_type']));
    row(label_cell(_("Stage")), cell($opportunity['stage']));
    row(label_cell(_("Value")), cell($opportunity['estimated_value']));
    row(label_cell(_("Probability")), cell($opportunity['probability'] . '%'));
    row(label_cell(_("Expected Close")), cell($opportunity['expected_close_date']));
    row(label_cell(_("Assigned To")), cell($opportunity['assigned_to']));
    row(label_cell(_("Notes")), cell($opportunity['notes']));
    
    end_table();
    
    echo '<br><a href="?section=opportunities&action=edit&id=' . $id . '">' . _("Edit") . '</a>';
    
    page_end();
}

/**
 * Add new opportunity
 */
function fa_crm_add_opportunity() {
    fa_crm_edit_opportunity();
}

/**
 * Update opportunity stage (AJAX)
 */
function fa_crm_update_opportunity_stage() {
    $id = $_POST['id'];
    $stage = $_POST['stage'];
    
    update_crm_opportunity_stage($id, $stage);
    
    echo json_encode(array('success' => true));
    exit;
}

/**
 * Communications section
 */
function fa_crm_communications($action) {
    switch ($action) {
        case 'list':
            fa_crm_list_communications();
            break;
        case 'add':
            fa_crm_add_communication();
            break;
        default:
            fa_crm_list_communications();
    }
}

/**
 * List communications
 */
function fa_crm_list_communications() {
    page(_("Communications"), false, false, "", "");
    
    start_table(TABLESTYLE);
    
    echo '<tr><th colspan="7">' . _("Communication Log") . '</th></tr>';
    echo '<tr>';
    echo '<th>' . _("Date") . '</th>';
    echo '<th>' . _("Customer") . '</th>';
    echo '<th>' . _("Type") . '</th>';
    echo '<th>' . _("Subject") . '</th>';
    echo '<th>' . _("Direction") . '</th>';
    echo '<th>' . _("Follow-up") . '</th>';
    echo '<th>' . _("Actions") . '</th>';
    echo '</tr>';
    
    $comms = get_crm_communications();
    
    while ($comm = db_fetch_assoc($comms)) {
        $followup_class = $comm['follow_up_required'] && $comm['follow_up_date'] < date('Y-m-d') ? 'overdue' : '';
        
        echo '<tr>';
        echo '<td>' . $comm['created_at'] . '</td>';
        echo '<td>' . $comm['customer_name'] . '</td>';
        echo '<td>' . $comm['communication_type'] . '</td>';
        echo '<td>' . $comm['subject'] . '</td>';
        echo '<td>' . $comm['direction'] . '</td>';
        echo '<td class="' . $followup_class . '">' . ($comm['follow_up_required'] ? $comm['follow_up_date'] : '-') . '</td>';
        echo '<td><a href="?section=communications&action=view&id=' . $comm['id'] . '">View</a></td>';
        echo '</tr>';
    }
    
    end_table();
    
    echo '<br><a href="?section=communications&action=add">' . _("Log Communication") . '</a>';
    
    page_end();
}

/**
 * Add communication
 */
function fa_crm_add_communication() {
    page(_("Log Communication"), true, false, "", "");
    
    if (isset($_POST['SAVE'])) {
        $comm_data = array(
            'debtor_no' => $_POST['debtor_no'],
            'contact_id' => $_POST['contact_id'],
            'communication_type' => $_POST['communication_type'],
            'direction' => $_POST['direction'],
            'subject' => $_POST['subject'],
            'message' => $_POST['message'],
            'follow_up_required' => isset($_POST['follow_up_required']),
            'follow_up_date' => $_POST['follow_up_date'],
            'notes' => $_POST['notes'],
        );
        
        insert_crm_communication($comm_data);
        display_notification("Communication logged");
    }
    
    start_form();
    start_table(TABLESTYLE);
    
    table_header(_("Log New Communication"));
    
    row(label_cell(_("Customer")));
    cell(sel_customer($_POST['debtor_no'] ?? ''));
    end_row();
    
    row(label_cell(_("Type")));
    cell(sel_communication_type($_POST['communication_type'] ?? ''));
    end_row();
    
    row(label_cell(_("Direction")));
    cell(sel_direction($_POST['direction'] ?? 'outbound'));
    end_row();
    
    row(label_cell(_("Subject")));
    cell(text_input('subject', ''));
    end_row();
    
    row(label_cell(_("Message")));
    cell(textarea('message', '', 50, 5));
    end_row();
    
    row(label_cell(_("Follow-up Required")));
    cell(check_input('follow_up_required'));
    end_row();
    
    row(label_cell(_("Follow-up Date")));
    cell(date_input('follow_up_date', ''));
    end_row();
    
    end_table();
    
    submit_row('SAVE', _("Save"), true, '', 'default');
    
    end_form();
    page_end();
}

/**
 * Calendar section
 */
function fa_crm_calendar($action) {
    page(_("CRM Calendar"), false, false, "", "");
    
    echo '<p>Calendar view coming soon</p>';
    
    page_end();
}

/**
 * Reports section
 */
function fa_crm_reports($action) {
    page(_("CRM Reports"), false, false, "", "");
    
    echo '<h3>Available Reports</h3>';
    echo '<ul>';
    echo '<li><a href="?section=reports&type=pipeline">Sales Pipeline Report</a></li>';
    echo '<li><a href="?section=reports&type=customers">Customer Analysis</a></li>';
    echo '<li><a href="?section=reports&type=activities">Activity Report</a></li>';
    echo '</ul>';
    
    page_end();
}

/**
 * Settings section
 */
function fa_crm_settings($action) {
    page(_("CRM Settings"), true, false, "", "");
    
    echo '<h3>CRM Settings</h3>';
    echo '<p>Settings page coming soon</p>';
    
    page_end();
}

// Route the request
fa_crm_route($section, $action);
