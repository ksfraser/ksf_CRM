<?php
/**
 * CRM Web Forms API
 * 
 * Endpoint for lead creation from external forms
 * Similar to SuiteCRM web-to-lead forms
 * 
 * Usage:
 *   POST to this file with:
 *     - first_name, last_name, email, phone
 *     - account_name (creates debtor if not exists)
 *     - lead_source, lead_source_description
 *     - assigned_to, campaign_id
 * 
 * Returns JSON:
 *   - success: true/false
 *   - message: status message
 *   - lead_id: created lead ID (on success)
 */

$path_to_root = dirname(__FILE__);
include_once($path_to_root . "/../../includes/db.inc");
include_once($path_to_root . "/../../includes/session.inc");
include_once($path_to_root . "/includes/crm_db.inc");

header('Content-Type: application/json');

$response = array(
    'success' => false,
    'message' => '',
    'lead_id' => null,
);

try
{
    $module_id = 'FA_CRM';
    include($path_to_root . "/../../includes/prices.inc");
    include($path_to_root . "/../../includes/currencies.inc");
    include_once($path_to_root . "/../../includes/company.inc");

    // Get form data
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $account_name = isset($_POST['account_name']) ? $_POST['account_name'] : '';
    $lead_source = isset($_POST['lead_source']) ? $_POST['lead_source'] : 'web';
    $lead_source_description = isset($_POST['lead_source_description']) ? $_POST['lead_source_description'] : '';
    $assigned_to = isset($_POST['assigned_to']) ? $_POST['assigned_to'] : '';
    $campaign_id = isset($_POST['campaign_id']) ? $_POST['campaign_id'] : null;
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    $website = isset($_POST['website']) ? $_POST['website'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // Validation
    if (empty($first_name) && empty($last_name))
    {
        $response['message'] = 'Please provide at least a first name or last name';
        echo json_encode($response);
        exit;
    }

    if (empty($email) && empty($phone) && empty($account_name))
    {
        $response['message'] = 'Please provide at least an email, phone, or account name';
        echo json_encode($response);
        exit;
    }

    // Check if debtor/account exists, create if not
    $debtor_no = '';

    if (!empty($account_name))
    {
        // Check for existing debtor
        $sql = "SELECT debtor_no FROM " . TB_PREF . "debtors_master WHERE name = " . db_escape($account_name);
        $result = db_query($sql);
        if ($row = db_fetch_assoc($result))
        {
            $debtor_no = $row['debtor_no'];
        }
        else
        {
            // Create new debtor
            $sql = "INSERT INTO " . TB_PREF . "debtors_master 
                (name, inactive, curr_code, payment_terms, credit_limit, discount,	payment_days, 
                 email, address, phone, sales_type, tax_group_id, tax_included)
                VALUES (" . db_escape($account_name) . ", 1, '" . get_company_default('curr_default') . "', 
                '" . get_company_default('payment_terms') . "', '" . get_company_default('credit_limit') . "',
                0, '" . get_company_default('payment_days') . "', " . db_escape($email) . ", 
                " . db_escape($address) . ", " . db_escape($phone) . ", 
                '" . get_company_default('sales_type') . "', '" . get_company_default('tax_group_id') . "',
                '" . get_company_default('tax_included') . "')";
            db_query($sql, "Could not create debtor");
            $debtor_no = db_insert_id();
        }
    }
    else if (!empty($email))
    {
        // Try to find by email
        $sql = "SELECT debtor_no FROM " . TB_PREF . "debtors_master WHERE email = " . db_escape($email);
        $result = db_query($sql);
        if ($row = db_fetch_assoc($result))
        {
            $debtor_no = $row['debtor_no'];
        }
    }

    // If no debtor found, create one
    if (empty($debtor_no))
    {
        $display_name = trim($first_name . ' ' . $last_name);
        if (empty($display_name)) $display_name = 'Web Lead';

        $sql = "INSERT INTO " . TB_PREF . "debtors_master 
            (name, inactive, curr_code, payment_terms, credit_limit, discount, payment_days, 
             email, address, phone, sales_type, tax_group_id, tax_included)
            VALUES (" . db_escape($display_name) . ", 1, '" . get_company_default('curr_default') . "', 
            '" . get_company_default('payment_terms') . "', '" . get_company_default('credit_limit') . "',
            0, '" . get_company_default('payment_days') . "', " . db_escape($email) . ", 
            " . db_escape($address) . ", " . db_escape($phone) . ", 
            '" . get_company_default('sales_type') . "', '" . get_company_default('tax_group_id') . "',
            '" . get_company_default('tax_included') . "')";
        db_query($sql, "Could not create debtor");
        $debtor_no = db_insert_id();
    }

    // Create contact
    $contact_id = 0;
    if (!empty($first_name) || !empty($last_name))
    {
        $sql = "INSERT INTO " . TB_PREF . "fa_crm_contacts
            (debtor_no, first_name, last_name, email, phone, address, is_primary)
        VALUES (" . db_escape($debtor_no) . ", " . db_escape($first_name) . ", 
                " . db_escape($last_name) . ", " . db_escape($email) . ", 
                " . db_escape($phone) . ", " . db_escape($address) . ", 1)";
        db_query($sql, "Could not create contact");
        $contact_id = db_insert_id();
    }

    // Build lead notes
    $full_notes = $lead_source_description;
    if (!empty($notes))
    {
        $full_notes .= "\n" . $notes;
    }

    // Create lead
    $lead_data = array(
        'debtor_no' => $debtor_no,
        'lead_source' => $lead_source,
        'lead_status' => 'new',
        'rating' => '',
        'annual_revenue' => '',
        'employee_count' => '',
        'industry' => '',
        'website' => $website,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'assigned_to' => $assigned_to,
        'campaign_id' => $campaign_id,
        'notes' => $full_notes,
    );

    $lead_id = add_lead($lead_data);

    $response['success'] = true;
    $response['message'] = 'Lead created successfully';
    $response['lead_id'] = $lead_id;
    $response['debtor_no'] = $debtor_no;
    $response['contact_id'] = $contact_id;
}
catch (Exception $e)
{
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;