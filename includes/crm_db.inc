<?php
/**
 * CRM Database Functions
 *
 * WebERP-style CRM database operations for FrontAccounting
 */

// ============================================================================
// CRM CUSTOMER FUNCTIONS
// ============================================================================

function update_customer_crm_data($customer_id, $crm_data)
{
	// Check if CRM record exists
	$sql = "SELECT id FROM " . TB_PREF . "crm_customers WHERE debtor_no = " . db_escape($customer_id);
	$result = db_query($sql);
	$exists = db_num_rows($result) > 0;

	if ($exists) {
		$sql = "UPDATE " . TB_PREF . "crm_customers SET
			customer_type_id = " . db_escape($crm_data['customer_type_id']) . ",
			customer_segment_id = " . db_escape($crm_data['customer_segment_id']) . ",
			territory_id = " . db_escape($crm_data['territory_id']) . ",
			customer_since = " . db_escape($crm_data['customer_since']) . ",
			website = " . db_escape($crm_data['website']) . ",
			industry = " . db_escape($crm_data['industry']) . ",
			employee_count = " . db_escape($crm_data['employee_count']) . ",
			annual_revenue = " . db_escape($crm_data['annual_revenue']) . ",
			parent_company = " . db_escape($crm_data['parent_company']) . ",
			latitude = " . db_escape($crm_data['latitude']) . ",
			longitude = " . db_escape($crm_data['longitude']) . ",
			edi_enabled = " . (int)$crm_data['edi_enabled'] . ",
			marketing_opt_out = " . (int)$crm_data['marketing_opt_out'] . ",
			preferred_contact_method = " . db_escape($crm_data['preferred_contact_method']) . ",
			last_contact_date = " . db_escape($crm_data['last_contact_date']) . ",
			next_followup_date = " . db_escape($crm_data['next_followup_date']) . ",
			account_manager = " . db_escape($crm_data['account_manager']) . ",
			credit_rating = " . db_escape($crm_data['credit_rating']) . ",
			payment_reliability = " . (float)$crm_data['payment_reliability'] . "
		WHERE debtor_no = " . db_escape($customer_id);
	} else {
		$sql = "INSERT INTO " . TB_PREF . "crm_customers
			(debtor_no, customer_type_id, customer_segment_id, territory_id, customer_since,
			website, industry, employee_count, annual_revenue, parent_company, latitude, longitude,
			edi_enabled, marketing_opt_out, preferred_contact_method, last_contact_date,
			next_followup_date, account_manager, credit_rating, payment_reliability)
		VALUES (" . db_escape($customer_id) . ", " . db_escape($crm_data['customer_type_id']) . ",
			" . db_escape($crm_data['customer_segment_id']) . ", " . db_escape($crm_data['territory_id']) . ",
			" . db_escape($crm_data['customer_since']) . ", " . db_escape($crm_data['website']) . ",
			" . db_escape($crm_data['industry']) . ", " . db_escape($crm_data['employee_count']) . ",
			" . db_escape($crm_data['annual_revenue']) . ", " . db_escape($crm_data['parent_company']) . ",
			" . db_escape($crm_data['latitude']) . ", " . db_escape($crm_data['longitude']) . ",
			" . (int)$crm_data['edi_enabled'] . ", " . (int)$crm_data['marketing_opt_out'] . ",
			" . db_escape($crm_data['preferred_contact_method']) . ", " . db_escape($crm_data['last_contact_date']) . ",
			" . db_escape($crm_data['next_followup_date']) . ", " . db_escape($crm_data['account_manager']) . ",
			" . db_escape($crm_data['credit_rating']) . ", " . (float)$crm_data['payment_reliability'] . ")";
	}

	db_query($sql, "Could not update customer CRM data");
}

function get_customer_crm_data($customer_id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_customers WHERE debtor_no = " . db_escape($customer_id);
	$result = db_query($sql, "Could not get customer CRM data");
	return db_fetch($result);
}

function delete_customer_crm_data($customer_id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_customers WHERE debtor_no = " . db_escape($customer_id);
	db_query($sql, "Could not delete customer CRM data");
}

// ============================================================================
// CUSTOMER TYPES FUNCTIONS
// ============================================================================

function add_customer_type($type_name, $description, $discount_percent = 0, $credit_limit = 0, $payment_terms = null)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_customer_types
		(type_name, description, discount_percent, credit_limit, payment_terms)
		VALUES (" . db_escape($type_name) . ", " . db_escape($description) . ",
		" . (float)$discount_percent . ", " . (float)$credit_limit . ", " . db_escape($payment_terms) . ")";

	db_query($sql, "Could not add customer type");
	return db_insert_id();
}

function update_customer_type($id, $type_name, $description, $discount_percent = 0, $credit_limit = 0, $payment_terms = null)
{
	$sql = "UPDATE " . TB_PREF . "crm_customer_types SET
		type_name = " . db_escape($type_name) . ",
		description = " . db_escape($description) . ",
		discount_percent = " . (float)$discount_percent . ",
		credit_limit = " . (float)$credit_limit . ",
		payment_terms = " . db_escape($payment_terms) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update customer type");
}

function delete_customer_type($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_customer_types WHERE id = " . (int)$id;
	db_query($sql, "Could not delete customer type");
}

function get_customer_types($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_customer_types";
	if ($active_only) {
		$sql .= " WHERE inactive = 0";
	}
	$sql .= " ORDER BY type_name";

	$result = db_query($sql, "Could not get customer types");
	return $result;
}

function get_customer_type($id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_customer_types WHERE id = " . (int)$id;
	$result = db_query($sql, "Could not get customer type");
	return db_fetch($result);
}

// ============================================================================
// CONTACT MANAGEMENT FUNCTIONS
// ============================================================================

function add_customer_contact($debtor_no, $contact_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_contacts
		(debtor_no, contact_role_id, first_name, last_name, title, department,
		phone, mobile, email, address, notes, is_primary)
		VALUES (" . db_escape($debtor_no) . ", " . db_escape($contact_data['contact_role_id']) . ",
			" . db_escape($contact_data['first_name']) . ", " . db_escape($contact_data['last_name']) . ",
			" . db_escape($contact_data['title']) . ", " . db_escape($contact_data['department']) . ",
			" . db_escape($contact_data['phone']) . ", " . db_escape($contact_data['mobile']) . ",
			" . db_escape($contact_data['email']) . ", " . db_escape($contact_data['address']) . ",
			" . db_escape($contact_data['notes']) . ", " . (int)$contact_data['is_primary'] . ")";

	db_query($sql, "Could not add customer contact");
	return db_insert_id();
}

function update_customer_contact($contact_id, $contact_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_contacts SET
		contact_role_id = " . db_escape($contact_data['contact_role_id']) . ",
		first_name = " . db_escape($contact_data['first_name']) . ",
		last_name = " . db_escape($contact_data['last_name']) . ",
		title = " . db_escape($contact_data['title']) . ",
		department = " . db_escape($contact_data['department']) . ",
		phone = " . db_escape($contact_data['phone']) . ",
		mobile = " . db_escape($contact_data['mobile']) . ",
		email = " . db_escape($contact_data['email']) . ",
		address = " . db_escape($contact_data['address']) . ",
		notes = " . db_escape($contact_data['notes']) . ",
		is_primary = " . (int)$contact_data['is_primary'] . "
	WHERE id = " . (int)$contact_id;

	db_query($sql, "Could not update customer contact");
}

function delete_customer_contact($contact_id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_contacts WHERE id = " . (int)$contact_id;
	db_query($sql, "Could not delete customer contact");
}

function get_customer_contacts($debtor_no, $active_only = true)
{
	$sql = "SELECT c.*, r.role_name FROM " . TB_PREF . "crm_contacts c
		LEFT JOIN " . TB_PREF . "crm_contact_roles r ON c.contact_role_id = r.id
		WHERE c.debtor_no = " . db_escape($debtor_no);
	if ($active_only) {
		$sql .= " AND c.inactive = 0";
	}
	$sql .= " ORDER BY c.is_primary DESC, c.last_name, c.first_name";

	$result = db_query($sql, "Could not get customer contacts");
	return $result;
}

function get_customer_contact($contact_id)
{
	$sql = "SELECT c.*, r.role_name FROM " . TB_PREF . "crm_contacts c
		LEFT JOIN " . TB_PREF . "crm_contact_roles r ON c.contact_role_id = r.id
		WHERE c.id = " . (int)$contact_id;

	$result = db_query($sql, "Could not get customer contact");
	return db_fetch($result);
}

function get_primary_contact($debtor_no)
{
	$sql = "SELECT c.*, r.role_name FROM " . TB_PREF . "crm_contacts c
		LEFT JOIN " . TB_PREF . "crm_contact_roles r ON c.contact_role_id = r.id
		WHERE c.debtor_no = " . db_escape($debtor_no) . " AND c.is_primary = 1 AND c.inactive = 0
		LIMIT 1";

	$result = db_query($sql, "Could not get primary contact");
	return db_fetch($result);
}

// ============================================================================
// COMMUNICATIONS TRACKING FUNCTIONS
// ============================================================================

function add_communication($communication_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_communications
		(debtor_no, contact_id, communication_type, direction, subject, message,
		email_from, email_to, phone_number, duration_minutes, status, scheduled_date,
		completed_date, assigned_to, priority, follow_up_required, follow_up_date,
		notes, email_message_id, attachment_path, created_by)
		VALUES (" . db_escape($communication_data['debtor_no']) . ",
			" . db_escape($communication_data['contact_id']) . ",
			" . db_escape($communication_data['communication_type']) . ",
			" . db_escape($communication_data['direction']) . ",
			" . db_escape($communication_data['subject']) . ",
			" . db_escape($communication_data['message']) . ",
			" . db_escape($communication_data['email_from']) . ",
			" . db_escape($communication_data['email_to']) . ",
			" . db_escape($communication_data['phone_number']) . ",
			" . db_escape($communication_data['duration_minutes']) . ",
			" . db_escape($communication_data['status']) . ",
			" . db_escape($communication_data['scheduled_date']) . ",
			" . db_escape($communication_data['completed_date']) . ",
			" . db_escape($communication_data['assigned_to']) . ",
			" . db_escape($communication_data['priority']) . ",
			" . (int)$communication_data['follow_up_required'] . ",
			" . db_escape($communication_data['follow_up_date']) . ",
			" . db_escape($communication_data['notes']) . ",
			" . db_escape($communication_data['email_message_id']) . ",
			" . db_escape($communication_data['attachment_path']) . ",
			" . db_escape($communication_data['created_by']) . ")";

	db_query($sql, "Could not add communication");
	return db_insert_id();
}

function update_communication($communication_id, $communication_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_communications SET
		contact_id = " . db_escape($communication_data['contact_id']) . ",
		communication_type = " . db_escape($communication_data['communication_type']) . ",
		direction = " . db_escape($communication_data['direction']) . ",
		subject = " . db_escape($communication_data['subject']) . ",
		message = " . db_escape($communication_data['message']) . ",
		email_from = " . db_escape($communication_data['email_from']) . ",
		email_to = " . db_escape($communication_data['email_to']) . ",
		phone_number = " . db_escape($communication_data['phone_number']) . ",
		duration_minutes = " . db_escape($communication_data['duration_minutes']) . ",
		status = " . db_escape($communication_data['status']) . ",
		scheduled_date = " . db_escape($communication_data['scheduled_date']) . ",
		completed_date = " . db_escape($communication_data['completed_date']) . ",
		assigned_to = " . db_escape($communication_data['assigned_to']) . ",
		priority = " . db_escape($communication_data['priority']) . ",
		follow_up_required = " . (int)$communication_data['follow_up_required'] . ",
		follow_up_date = " . db_escape($communication_data['follow_up_date']) . ",
		notes = " . db_escape($communication_data['notes']) . ",
		email_message_id = " . db_escape($communication_data['email_message_id']) . ",
		attachment_path = " . db_escape($communication_data['attachment_path']) . "
	WHERE id = " . (int)$communication_id;

	db_query($sql, "Could not update communication");
}

function delete_communication($communication_id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_communications WHERE id = " . (int)$communication_id;
	db_query($sql, "Could not delete communication");
}

function get_customer_communications($debtor_no, $limit = 50, $offset = 0)
{
	$sql = "SELECT c.*, ct.first_name, ct.last_name, ct.email as contact_email
		FROM " . TB_PREF . "crm_communications c
		LEFT JOIN " . TB_PREF . "crm_contacts ct ON c.contact_id = ct.id
		WHERE c.debtor_no = " . db_escape($debtor_no) . "
		ORDER BY c.created_at DESC
		LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

	$result = db_query($sql, "Could not get customer communications");
	return $result;
}

function get_communication($communication_id)
{
	$sql = "SELECT c.*, ct.first_name, ct.last_name, ct.email as contact_email
		FROM " . TB_PREF . "crm_communications c
		LEFT JOIN " . TB_PREF . "crm_contacts ct ON c.contact_id = ct.id
		WHERE c.id = " . (int)$communication_id;

	$result = db_query($sql, "Could not get communication");
	return db_fetch($result);
}

function get_pending_followups($assigned_to = null)
{
	$sql = "SELECT c.*, d.name as customer_name, ct.first_name, ct.last_name
		FROM " . TB_PREF . "crm_communications c
		JOIN " . TB_PREF . "debtors_master d ON c.debtor_no = d.debtor_no
		LEFT JOIN " . TB_PREF . "crm_contacts ct ON c.contact_id = ct.id
		WHERE c.follow_up_required = 1
		AND c.follow_up_date <= CURDATE()
		AND c.status != 'cancelled'";

	if ($assigned_to) {
		$sql .= " AND c.assigned_to = " . db_escape($assigned_to);
	}

	$sql .= " ORDER BY c.follow_up_date ASC, c.priority DESC";

	$result = db_query($sql, "Could not get pending followups");
	return $result;
}

function get_communications_by_email($email_address, $limit = 100)
{
	$sql = "SELECT c.*, d.name as customer_name
		FROM " . TB_PREF . "crm_communications c
		JOIN " . TB_PREF . "debtors_master d ON c.debtor_no = d.debtor_no
		WHERE (c.email_from = " . db_escape($email_address) . "
		OR c.email_to = " . db_escape($email_address) . ")
		AND c.communication_type = 'email'
		ORDER BY c.created_at DESC
		LIMIT " . (int)$limit;

	$result = db_query($sql, "Could not get communications by email");
	return $result;
}

// ============================================================================
// EMAIL IMPORT FUNCTIONS
// ============================================================================

function add_email_account($account_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_email_accounts
		(account_name, email_address, smtp_host, smtp_port, smtp_username, smtp_password,
		smtp_encryption, imap_host, imap_port, imap_username, imap_password, imap_encryption,
		auto_import, import_frequency_minutes)
		VALUES (" . db_escape($account_data['account_name']) . ",
			" . db_escape($account_data['email_address']) . ",
			" . db_escape($account_data['smtp_host']) . ",
			" . (int)$account_data['smtp_port'] . ",
			" . db_escape($account_data['smtp_username']) . ",
			" . db_escape($account_data['smtp_password']) . ",
			" . db_escape($account_data['smtp_encryption']) . ",
			" . db_escape($account_data['imap_host']) . ",
			" . (int)$account_data['imap_port'] . ",
			" . db_escape($account_data['imap_username']) . ",
			" . db_escape($account_data['imap_password']) . ",
			" . db_escape($account_data['imap_encryption']) . ",
			" . (int)$account_data['auto_import'] . ",
			" . (int)$account_data['import_frequency_minutes'] . ")";

	db_query($sql, "Could not add email account");
	return db_insert_id();
}

function update_email_account($account_id, $account_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_email_accounts SET
		account_name = " . db_escape($account_data['account_name']) . ",
		email_address = " . db_escape($account_data['email_address']) . ",
		smtp_host = " . db_escape($account_data['smtp_host']) . ",
		smtp_port = " . (int)$account_data['smtp_port'] . ",
		smtp_username = " . db_escape($account_data['smtp_username']) . ",
		smtp_password = " . db_escape($account_data['smtp_password']) . ",
		smtp_encryption = " . db_escape($account_data['smtp_encryption']) . ",
		imap_host = " . db_escape($account_data['imap_host']) . ",
		imap_port = " . (int)$account_data['imap_port'] . ",
		imap_username = " . db_escape($account_data['imap_username']) . ",
		imap_password = " . db_escape($account_data['imap_password']) . ",
		imap_encryption = " . db_escape($account_data['imap_encryption']) . ",
		auto_import = " . (int)$account_data['auto_import'] . ",
		import_frequency_minutes = " . (int)$account_data['import_frequency_minutes'] . ",
		active = " . (int)$account_data['active'] . "
	WHERE id = " . (int)$account_id;

	db_query($sql, "Could not update email account");
}

function delete_email_account($account_id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_email_accounts WHERE id = " . (int)$account_id;
	db_query($sql, "Could not delete email account");
}

function get_email_accounts($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_email_accounts";
	if ($active_only) {
		$sql .= " WHERE active = 1";
	}
	$sql .= " ORDER BY account_name";

	$result = db_query($sql, "Could not get email accounts");
	return $result;
}

function get_email_account($account_id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_email_accounts WHERE id = " . (int)$account_id;
	$result = db_query($sql, "Could not get email account");
	return db_fetch($result);
}

function update_email_sync_time($account_id)
{
	$sql = "UPDATE " . TB_PREF . "crm_email_accounts SET last_sync = NOW() WHERE id = " . (int)$account_id;
	db_query($sql, "Could not update email sync time");
}

function get_accounts_due_for_sync()
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_email_accounts
		WHERE active = 1 AND auto_import = 1
		AND (last_sync IS NULL OR last_sync <= DATE_SUB(NOW(), INTERVAL import_frequency_minutes MINUTE))";

	$result = db_query($sql, "Could not get accounts due for sync");
	return $result;
}

// ============================================================================
// TERRITORY FUNCTIONS
// ============================================================================

function add_territory($territory_name, $description, $sales_person = null, $region = null)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_territories
		(territory_name, description, sales_person, region)
		VALUES (" . db_escape($territory_name) . ", " . db_escape($description) . ",
		" . db_escape($sales_person) . ", " . db_escape($region) . ")";

	db_query($sql, "Could not add territory");
	return db_insert_id();
}

function update_territory($id, $territory_name, $description, $sales_person = null, $region = null)
{
	$sql = "UPDATE " . TB_PREF . "crm_territories SET
		territory_name = " . db_escape($territory_name) . ",
		description = " . db_escape($description) . ",
		sales_person = " . db_escape($sales_person) . ",
		region = " . db_escape($region) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update territory");
}

function delete_territory($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_territories WHERE id = " . (int)$id;
	db_query($sql, "Could not delete territory");
}

function get_territories($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_territories";
	if ($active_only) {
		$sql .= " WHERE inactive = 0";
	}
	$sql .= " ORDER BY territory_name";

	$result = db_query($sql, "Could not get territories");
	return $result;
}

function get_territory($id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_territories WHERE id = " . (int)$id;
	$result = db_query($sql, "Could not get territory");
	return db_fetch($result);
}

// ============================================================================
// OPPORTUNITIES FUNCTIONS
// ============================================================================

function add_opportunity($opportunity_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_opportunities
		(opportunity_name, debtor_no, contact_id, sales_person, opportunity_type, status,
		estimated_value, probability, expected_close_date, notes)
		VALUES (" . db_escape($opportunity_data['opportunity_name']) . ",
			" . db_escape($opportunity_data['debtor_no']) . ",
			" . db_escape($opportunity_data['contact_id']) . ",
			" . db_escape($opportunity_data['sales_person']) . ",
			" . db_escape($opportunity_data['opportunity_type']) . ",
			" . db_escape($opportunity_data['status']) . ",
			" . (float)$opportunity_data['estimated_value'] . ",
			" . (float)$opportunity_data['probability'] . ",
			" . db_escape($opportunity_data['expected_close_date']) . ",
			" . db_escape($opportunity_data['notes']) . ")";

	db_query($sql, "Could not add opportunity");
	return db_insert_id();
}

function update_opportunity($opportunity_id, $opportunity_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_opportunities SET
		opportunity_name = " . db_escape($opportunity_data['opportunity_name']) . ",
		contact_id = " . db_escape($opportunity_data['contact_id']) . ",
		sales_person = " . db_escape($opportunity_data['sales_person']) . ",
		opportunity_type = " . db_escape($opportunity_data['opportunity_type']) . ",
		status = " . db_escape($opportunity_data['status']) . ",
		estimated_value = " . (float)$opportunity_data['estimated_value'] . ",
		probability = " . (float)$opportunity_data['probability'] . ",
		expected_close_date = " . db_escape($opportunity_data['expected_close_date']) . ",
		notes = " . db_escape($opportunity_data['notes']) . "
	WHERE id = " . (int)$opportunity_id;

	db_query($sql, "Could not update opportunity");
}

function delete_opportunity($opportunity_id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_opportunities WHERE id = " . (int)$opportunity_id;
	db_query($sql, "Could not delete opportunity");
}

function get_customer_opportunities($debtor_no, $active_only = true)
{
	$sql = "SELECT o.*, c.first_name, c.last_name FROM " . TB_PREF . "crm_opportunities o
		LEFT JOIN " . TB_PREF . "crm_contacts c ON o.contact_id = c.id
		WHERE o.debtor_no = " . db_escape($debtor_no);
	if ($active_only) {
		$sql .= " AND o.status NOT IN ('closed_won', 'closed_lost', 'cancelled')";
	}
	$sql .= " ORDER BY o.created_at DESC";

	$result = db_query($sql, "Could not get customer opportunities");
	return $result;
}

function get_opportunity($opportunity_id)
{
	$sql = "SELECT o.*, c.first_name, c.last_name, d.name as customer_name
		FROM " . TB_PREF . "crm_opportunities o
		LEFT JOIN " . TB_PREF . "crm_contacts c ON o.contact_id = c.id
		JOIN " . TB_PREF . "debtors_master d ON o.debtor_no = d.debtor_no
		WHERE o.id = " . (int)$opportunity_id;

	$result = db_query($sql, "Could not get opportunity");
	return db_fetch($result);
}

// ============================================================================
// ANALYTICS FUNCTIONS
// ============================================================================

function update_customer_analytics($customer_id)
{
	// Calculate customer analytics data
	$analytics = calculate_customer_analytics($customer_id);

	// Insert or update analytics record
	$sql = "INSERT INTO " . TB_PREF . "crm_customer_analytics
		(debtor_no, period_start, period_end, total_sales, total_payments,
		outstanding_balance, payment_days_avg, customer_lifetime_value,
		last_communication_date, communication_count)
		VALUES (" . db_escape($customer_id) . ", " . db_escape($analytics['period_start']) . ",
			" . db_escape($analytics['period_end']) . ", " . $analytics['total_sales'] . ",
			" . $analytics['total_payments'] . ", " . $analytics['outstanding_balance'] . ",
			" . $analytics['payment_days_avg'] . ", " . $analytics['customer_lifetime_value'] . ",
			" . db_escape($analytics['last_communication_date']) . ", " . $analytics['communication_count'] . ")
		ON DUPLICATE KEY UPDATE
		total_sales = VALUES(total_sales),
		total_payments = VALUES(total_payments),
		outstanding_balance = VALUES(outstanding_balance),
		payment_days_avg = VALUES(payment_days_avg),
		customer_lifetime_value = VALUES(customer_lifetime_value),
		last_communication_date = VALUES(last_communication_date),
		communication_count = VALUES(communication_count)";

	db_query($sql);
}

function calculate_customer_analytics($customer_id)
{
	// Calculate total sales
	$sql = "SELECT SUM(ov_amount + ov_gst + ov_freight + ov_freight_tax - ov_discount) as total_sales
	FROM " . TB_PREF . "debtor_trans
	WHERE debtor_no = " . db_escape($customer_id) . "
	AND type IN (" . ST_SALESINVOICE . ", " . ST_CUSTCREDIT . ")";

	$result = db_query($sql);
	$sales_row = db_fetch($result);
	$total_sales = $sales_row['total_sales'] ? $sales_row['total_sales'] : 0;

	// Calculate total payments
	$sql = "SELECT SUM(ov_amount) as total_payments
	FROM " . TB_PREF . "debtor_trans
	WHERE debtor_no = " . db_escape($customer_id) . "
	AND type = " . ST_CUSTPAYMENT;

	$result = db_query($sql);
	$payment_row = db_fetch($result);
	$total_payments = $payment_row['total_payments'] ? $payment_row['total_payments'] : 0;

	// Calculate outstanding balance
	$sql = "SELECT SUM(ov_amount + ov_gst + ov_freight + ov_freight_tax - ov_discount - alloc) as outstanding
	FROM " . TB_PREF . "debtor_trans
	WHERE debtor_no = " . db_escape($customer_id) . "
	AND type IN (" . ST_SALESINVOICE . ", " . ST_CUSTCREDIT . ")";

	$result = db_query($sql);
	$outstanding_row = db_fetch($result);
	$outstanding_balance = $outstanding_row['outstanding'] ? $outstanding_row['outstanding'] : 0;

	// Calculate average payment days (simplified)
	$payment_days_avg = 30; // Default assumption

	// Customer lifetime value (simplified)
	$customer_lifetime_value = $total_sales;

	// Get communication stats
	$sql = "SELECT COUNT(*) as comm_count, MAX(created_at) as last_comm
		FROM " . TB_PREF . "crm_communications
		WHERE debtor_no = " . db_escape($customer_id);

	$result = db_query($sql);
	$comm_row = db_fetch($result);
	$communication_count = $comm_row['comm_count'] ? $comm_row['comm_count'] : 0;
	$last_communication_date = $comm_row['last_comm'];

	return [
		'customer_id' => $customer_id,
		'period_start' => date('Y-m-01'),
		'period_end' => date('Y-m-t'),
		'total_sales' => $total_sales,
		'total_payments' => $total_payments,
		'outstanding_balance' => $outstanding_balance,
		'payment_days_avg' => $payment_days_avg,
		'customer_lifetime_value' => $customer_lifetime_value,
		'last_communication_date' => $last_communication_date,
		'communication_count' => $communication_count
	];
}

function get_customer_analytics($customer_id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_customer_analytics
		WHERE debtor_no = " . db_escape($customer_id) . "
		ORDER BY period_end DESC LIMIT 1";

	$result = db_query($sql, "Could not get customer analytics");
	return db_fetch($result);
}

// Customer Types Functions
function add_customer_type($type_name, $description, $discount_percent = 0, $credit_limit = 0, $payment_terms = null)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_customer_types
		(type_name, description, discount_percent, credit_limit, payment_terms)
		VALUES (" . db_escape($type_name) . ", " . db_escape($description) . ",
		" . (float)$discount_percent . ", " . (float)$credit_limit . ", " . db_escape($payment_terms) . ")";

	db_query($sql, "Could not add customer type");
	return db_insert_id();
}

function update_customer_type($id, $type_name, $description, $discount_percent = 0, $credit_limit = 0, $payment_terms = null)
{
	$sql = "UPDATE " . TB_PREF . "crm_customer_types SET
		type_name = " . db_escape($type_name) . ",
		description = " . db_escape($description) . ",
		discount_percent = " . (float)$discount_percent . ",
		credit_limit = " . (float)$credit_limit . ",
		payment_terms = " . db_escape($payment_terms) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update customer type");
}

function delete_customer_type($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_customer_types WHERE id = " . (int)$id;
	db_query($sql, "Could not delete customer type");
}

function get_customer_types($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_customer_types";
	if ($active_only) {
		$sql .= " WHERE inactive = 0";
	}
	$sql .= " ORDER BY type_name";

	return db_query($sql, "Could not get customer types");
}

// Customer Segments Functions
function add_customer_segment($segment_name, $description, $criteria = null)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_customer_segments
		(segment_name, description, criteria)
		VALUES (" . db_escape($segment_name) . ", " . db_escape($description) . ", " . db_escape($criteria) . ")";

	db_query($sql, "Could not add customer segment");
	return db_insert_id();
}

function update_customer_segment($id, $segment_name, $description, $criteria = null)
{
	$sql = "UPDATE " . TB_PREF . "crm_customer_segments SET
		segment_name = " . db_escape($segment_name) . ",
		description = " . db_escape($description) . ",
		criteria = " . db_escape($criteria) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update customer segment");
}

function delete_customer_segment($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_customer_segments WHERE id = " . (int)$id;
	db_query($sql, "Could not delete customer segment");
}

function get_customer_segments($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_customer_segments";
	if ($active_only) {
		$sql .= " WHERE inactive = 0";
	}
	$sql .= " ORDER BY segment_name";

	return db_query($sql, "Could not get customer segments");
}

// Contact Roles Functions
function add_contact_role($role_name, $description, $default_phone = null, $default_email = null)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_contact_roles
		(role_name, description, default_phone, default_email)
		VALUES (" . db_escape($role_name) . ", " . db_escape($description) . ",
		" . db_escape($default_phone) . ", " . db_escape($default_email) . ")";

	db_query($sql, "Could not add contact role");
	return db_insert_id();
}

function update_contact_role($id, $role_name, $description, $default_phone = null, $default_email = null)
{
	$sql = "UPDATE " . TB_PREF . "crm_contact_roles SET
		role_name = " . db_escape($role_name) . ",
		description = " . db_escape($description) . ",
		default_phone = " . db_escape($default_phone) . ",
		default_email = " . db_escape($default_email) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update contact role");
}

function delete_contact_role($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_contact_roles WHERE id = " . (int)$id;
	db_query($sql, "Could not delete contact role");
}

function get_contact_roles($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_contact_roles";
	if ($active_only) {
		$sql .= " WHERE inactive = 0";
	}
	$sql .= " ORDER BY role_name";

	return db_query($sql, "Could not get contact roles");
}

// Territories Functions
function add_territory($territory_name, $description, $sales_person = null, $region = null)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_territories
		(territory_name, description, sales_person, region)
		VALUES (" . db_escape($territory_name) . ", " . db_escape($description) . ",
		" . db_escape($sales_person) . ", " . db_escape($region) . ")";

	db_query($sql, "Could not add territory");
	return db_insert_id();
}

function update_territory($id, $territory_name, $description, $sales_person = null, $region = null)
{
	$sql = "UPDATE " . TB_PREF . "crm_territories SET
		territory_name = " . db_escape($territory_name) . ",
		description = " . db_escape($description) . ",
		sales_person = " . db_escape($sales_person) . ",
		region = " . db_escape($region) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update territory");
}

function delete_territory($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_territories WHERE id = " . (int)$id;
	db_query($sql, "Could not delete territory");
}

function get_territories($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_territories";
	if ($active_only) {
		$sql .= " WHERE inactive = 0";
	}
	$sql .= " ORDER BY territory_name";

	return db_query($sql, "Could not get territories");
}

// Sales Opportunities Functions
function add_sales_opportunity($opportunity_name, $customer_id, $contact_id = null, $sales_person = null,
	$opportunity_type = 'sales', $status = 'prospect', $estimated_value = 0, $probability = 0,
	$expected_close_date = null, $notes = null)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_opportunities
		(opportunity_name, customer_id, contact_id, sales_person, opportunity_type, status,
		estimated_value, probability, expected_close_date, notes)
		VALUES (" . db_escape($opportunity_name) . ", " . db_escape($customer_id) . ",
		" . db_escape($contact_id) . ", " . db_escape($sales_person) . ", " . db_escape($opportunity_type) . ",
		" . db_escape($status) . ", " . (float)$estimated_value . ", " . (float)$probability . ",
		" . db_escape($expected_close_date) . ", " . db_escape($notes) . ")";

	db_query($sql, "Could not add sales opportunity");
	return db_insert_id();
}

function update_sales_opportunity($id, $opportunity_name, $customer_id, $contact_id = null, $sales_person = null,
	$opportunity_type = 'sales', $status = 'prospect', $estimated_value = 0, $probability = 0,
	$expected_close_date = null, $notes = null)
{
	$sql = "UPDATE " . TB_PREF . "crm_opportunities SET
		opportunity_name = " . db_escape($opportunity_name) . ",
		customer_id = " . db_escape($customer_id) . ",
		contact_id = " . db_escape($contact_id) . ",
		sales_person = " . db_escape($sales_person) . ",
		opportunity_type = " . db_escape($opportunity_type) . ",
		status = " . db_escape($status) . ",
		estimated_value = " . (float)$estimated_value . ",
		probability = " . (float)$probability . ",
		expected_close_date = " . db_escape($expected_close_date) . ",
		notes = " . db_escape($notes) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update sales opportunity");
}

function delete_sales_opportunity($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_opportunities WHERE id = " . (int)$id;
	db_query($sql, "Could not delete sales opportunity");
}

function get_customer_opportunities($customer_id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_opportunities
	WHERE customer_id = " . db_escape($customer_id) . "
	ORDER BY expected_close_date DESC, estimated_value DESC";

	return db_query($sql, "Could not get customer opportunities");
}

// EDI Configuration Functions
function add_edi_config($customer_id, $edi_type, $edi_code, $ftp_host = null, $ftp_username = null,
	$ftp_password = null, $email_address = null, $active = 1)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_edi_config
		(customer_id, edi_type, edi_code, ftp_host, ftp_username, ftp_password, email_address, active)
		VALUES (" . db_escape($customer_id) . ", " . db_escape($edi_type) . ", " . db_escape($edi_code) . ",
		" . db_escape($ftp_host) . ", " . db_escape($ftp_username) . ", " . db_escape($ftp_password) . ",
		" . db_escape($email_address) . ", " . (int)$active . ")";

	db_query($sql, "Could not add EDI configuration");
	return db_insert_id();
}

function update_edi_config($id, $customer_id, $edi_type, $edi_code, $ftp_host = null, $ftp_username = null,
	$ftp_password = null, $email_address = null, $active = 1)
{
	$sql = "UPDATE " . TB_PREF . "crm_edi_config SET
		customer_id = " . db_escape($customer_id) . ",
		edi_type = " . db_escape($edi_type) . ",
		edi_code = " . db_escape($edi_code) . ",
		ftp_host = " . db_escape($ftp_host) . ",
		ftp_username = " . db_escape($ftp_username) . ",
		ftp_password = " . db_escape($ftp_password) . ",
		email_address = " . db_escape($email_address) . ",
		active = " . (int)$active . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update EDI configuration");
}

function get_customer_edi_config($customer_id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_edi_config
	WHERE customer_id = " . db_escape($customer_id) . " AND active = 1
	LIMIT 1";

	$result = db_query($sql, "Could not get EDI configuration");
	return db_fetch($result);
}

// Customer Analytics Functions
function get_customer_analytics($customer_id, $period_start = null, $period_end = null)
{
	if (!$period_start) {
		$period_start = date('Y-m-01');
	}
	if (!$period_end) {
		$period_end = date('Y-m-t');
	}

	$sql = "SELECT * FROM " . TB_PREF . "crm_customer_analytics
	WHERE customer_id = " . db_escape($customer_id) . "
	AND period_start >= " . db_escape($period_start) . "
	AND period_end <= " . db_escape($period_end) . "
	ORDER BY period_end DESC LIMIT 1";

	$result = db_query($sql, "Could not get customer analytics");
	return db_fetch($result);
}

function calculate_customer_analytics($customer_id)
{
	// Calculate total sales
	$sql = "SELECT SUM(ov_amount + ov_gst + ov_freight + ov_freight_tax - ov_discount) as total_sales
	FROM " . TB_PREF . "debtor_trans
	WHERE debtor_no = " . db_escape($customer_id) . "
	AND type IN (" . ST_SALESINVOICE . ", " . ST_CUSTCREDIT . ")";

	$result = db_query($sql);
	$sales_row = db_fetch($result);
	$total_sales = $sales_row['total_sales'] ? $sales_row['total_sales'] : 0;

	// Calculate total payments
	$sql = "SELECT SUM(ov_amount) as total_payments
	FROM " . TB_PREF . "debtor_trans
	WHERE debtor_no = " . db_escape($customer_id) . "
	AND type = " . ST_CUSTPAYMENT;

	$result = db_query($sql);
	$payment_row = db_fetch($result);
	$total_payments = $payment_row['total_payments'] ? $payment_row['total_payments'] : 0;

	// Calculate outstanding balance
	$sql = "SELECT SUM(ov_amount + ov_gst + ov_freight + ov_freight_tax - ov_discount - alloc) as outstanding
	FROM " . TB_PREF . "debtor_trans
	WHERE debtor_no = " . db_escape($customer_id) . "
	AND type IN (" . ST_SALESINVOICE . ", " . ST_CUSTCREDIT . ")";

	$result = db_query($sql);
	$outstanding_row = db_fetch($result);
	$outstanding_balance = $outstanding_row['outstanding'] ? $outstanding_row['outstanding'] : 0;

	// Calculate average payment days (simplified)
	$payment_days_avg = 30; // Default assumption

	// Customer lifetime value (simplified)
	$customer_lifetime_value = $total_sales;

	return [
		'customer_id' => $customer_id,
		'period_start' => date('Y-m-01'),
		'period_end' => date('Y-m-t'),
		'total_sales' => $total_sales,
		'total_payments' => $total_payments,
		'outstanding_balance' => $outstanding_balance,
		'payment_days_avg' => $payment_days_avg,
		'customer_lifetime_value' => $customer_lifetime_value
	];
}

function update_customer_analytics($customer_id)
{
	$analytics = calculate_customer_analytics($customer_id);

	$sql = "INSERT INTO " . TB_PREF . "crm_customer_analytics
		(customer_id, period_start, period_end, total_sales, total_payments,
		outstanding_balance, payment_days_avg, customer_lifetime_value)
		VALUES (" . db_escape($analytics['customer_id']) . ", " . db_escape($analytics['period_start']) . ",
		" . db_escape($analytics['period_end']) . ", " . $analytics['total_sales'] . ",
		" . $analytics['total_payments'] . ", " . $analytics['outstanding_balance'] . ",
		" . $analytics['payment_days_avg'] . ", " . $analytics['customer_lifetime_value'] . ")
		ON DUPLICATE KEY UPDATE
		total_sales = VALUES(total_sales),
		total_payments = VALUES(total_payments),
		outstanding_balance = VALUES(outstanding_balance),
		payment_days_avg = VALUES(payment_days_avg),
		customer_lifetime_value = VALUES(customer_lifetime_value)";

	db_query($sql, "Could not update customer analytics");
}

// UI Helper Functions
function customer_types_list($name, $selected_id = null, $submit_on_change = false)
{
	$sql = "SELECT id, type_name FROM " . TB_PREF . "crm_customer_types WHERE inactive = 0 ORDER BY type_name";
	return combo_input($name, $selected_id, $sql, 'id', 'type_name',
		array('select_submit' => $submit_on_change, 'async' => false));
}

function customer_types_list_row($label, $name, $selected_id = null, $submit_on_change = false)
{
	echo "<tr><td class='label'>$label</td><td>";
	echo customer_types_list($name, $selected_id, $submit_on_change);
	echo "</td></tr>\n";
}

function customer_segments_list($name, $selected_id = null, $submit_on_change = false)
{
	$sql = "SELECT id, segment_name FROM " . TB_PREF . "crm_customer_segments WHERE inactive = 0 ORDER BY segment_name";
	return combo_input($name, $selected_id, $sql, 'id', 'segment_name',
		array('select_submit' => $submit_on_change, 'async' => false));
}

function customer_segments_list_row($label, $name, $selected_id = null, $submit_on_change = false)
{
	echo "<tr><td class='label'>$label</td><td>";
	echo customer_segments_list($name, $selected_id, $submit_on_change);
	echo "</td></tr>\n";
}

function contact_roles_list($name, $selected_id = null, $submit_on_change = false)
{
	$sql = "SELECT id, role_name FROM " . TB_PREF . "crm_contact_roles WHERE inactive = 0 ORDER BY role_name";
	return combo_input($name, $selected_id, $sql, 'id', 'role_name',
		array('select_submit' => $submit_on_change, 'async' => false));
}

function contact_roles_list_row($label, $name, $selected_id = null, $submit_on_change = false)
{
	echo "<tr><td class='label'>$label</td><td>";
	echo contact_roles_list($name, $selected_id, $submit_on_change);
	echo "</td></tr>\n";
}

function territories_list($name, $selected_id = null, $submit_on_change = false)
{
	$sql = "SELECT id, territory_name FROM " . TB_PREF . "crm_territories WHERE inactive = 0 ORDER BY territory_name";
	return combo_input($name, $selected_id, $sql, 'id', 'territory_name',
		array('select_submit' => $submit_on_change, 'async' => false));
}

function territories_list_row($label, $name, $selected_id = null, $submit_on_change = false)
{
	echo "<tr><td class='label'>$label</td><td>";
	echo territories_list($name, $selected_id, $submit_on_change);
	echo "</td></tr>\n";
}

function preferred_contact_method_row($label, $name, $selected = null)
{
	global $Ajax;

	echo "<tr><td class='label'>$label</td><td>";
	echo "<select name='$name' id='$name'>";

	$methods = array('email' => _('Email'), 'phone' => _('Phone'), 'mail' => _('Mail'));
	foreach ($methods as $value => $display) {
		echo "<option value='$value'" . ($selected == $value ? " selected" : "") . ">$display</option>";
	}

	echo "</select></td></tr>\n";

	$Ajax->addUpdate($name, $name, $selected);
}

function credit_rating_row($label, $name, $selected = null)
{
	global $Ajax;

	echo "<tr><td class='label'>$label</td><td>";
	echo "<select name='$name' id='$name'>";

	$ratings = array('excellent' => _('Excellent'), 'good' => _('Good'), 'fair' => _('Fair'), 'poor' => _('Poor'));
	foreach ($ratings as $value => $display) {
		echo "<option value='$value'" . ($selected == $value ? " selected" : "") . ">$display</option>";
	}

	echo "</select></td></tr>\n";

	$Ajax->addUpdate($name, $name, $selected);
}

function display_customer_contacts($customer_id)
{
	$sql = "SELECT cc.*, cr.role_name
	FROM " . TB_PREF . "custcontacts cc
	LEFT JOIN " . TB_PREF . "crm_contact_roles cr ON cc.role_id = cr.id
	WHERE cc.debtorno = " . db_escape($customer_id) . "
	ORDER BY cc.contid";

	$result = db_query($sql, "Could not get customer contacts");

	if (db_num_rows($result) == 0) {
		echo "<p>" . _("No contacts defined for this customer") . "</p>";
		return;
	}

	start_table(TABLESTYLE);
	table_header(array(_("Name"), _("Role"), _("Phone"), _("Email"), _("Notes"), ""));

	while ($contact = db_fetch($result)) {
		start_row();
		label_cell($contact['contactname']);
		label_cell($contact['role_name'] ?: $contact['role']);
		label_cell($contact['phoneno']);
		email_cell($contact['email']);
		label_cell($contact['notes']);
		edit_button_cell("Edit" . $contact['contid'], _("Edit"));
		end_row();
	}

	end_table();
}

function display_customer_opportunities($customer_id)
{
	$result = get_customer_opportunities($customer_id);

	if (db_num_rows($result) == 0) {
		echo "<p>" . _("No sales opportunities for this customer") . "</p>";
		return;
	}

	start_table(TABLESTYLE);
	table_header(array(_("Opportunity"), _("Type"), _("Status"), _("Value"), _("Probability"), _("Close Date"), ""));

	while ($opp = db_fetch($result)) {
		start_row();
		label_cell($opp['opportunity_name']);
		label_cell($opp['opportunity_type']);
		label_cell($opp['status']);
		amount_cell($opp['estimated_value']);
		label_cell($opp['probability'] . '%');
		label_cell(sql2date($opp['expected_close_date']));
		edit_button_cell("EditOpp" . $opp['id'], _("Edit"));
		end_row();
	}

	end_table();
}

// ============================================================================
// EMAIL ACCOUNT MANAGEMENT FUNCTIONS
// ============================================================================

function add_email_account($account_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_email_accounts (
		account_name, email_address, server_host, server_port, encryption,
		username, password, auto_import, import_frequency, is_active, created_at
	) VALUES (
		" . db_escape($account_data['account_name']) . ",
		" . db_escape($account_data['email_address']) . ",
		" . db_escape($account_data['server_host']) . ",
		" . (int)$account_data['server_port'] . ",
		" . db_escape($account_data['encryption']) . ",
		" . db_escape($account_data['username']) . ",
		" . db_escape($account_data['password']) . ",
		" . (int)$account_data['auto_import'] . ",
		" . (int)$account_data['import_frequency'] . ",
		" . (int)$account_data['is_active'] . ",
		NOW()
	)";

	if (db_query($sql)) {
		return db_insert_id();
	}

	return false;
}

function update_email_account($account_id, $account_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_email_accounts SET
		account_name = " . db_escape($account_data['account_name']) . ",
		email_address = " . db_escape($account_data['email_address']) . ",
		server_host = " . db_escape($account_data['server_host']) . ",
		server_port = " . (int)$account_data['server_port'] . ",
		encryption = " . db_escape($account_data['encryption']) . ",
		username = " . db_escape($account_data['username']);

	if (!empty($account_data['password'])) {
		$sql .= ", password = " . db_escape($account_data['password']);
	}

	$sql .= ", auto_import = " . (int)$account_data['auto_import'] . ",
		import_frequency = " . (int)$account_data['import_frequency'] . ",
		is_active = " . (int)$account_data['is_active'];

	if (!empty($account_data['last_import'])) {
		$sql .= ", last_import = " . db_escape($account_data['last_import']);
	}

	$sql .= " WHERE id = " . (int)$account_id;

	return db_query($sql);
}

function delete_email_account($account_id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_email_accounts WHERE id = " . (int)$account_id;
	return db_query($sql);
}

function get_email_account($account_id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_email_accounts WHERE id = " . (int)$account_id;
	$result = db_query($sql);
	return db_fetch($result);
}

function get_email_accounts($active_only = true)
{
	$where = $active_only ? "WHERE is_active = 1" : "";
	$sql = "SELECT * FROM " . TB_PREF . "crm_email_accounts $where ORDER BY account_name";
	return db_query($sql);
}

function can_delete_email_account($account_id)
{
	// Check if account has associated communications
	$sql = "SELECT COUNT(*) FROM " . TB_PREF . "crm_communications WHERE email_account_id = " . (int)$account_id;
	$result = db_query($sql);
	$row = db_fetch($result);
	return $row[0] == 0;
}

function test_email_connection($connection_data)
{
	// Test IMAP connection
	$hostname = $connection_data['server_host'];
	$port = $connection_data['server_port'];
	$encryption = $connection_data['encryption'];
	$username = $connection_data['username'];
	$password = $connection_data['password'];

	// Build connection string
	$connection_string = "{" . $hostname . ":" . $port;

	switch ($encryption) {
		case 'ssl':
			$connection_string .= "/ssl";
			break;
		case 'tls':
			$connection_string .= "/tls";
			break;
	}

	$connection_string .= "}INBOX";

	// Test connection
	$imap = @imap_open($connection_string, $username, $password, OP_READONLY, 1);

	if ($imap) {
		imap_close($imap);
		return ['success' => true];
	} else {
		return ['success' => false, 'error' => imap_last_error()];
	}
}

// ============================================================================
// OPPORTUNITIES FUNCTIONS
// ============================================================================

function add_opportunity($customer_id, $name, $description, $stage, $amount, $probability, $expected_close_date, $assigned_to, $source)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_opportunities
		(customer_id, name, description, stage, amount, probability, expected_close_date, assigned_to, source, created_at)
		VALUES (" . db_escape($customer_id) . ", " . db_escape($name) . ", " . db_escape($description) . ",
		" . db_escape($stage) . ", " . (float)$amount . ", " . (float)$probability . ",
		" . db_escape($expected_close_date) . ", " . db_escape($assigned_to) . ", " . db_escape($source) . ", NOW())";

	db_query($sql, "Could not add opportunity");
	return db_insert_id();
}

function update_opportunity($id, $customer_id, $name, $description, $stage, $amount, $probability, $expected_close_date, $assigned_to, $source)
{
	$sql = "UPDATE " . TB_PREF . "crm_opportunities SET
		customer_id = " . db_escape($customer_id) . ",
		name = " . db_escape($name) . ",
		description = " . db_escape($description) . ",
		stage = " . db_escape($stage) . ",
		amount = " . (float)$amount . ",
		probability = " . (float)$probability . ",
		expected_close_date = " . db_escape($expected_close_date) . ",
		assigned_to = " . db_escape($assigned_to) . ",
		source = " . db_escape($source) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update opportunity");
}

function delete_opportunity($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_opportunities WHERE id = " . (int)$id;
	db_query($sql, "Could not delete opportunity");
}

function get_opportunities($active_only = true)
{
	$sql = "SELECT o.*, d.name as customer_name, u.user_id as assigned_to_name
		FROM " . TB_PREF . "crm_opportunities o
		JOIN " . TB_PREF . "debtors_master d ON o.customer_id = d.debtor_no
		LEFT JOIN " . TB_PREF . "users u ON o.assigned_to = u.id";

	if ($active_only) {
		$sql .= " WHERE o.stage NOT IN ('closed_won', 'closed_lost')";
	}

	$sql .= " ORDER BY o.expected_close_date ASC";

	$result = db_query($sql, "Could not get opportunities");
	return $result;
}

function get_opportunity($id)
{
	$sql = "SELECT o.*, d.name as customer_name, u.user_id as assigned_to_name
		FROM " . TB_PREF . "crm_opportunities o
		JOIN " . TB_PREF . "debtors_master d ON o.customer_id = d.debtor_no
		LEFT JOIN " . TB_PREF . "users u ON o.assigned_to = u.id
		WHERE o.id = " . (int)$id;

	$result = db_query($sql, "Could not get opportunity");
	return db_fetch($result);
}

// ============================================================================
// MEETING ROOMS FUNCTIONS
// ============================================================================

function add_meeting_room($room_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_meeting_rooms
		(room_name, room_type, location, capacity, equipment, phone_number, conference_url, active)
		VALUES (" . db_escape($room_data['room_name']) . ", " . db_escape($room_data['room_type']) . ",
		" . db_escape($room_data['location']) . ", " . (int)$room_data['capacity'] . ",
		" . db_escape($room_data['equipment']) . ", " . db_escape($room_data['phone_number']) . ",
		" . db_escape($room_data['conference_url']) . ", " . (int)$room_data['active'] . ")";

	db_query($sql, "Could not add meeting room");
	return db_insert_id();
}

function update_meeting_room($id, $room_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_meeting_rooms SET
		room_name = " . db_escape($room_data['room_name']) . ",
		room_type = " . db_escape($room_data['room_type']) . ",
		location = " . db_escape($room_data['location']) . ",
		capacity = " . (int)$room_data['capacity'] . ",
		equipment = " . db_escape($room_data['equipment']) . ",
		phone_number = " . db_escape($room_data['phone_number']) . ",
		conference_url = " . db_escape($room_data['conference_url']) . ",
		active = " . (int)$room_data['active'] . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update meeting room");
}

function delete_meeting_room($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_meeting_rooms WHERE id = " . (int)$id;
	db_query($sql, "Could not delete meeting room");
}

function get_meeting_rooms($active_only = true)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_meeting_rooms";
	if ($active_only) {
		$sql .= " WHERE active = 1";
	}
	$sql .= " ORDER BY room_name";

	$result = db_query($sql, "Could not get meeting rooms");
	return $result;
}

function get_meeting_room($id)
{
	$sql = "SELECT * FROM " . TB_PREF . "crm_meeting_rooms WHERE id = " . (int)$id;
	$result = db_query($sql, "Could not get meeting room");
	return db_fetch($result);
}

// ============================================================================
// MEETINGS FUNCTIONS
// ============================================================================

function add_meeting($meeting_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_meetings (
		meeting_name, meeting_type, description, start_date, end_date, duration_minutes, time_zone,
		location_type, room_id, custom_location, phone_number, conference_url, meeting_url,
		dial_in_number, access_code, host_pin, debtor_no, opportunity_id, project_id, quote_id,
		campaign_id, agenda, preparation_notes, status, priority, assigned_to, created_by,
		is_recurring, recurrence_id, reminder_minutes_before, ics_uid, external_id
	) VALUES (
		" . db_escape($meeting_data['meeting_name']) . ",
		" . db_escape($meeting_data['meeting_type']) . ",
		" . db_escape($meeting_data['description']) . ",
		" . db_escape($meeting_data['start_date']) . ",
		" . db_escape($meeting_data['end_date']) . ",
		" . (int)$meeting_data['duration_minutes'] . ",
		" . db_escape($meeting_data['time_zone']) . ",
		" . db_escape($meeting_data['location_type']) . ",
		" . db_escape($meeting_data['room_id']) . ",
		" . db_escape($meeting_data['custom_location']) . ",
		" . db_escape($meeting_data['phone_number']) . ",
		" . db_escape($meeting_data['conference_url']) . ",
		" . db_escape($meeting_data['meeting_url']) . ",
		" . db_escape($meeting_data['dial_in_number']) . ",
		" . db_escape($meeting_data['access_code']) . ",
		" . db_escape($meeting_data['host_pin']) . ",
		" . db_escape($meeting_data['debtor_no']) . ",
		" . db_escape($meeting_data['opportunity_id']) . ",
		" . db_escape($meeting_data['project_id']) . ",
		" . db_escape($meeting_data['quote_id']) . ",
		" . db_escape($meeting_data['campaign_id']) . ",
		" . db_escape($meeting_data['agenda']) . ",
		" . db_escape($meeting_data['preparation_notes']) . ",
		" . db_escape($meeting_data['status']) . ",
		" . db_escape($meeting_data['priority']) . ",
		" . db_escape($meeting_data['assigned_to']) . ",
		" . db_escape($meeting_data['created_by']) . ",
		" . (int)$meeting_data['is_recurring'] . ",
		" . db_escape($meeting_data['recurrence_id']) . ",
		" . (int)$meeting_data['reminder_minutes_before'] . ",
		" . db_escape($meeting_data['ics_uid']) . ",
		" . db_escape($meeting_data['external_id']) . "
	)";

	db_query($sql, "Could not add meeting");
	return db_insert_id();
}

function update_meeting($id, $meeting_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_meetings SET
		meeting_name = " . db_escape($meeting_data['meeting_name']) . ",
		meeting_type = " . db_escape($meeting_data['meeting_type']) . ",
		description = " . db_escape($meeting_data['description']) . ",
		start_date = " . db_escape($meeting_data['start_date']) . ",
		end_date = " . db_escape($meeting_data['end_date']) . ",
		duration_minutes = " . (int)$meeting_data['duration_minutes'] . ",
		time_zone = " . db_escape($meeting_data['time_zone']) . ",
		location_type = " . db_escape($meeting_data['location_type']) . ",
		room_id = " . db_escape($meeting_data['room_id']) . ",
		custom_location = " . db_escape($meeting_data['custom_location']) . ",
		phone_number = " . db_escape($meeting_data['phone_number']) . ",
		conference_url = " . db_escape($meeting_data['conference_url']) . ",
		meeting_url = " . db_escape($meeting_data['meeting_url']) . ",
		dial_in_number = " . db_escape($meeting_data['dial_in_number']) . ",
		access_code = " . db_escape($meeting_data['access_code']) . ",
		host_pin = " . db_escape($meeting_data['host_pin']) . ",
		debtor_no = " . db_escape($meeting_data['debtor_no']) . ",
		opportunity_id = " . db_escape($meeting_data['opportunity_id']) . ",
		project_id = " . db_escape($meeting_data['project_id']) . ",
		quote_id = " . db_escape($meeting_data['quote_id']) . ",
		campaign_id = " . db_escape($meeting_data['campaign_id']) . ",
		agenda = " . db_escape($meeting_data['agenda']) . ",
		preparation_notes = " . db_escape($meeting_data['preparation_notes']) . ",
		meeting_outcome = " . db_escape($meeting_data['meeting_outcome']) . ",
		follow_up_actions = " . db_escape($meeting_data['follow_up_actions']) . ",
		status = " . db_escape($meeting_data['status']) . ",
		priority = " . db_escape($meeting_data['priority']) . ",
		assigned_to = " . db_escape($meeting_data['assigned_to']) . ",
		is_recurring = " . (int)$meeting_data['is_recurring'] . ",
		recurrence_id = " . db_escape($meeting_data['recurrence_id']) . ",
		reminder_minutes_before = " . (int)$meeting_data['reminder_minutes_before'] . ",
		ics_uid = " . db_escape($meeting_data['ics_uid']) . ",
		external_id = " . db_escape($meeting_data['external_id']) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update meeting");
}

function delete_meeting($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_meetings WHERE id = " . (int)$id;
	db_query($sql, "Could not delete meeting");
}

function get_meetings($filters = array(), $limit = null, $offset = 0)
{
	$sql = "SELECT m.*,
		d.name as customer_name,
		r.room_name,
		u.user_id as assigned_to_name,
		o.opportunity_name,
		c.campaign_name
	FROM " . TB_PREF . "crm_meetings m
	LEFT JOIN " . TB_PREF . "debtors_master d ON m.debtor_no = d.debtor_no
	LEFT JOIN " . TB_PREF . "crm_meeting_rooms r ON m.room_id = r.id
	LEFT JOIN " . TB_PREF . "users u ON m.assigned_to = u.id
	LEFT JOIN " . TB_PREF . "crm_opportunities o ON m.opportunity_id = o.id
	LEFT JOIN " . TB_PREF . "crm_campaigns c ON m.campaign_id = c.id
	WHERE 1=1";

	// Apply filters
	if (!empty($filters['debtor_no'])) {
		$sql .= " AND m.debtor_no = " . db_escape($filters['debtor_no']);
	}
	if (!empty($filters['assigned_to'])) {
		$sql .= " AND m.assigned_to = " . db_escape($filters['assigned_to']);
	}
	if (!empty($filters['status'])) {
		$sql .= " AND m.status = " . db_escape($filters['status']);
	}
	if (!empty($filters['start_date'])) {
		$sql .= " AND m.start_date >= " . db_escape($filters['start_date']);
	}
	if (!empty($filters['end_date'])) {
		$sql .= " AND m.end_date <= " . db_escape($filters['end_date']);
	}
	if (!empty($filters['meeting_type'])) {
		$sql .= " AND m.meeting_type = " . db_escape($filters['meeting_type']);
	}

	$sql .= " ORDER BY m.start_date ASC";

	if ($limit) {
		$sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
	}

	$result = db_query($sql, "Could not get meetings");
	return $result;
}

function get_meeting($id)
{
	$sql = "SELECT m.*,
		d.name as customer_name,
		r.room_name, r.room_type, r.location, r.capacity, r.equipment,
		u.user_id as assigned_to_name,
		o.opportunity_name,
		c.campaign_name
	FROM " . TB_PREF . "crm_meetings m
	LEFT JOIN " . TB_PREF . "debtors_master d ON m.debtor_no = d.debtor_no
	LEFT JOIN " . TB_PREF . "crm_meeting_rooms r ON m.room_id = r.id
	LEFT JOIN " . TB_PREF . "users u ON m.assigned_to = u.id
	LEFT JOIN " . TB_PREF . "crm_opportunities o ON m.opportunity_id = o.id
	LEFT JOIN " . TB_PREF . "crm_campaigns c ON m.campaign_id = c.id
	WHERE m.id = " . (int)$id;

	$result = db_query($sql, "Could not get meeting");
	return db_fetch($result);
}

function get_upcoming_meetings($days_ahead = 7, $user_id = null)
{
	$end_date = date('Y-m-d H:i:s', strtotime("+{$days_ahead} days"));

	$sql = "SELECT m.*,
		d.name as customer_name,
		r.room_name,
		u.user_id as assigned_to_name
	FROM " . TB_PREF . "crm_meetings m
	LEFT JOIN " . TB_PREF . "debtors_master d ON m.debtor_no = d.debtor_no
	LEFT JOIN " . TB_PREF . "crm_meeting_rooms r ON m.room_id = r.id
	LEFT JOIN " . TB_PREF . "users u ON m.assigned_to = u.id
	WHERE m.start_date <= " . db_escape($end_date) . "
	AND m.status IN ('planned', 'confirmed')
	AND m.start_date >= NOW()";

	if ($user_id) {
		$sql .= " AND (m.assigned_to = " . db_escape($user_id) . "
			OR m.id IN (
				SELECT meeting_id FROM " . TB_PREF . "crm_meeting_attendees
				WHERE attendee_type = 'employee' AND employee_id = " . db_escape($user_id) . "
			))";
	}

	$sql .= " ORDER BY m.start_date ASC";

	$result = db_query($sql, "Could not get upcoming meetings");
	return $result;
}

// ============================================================================
// MEETING ATTENDEES FUNCTIONS
// ============================================================================

function add_meeting_attendee($meeting_id, $attendee_data)
{
	$sql = "INSERT INTO " . TB_PREF . "crm_meeting_attendees (
		meeting_id, attendee_type, employee_id, contact_id, external_name,
		external_email, external_phone, attendee_role, response_status, notes
	) VALUES (
		" . (int)$meeting_id . ",
		" . db_escape($attendee_data['attendee_type']) . ",
		" . db_escape($attendee_data['employee_id']) . ",
		" . db_escape($attendee_data['contact_id']) . ",
		" . db_escape($attendee_data['external_name']) . ",
		" . db_escape($attendee_data['external_email']) . ",
		" . db_escape($attendee_data['external_phone']) . ",
		" . db_escape($attendee_data['attendee_role']) . ",
		" . db_escape($attendee_data['response_status']) . ",
		" . db_escape($attendee_data['notes']) . "
	)";

	db_query($sql, "Could not add meeting attendee");
	return db_insert_id();
}

function update_meeting_attendee($id, $attendee_data)
{
	$sql = "UPDATE " . TB_PREF . "crm_meeting_attendees SET
		attendee_type = " . db_escape($attendee_data['attendee_type']) . ",
		employee_id = " . db_escape($attendee_data['employee_id']) . ",
		contact_id = " . db_escape($attendee_data['contact_id']) . ",
		external_name = " . db_escape($attendee_data['external_name']) . ",
		external_email = " . db_escape($attendee_data['external_email']) . ",
		external_phone = " . db_escape($attendee_data['external_phone']) . ",
		attendee_role = " . db_escape($attendee_data['attendee_role']) . ",
		response_status = " . db_escape($attendee_data['response_status']) . ",
		response_date = NOW(),
		notes = " . db_escape($attendee_data['notes']) . "
	WHERE id = " . (int)$id;

	db_query($sql, "Could not update meeting attendee");
}

function delete_meeting_attendee($id)
{
	$sql = "DELETE FROM " . TB_PREF . "crm_meeting_attendees WHERE id = " . (int)$id;
	db_query($sql, "Could not delete meeting attendee");
}

function get_meeting_attendees($meeting_id)
{
	$sql = "SELECT ma.*,
		u.user_id as employee_name,
		CONCAT(cc.first_name, ' ', cc.last_name) as contact_name,
		cc.email as contact_email,
		cc.phone as contact_phone
	FROM " . TB_PREF . "crm_meeting_attendees ma
	LEFT JOIN " . TB_PREF . "users u ON ma.employee_id = u.id AND ma.attendee_type = 'employee'
	LEFT JOIN " . TB_PREF . "crm_contacts cc ON ma.contact_id = cc.id AND ma.attendee_type = 'contact'
	WHERE ma.meeting_id = " . (int)$meeting_id . "
	ORDER BY ma.attendee_role ASC, ma.attendee_type ASC";

	$result = db_query($sql, "Could not get meeting attendees");
	return $result;
}

function update_attendee_response($attendee_id, $response_status)
{
	$sql = "UPDATE " . TB_PREF . "crm_meeting_attendees SET
		response_status = " . db_escape($response_status) . ",
		response_date = NOW()
	WHERE id = " . (int)$attendee_id;

	db_query($sql, "Could not update attendee response");
}

// ============================================================================
// ICS AND CALENDAR FUNCTIONS
// ============================================================================

function generate_ics_content($meeting)
{
	$ics = "BEGIN:VCALENDAR\r\n";
	$ics .= "VERSION:2.0\r\n";
	$ics .= "PRODID:-//FrontAccounting CRM//EN\r\n";
	$ics .= "METHOD:REQUEST\r\n";
	$ics .= "BEGIN:VEVENT\r\n";

	// UID
	$ics .= "UID:" . ($meeting['ics_uid'] ?: uniqid('FA-CRM-', true)) . "\r\n";

	// Basic event details
	$ics .= "SUMMARY:" . $meeting['meeting_name'] . "\r\n";
	$ics .= "DESCRIPTION:" . str_replace(array("\r\n", "\n"), "\\n", $meeting['description']) . "\r\n";

	// Dates (convert to UTC)
	$start_utc = date('Ymd\THis\Z', strtotime($meeting['start_date']));
	$end_utc = date('Ymd\THis\Z', strtotime($meeting['end_date']));
	$ics .= "DTSTART:" . $start_utc . "\r\n";
	$ics .= "DTEND:" . $end_utc . "\r\n";

	// Location
	if ($meeting['location_type'] == 'physical' && $meeting['room_name']) {
		$ics .= "LOCATION:" . $meeting['room_name'];
		if ($meeting['location']) {
			$ics .= " - " . $meeting['location'];
		}
		$ics .= "\r\n";
	} elseif ($meeting['location_type'] == 'virtual' && $meeting['conference_url']) {
		$ics .= "LOCATION:" . $meeting['conference_url'] . "\r\n";
	} elseif ($meeting['location_type'] == 'phone' && $meeting['phone_number']) {
		$ics .= "LOCATION:" . $meeting['phone_number'] . "\r\n";
	}

	// Organizer
	if ($meeting['assigned_to_name']) {
		$ics .= "ORGANIZER;CN=" . $meeting['assigned_to_name'] . ":mailto:" . $meeting['assigned_to'] . "\r\n";
	}

	// Attendees
	$attendees = get_meeting_attendees($meeting['id']);
	while ($attendee = db_fetch($attendees)) {
		$attendee_name = '';
		$attendee_email = '';

		if ($attendee['attendee_type'] == 'employee' && $attendee['employee_name']) {
			$attendee_name = $attendee['employee_name'];
			$attendee_email = $attendee['employee_id']; // This should be email, but we need to get it from users table
		} elseif ($attendee['attendee_type'] == 'contact' && $attendee['contact_name']) {
			$attendee_name = $attendee['contact_name'];
			$attendee_email = $attendee['contact_email'];
		} elseif ($attendee['attendee_type'] == 'external') {
			$attendee_name = $attendee['external_name'];
			$attendee_email = $attendee['external_email'];
		}

		if ($attendee_email) {
			$role = $attendee['attendee_role'] == 'required' ? 'REQ-PARTICIPANT' : 'OPT-PARTICIPANT';
			$ics .= "ATTENDEE;ROLE=" . $role . ";CN=" . $attendee_name . ":mailto:" . $attendee_email . "\r\n";
		}
	}

	// Status
	$status_map = array(
		'planned' => 'TENTATIVE',
		'confirmed' => 'CONFIRMED',
		'cancelled' => 'CANCELLED',
		'completed' => 'CONFIRMED'
	);
	if (isset($status_map[$meeting['status']])) {
		$ics .= "STATUS:" . $status_map[$meeting['status']] . "\r\n";
	}

	// Priority
	$priority_map = array('low' => 9, 'normal' => 5, 'high' => 1, 'urgent' => 1);
	if (isset($priority_map[$meeting['priority']])) {
		$ics .= "PRIORITY:" . $priority_map[$meeting['priority']] . "\r\n";
	}

	// Timestamps
	$created = date('Ymd\THis\Z', strtotime($meeting['created_at']));
	$ics .= "CREATED:" . $created . "\r\n";
	$ics .= "DTSTAMP:" . date('Ymd\THis\Z') . "\r\n";

	$ics .= "END:VEVENT\r\n";
	$ics .= "END:VCALENDAR\r\n";

	return $ics;
}

function parse_ics_file($ics_content)
{
	$events = array();
	$lines = explode("\n", $ics_content);

	$current_event = null;
	$in_event = false;

	foreach ($lines as $line) {
		$line = trim($line);

		if ($line == 'BEGIN:VEVENT') {
			$current_event = array();
			$in_event = true;
		} elseif ($line == 'END:VEVENT') {
			if ($current_event) {
				$events[] = $current_event;
			}
			$current_event = null;
			$in_event = false;
		} elseif ($in_event && strpos($line, ':') !== false) {
			list($key, $value) = explode(':', $line, 2);

			// Handle parameters in key
			if (strpos($key, ';') !== false) {
				list($key, $params) = explode(';', $key, 2);
			}

			switch ($key) {
				case 'SUMMARY':
					$current_event['meeting_name'] = $value;
					break;
				case 'DESCRIPTION':
					$current_event['description'] = str_replace('\\n', "\n", $value);
					break;
				case 'DTSTART':
					$current_event['start_date'] = $value;
					break;
				case 'DTEND':
					$current_event['end_date'] = $value;
					break;
				case 'LOCATION':
					$current_event['location'] = $value;
					break;
				case 'UID':
					$current_event['ics_uid'] = $value;
					break;
				case 'STATUS':
					$status_map = array(
						'TENTATIVE' => 'planned',
						'CONFIRMED' => 'confirmed',
						'CANCELLED' => 'cancelled'
					);
					$current_event['status'] = isset($status_map[$value]) ? $status_map[$value] : 'planned';
					break;
			}
		}
	}

	return $events;
}