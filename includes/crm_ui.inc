<?php
/**
 * CRM UI Components
 *
 * WebERP-style CRM user interface components for FrontAccounting
 */

// Include CRM database functions
include_once($path_to_root . "/modules/CRM/includes/crm_db.inc");

/**
 * Display CRM customer summary
 */
function crm_customer_summary($customer_id)
{
	$crm_data = get_customer_crm_data($customer_id);
	$analytics = get_customer_analytics($customer_id);

	if (!$crm_data && !$analytics) {
		return;
	}

	echo '<div class="crm-summary" style="background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007bff;">';
	echo '<h4 style="margin-top: 0; color: #007bff;">' . _("CRM Information") . '</h4>';

	if ($crm_data) {
		echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-bottom: 15px;">';

		if ($crm_data['customer_type_id']) {
			$type = get_customer_type($crm_data['customer_type_id']);
			echo '<div><strong>' . _("Type:") . '</strong> ' . ($type ? $type['type_name'] : 'N/A') . '</div>';
		}

		if ($crm_data['territory_id']) {
			$territory = get_territory($crm_data['territory_id']);
			echo '<div><strong>' . _("Territory:") . '</strong> ' . ($territory ? $territory['territory_name'] : 'N/A') . '</div>';
		}

		if ($crm_data['account_manager']) {
			echo '<div><strong>' . _("Account Manager:") . '</strong> ' . $crm_data['account_manager'] . '</div>';
		}

		if ($crm_data['credit_rating']) {
			$ratings = array('excellent' => _('Excellent'), 'good' => _('Good'), 'fair' => _('Fair'), 'poor' => _('Poor'));
			echo '<div><strong>' . _("Credit Rating:") . '</strong> ' . ($ratings[$crm_data['credit_rating']] ?? $crm_data['credit_rating']) . '</div>';
		}

		if ($crm_data['industry']) {
			echo '<div><strong>' . _("Industry:") . '</strong> ' . $crm_data['industry'] . '</div>';
		}

		if ($crm_data['website']) {
			echo '<div><strong>' . _("Website:") . '</strong> <a href="' . htmlspecialchars($crm_data['website']) . '" target="_blank">' . htmlspecialchars($crm_data['website']) . '</a></div>';
		}

		if ($crm_data['annual_revenue']) {
			echo '<div><strong>' . _("Annual Revenue:") . '</strong> ' . FormatService::priceFormat($crm_data['annual_revenue']) . '</div>';
		}

		if ($crm_data['employee_count']) {
			echo '<div><strong>' . _("Employees:") . '</strong> ' . number_format($crm_data['employee_count']) . '</div>';
		}

		echo '</div>';
	}

	if ($analytics) {
		echo '<hr style="margin: 10px 0;">';
		echo '<h5 style="color: #28a745;">' . _("Customer Analytics") . '</h5>';
		echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">';
		echo '<div><strong>' . _("Lifetime Value:") . '</strong><br>' . FormatService::priceFormat($analytics['customer_lifetime_value']) . '</div>';
		echo '<div><strong>' . _("Total Sales:") . '</strong><br>' . FormatService::priceFormat($analytics['total_sales']) . '</div>';
		echo '<div><strong>' . _("Outstanding:") . '</strong><br>' . FormatService::priceFormat($analytics['outstanding_balance']) . '</div>';
		echo '<div><strong>' . _("Payment Reliability:") . '</strong><br>' . number_format($analytics['payment_days_avg'], 1) . ' ' . _("days avg") . '</div>';
		if ($analytics['communication_count']) {
			echo '<div><strong>' . _("Communications:") . '</strong><br>' . number_format($analytics['communication_count']) . '</div>';
		}
		echo '</div>';
	}

	echo '</div>';
}

/**
 * Display customer contacts summary
 */
function crm_customer_contacts_summary($customer_id)
{
	$contacts = get_customer_contacts($customer_id);

	if (db_num_rows($contacts) == 0) {
		return;
	}

	echo '<div class="crm-contacts" style="background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #17a2b8;">';
	echo '<h4 style="margin-top: 0; color: #17a2b8;">' . _("CRM Contacts") . '</h4>';

	echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">';

	while ($contact = db_fetch($contacts)) {
		echo '<div style="background: white; padding: 10px; border-radius: 3px; border: 1px solid #dee2e6;">';
		echo '<strong>' . htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) . '</strong>';
		if ($contact['role_name']) {
			echo '<br><small style="color: #6c757d;">' . htmlspecialchars($contact['role_name']) . '</small>';
		}
		if ($contact['title']) {
			echo '<br><small>' . htmlspecialchars($contact['title']) . '</small>';
		}
		if ($contact['phone']) {
			echo '<br><i class="fas fa-phone"></i> ' . htmlspecialchars($contact['phone']);
		}
		if ($contact['mobile']) {
			echo '<br><i class="fas fa-mobile-alt"></i> ' . htmlspecialchars($contact['mobile']);
		}
		if ($contact['email']) {
			echo '<br><i class="fas fa-envelope"></i> <a href="mailto:' . htmlspecialchars($contact['email']) . '">' . htmlspecialchars($contact['email']) . '</a>';
		}
		if ($contact['is_primary']) {
			echo '<br><span style="color: #007bff; font-weight: bold;">' . _("Primary Contact") . '</span>';
		}
		echo '</div>';
	}

	echo '</div>';
}

/**
 * Display recent communications
 */
function crm_recent_communications($customer_id, $limit = 5)
{
	$communications = get_customer_communications($customer_id, $limit);

	if (db_num_rows($communications) == 0) {
		return;
	}

	echo '<div class="crm-communications" style="background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #ffc107;">';
	echo '<h4 style="margin-top: 0; color: #ffc107;">' . _("Recent Communications") . '</h4>';

	echo '<div style="max-height: 300px; overflow-y: auto;">';
	echo '<table class="tablestyle" style="width: 100%;">';
	echo '<thead><tr>';
	echo '<th>' . _("Date") . '</th>';
	echo '<th>' . _("Type") . '</th>';
	echo '<th>' . _("Subject") . '</th>';
	echo '<th>' . _("Contact") . '</th>';
	echo '<th>' . _("Status") . '</th>';
	echo '</tr></thead><tbody>';

	while ($comm = db_fetch($communications)) {
		$date = sql2date($comm['created_at']);
		$type = ucfirst($comm['communication_type']);
		$subject = $comm['subject'] ? htmlspecialchars(substr($comm['subject'], 0, 30)) : _("No Subject");
		$contact = $comm['first_name'] ? htmlspecialchars($comm['first_name'] . ' ' . $comm['last_name']) : _("General");
		$status = ucfirst($comm['status']);

		$status_color = '';
		switch ($comm['status']) {
			case 'completed': $status_color = 'color: #28a745;'; break;
			case 'scheduled': $status_color = 'color: #007bff;'; break;
			case 'cancelled': $status_color = 'color: #dc3545;'; break;
			case 'failed': $status_color = 'color: #dc3545;'; break;
		}

		echo '<tr>';
		echo '<td>' . $date . '</td>';
		echo '<td>' . $type . '</td>';
		echo '<td>' . $subject . '</td>';
		echo '<td>' . $contact . '</td>';
		echo '<td style="' . $status_color . '">' . $status . '</td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	echo '</div>';

	echo '<div style="margin-top: 10px;">';
	echo '<a href="/modules/CRM/pages/communications.php?debtor_no=' . $customer_id . '" class="btn btn-sm btn-outline-primary">' . _("View All Communications") . '</a>';
	echo '</div>';

	echo '</div>';
}

/**
 * Display CRM dashboard widget
 */
function crm_dashboard_widget()
{
	global $db;

	// Get summary statistics
	$sql = "SELECT
		COUNT(DISTINCT c.debtor_no) as total_customers,
		COUNT(comm.id) as total_communications,
		COUNT(CASE WHEN comm.status = 'scheduled' THEN 1 END) as scheduled_communications,
		COUNT(CASE WHEN o.status IN ('prospect', 'qualified') THEN 1 END) as active_opportunities
	FROM " . TB_PREF . "crm_customers c
	LEFT JOIN " . TB_PREF . "crm_communications comm ON c.debtor_no = comm.debtor_no
	LEFT JOIN " . TB_PREF . "crm_opportunities o ON c.debtor_no = o.debtor_no";

	$result = db_query($sql);
	$stats = db_fetch($result);

	echo '<div class="crm-dashboard-widget" style="background: #f8f9fa; padding: 20px; margin: 10px 0; border-radius: 5px;">';
	echo '<h3 style="margin-top: 0; color: #495057;">' . _("CRM Dashboard") . '</h3>';

	echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">';
	echo '<div style="text-align: center; padding: 15px; background: white; border-radius: 3px; border: 1px solid #dee2e6;">';
	echo '<div style="font-size: 24px; font-weight: bold; color: #007bff;">' . number_format($stats['total_customers']) . '</div>';
	echo '<div style="color: #6c757d;">' . _("CRM Customers") . '</div>';
	echo '</div>';

	echo '<div style="text-align: center; padding: 15px; background: white; border-radius: 3px; border: 1px solid #dee2e6;">';
	echo '<div style="font-size: 24px; font-weight: bold; color: #ffc107;">' . number_format($stats['total_communications']) . '</div>';
	echo '<div style="color: #6c757d;">' . _("Communications") . '</div>';
	echo '</div>';

	echo '<div style="text-align: center; padding: 15px; background: white; border-radius: 3px; border: 1px solid #dee2e6;">';
	echo '<div style="font-size: 24px; font-weight: bold; color: #17a2b8;">' . number_format($stats['scheduled_communications']) . '</div>';
	echo '<div style="color: #6c757d;">' . _("Scheduled") . '</div>';
	echo '</div>';

	echo '<div style="text-align: center; padding: 15px; background: white; border-radius: 3px; border: 1px solid #dee2e6;">';
	echo '<div style="font-size: 24px; font-weight: bold; color: #28a745;">' . number_format($stats['active_opportunities']) . '</div>';
	echo '<div style="color: #6c757d;">' . _("Opportunities") . '</div>';
	echo '</div>';
	echo '</div>';

	// Quick actions
	echo '<div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #dee2e6;">';
	echo '<h5>' . _("Quick Actions") . '</h5>';
	echo '<div style="display: flex; gap: 10px; flex-wrap: wrap;">';
	echo '<a href="/modules/CRM/pages/dashboard.php" class="btn btn-sm btn-primary">' . _("CRM Dashboard") . '</a>';
	echo '<a href="/sales/manage/customers.php" class="btn btn-sm btn-secondary">' . _("Add Customer") . '</a>';
	echo '<a href="/modules/CRM/pages/customer_types.php" class="btn btn-sm btn-info">' . _("Manage Types") . '</a>';
	echo '<a href="/modules/CRM/pages/territories.php" class="btn btn-sm btn-success">' . _("Manage Territories") . '</a>';
	echo '</div>';
	echo '</div>';

	echo '</div>';
}

/**
 * Display customer type badge
 */
function customer_type_badge($customer_type_id)
{
	if (!$customer_type_id) {
		return '';
	}

	$type = get_customer_type($customer_type_id);
	if (!$type) {
		return '';
	}

	$colors = array(
		'Retail' => '#007bff',
		'Wholesale' => '#28a745',
		'Corporate' => '#dc3545',
		'Government' => '#6f42c1',
		'Non-Profit' => '#fd7e14'
	);

	$color = $colors[$type['type_name']] ?? '#6c757d';

	return '<span style="background-color: ' . $color . '; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">' . htmlspecialchars($type['type_name']) . '</span>';
}

/**
 * Display territory badge
 */
function territory_badge($territory_id)
{
	if (!$territory_id) {
		return '';
	}

	$territory = get_territory($territory_id);
	if (!$territory) {
		return '';
	}

	return '<span style="background-color: #17a2b8; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">' . htmlspecialchars($territory['territory_name']) . '</span>';
}

/**
 * Communication type dropdown
 */
function comms_type_list_row($label, $name, $value = null)
{
	$types = array(
		'call' => _("Phone Call"),
		'meeting' => _("Meeting"),
		'email' => _("Email"),
		'sms' => _("SMS"),
		'note' => _("Note"),
		'letter' => _("Letter")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $types);
	echo '</td></tr>';
}

/**
 * Direction dropdown
 */
function direction_list_row($label, $name, $value = null)
{
	$directions = array(
		'inbound' => _("Inbound"),
		'outbound' => _("Outbound"),
		'internal' => _("Internal")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $directions);
	echo '</td></tr>';
}

/**
 * Priority dropdown
 */
function priority_list_row($label, $name, $value = null)
{
	$priorities = array(
		'low' => _("Low"),
		'medium' => _("Medium"),
		'high' => _("High"),
		'urgent' => _("Urgent")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $priorities);
	echo '</td></tr>';
}

/**
 * Status dropdown
 */
function status_list_row($label, $name, $value = null)
{
	$statuses = array(
		'scheduled' => _("Scheduled"),
		'completed' => _("Completed"),
		'cancelled' => _("Cancelled"),
		'failed' => _("Failed")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $statuses);
	echo '</td></tr>';
}

/**
 * Customer types dropdown
 */
function customer_types_list_row($label, $name, $value = null)
{
	$types = get_customer_types();
	$options = array('' => _("Select Type"));

	while ($type = db_fetch($types)) {
		$options[$type['id']] = $type['type_name'];
	}

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $options);
	echo '</td></tr>';
}

/**
 * Customer segments dropdown
 */
function customer_segments_list_row($label, $name, $value = null)
{
	global $db;

	$sql = "SELECT id, segment_name FROM " . TB_PREF . "crm_customer_segments WHERE inactive = 0 ORDER BY segment_name";
	$result = db_query($sql);

	$options = array('' => _("Select Segment"));
	while ($segment = db_fetch($result)) {
		$options[$segment['id']] = $segment['segment_name'];
	}

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $options);
	echo '</td></tr>';
}

/**
 * Territories dropdown
 */
function territories_list_row($label, $name, $value = null)
{
	global $db;

	$sql = "SELECT id, territory_name FROM " . TB_PREF . "crm_territories WHERE inactive = 0 ORDER BY territory_name";
	$result = db_query($sql);

	$options = array('' => _("Select Territory"));
	while ($territory = db_fetch($result)) {
		$options[$territory['id']] = $territory['territory_name'];
	}

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $options);
	echo '</td></tr>';
}

/**
 * Contact roles dropdown
 */
function contact_roles_list_row($label, $name, $value = null)
{
	global $db;

	$sql = "SELECT id, role_name FROM " . TB_PREF . "crm_contact_roles WHERE inactive = 0 ORDER BY role_name";
	$result = db_query($sql);

	$options = array('' => _("Select Role"));
	while ($role = db_fetch($result)) {
		$options[$role['id']] = $role['role_name'];
	}

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $options);
	echo '</td></tr>';
}

/**
 * Preferred contact method dropdown
 */
function preferred_contact_method_row($label, $name, $value = null)
{
	$methods = array(
		'email' => _("Email"),
		'phone' => _("Phone"),
		'mobile' => _("Mobile"),
		'mail' => _("Mail"),
		'fax' => _("Fax")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $methods);
	echo '</td></tr>';
}

/**
 * Credit rating dropdown
 */
function credit_rating_row($label, $name, $value = null)
{
	$ratings = array(
		'excellent' => _("Excellent"),
		'good' => _("Good"),
		'fair' => _("Fair"),
		'poor' => _("Poor")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $ratings);
	echo '</td></tr>';
}

/**
 * Display customer contacts
 */
function display_customer_contacts($customer_id)
{
	$contacts = get_customer_contacts($customer_id);

	if (db_num_rows($contacts) == 0) {
		echo '<p>' . _("No contacts found for this customer.") . '</p>';
		return;
	}

	start_table(TABLESTYLE);
	table_header(array(_("Name"), _("Role"), _("Phone"), _("Email"), _("Primary"), ""));

	while ($contact = db_fetch($contacts)) {
		start_row();
		label_cell($contact['first_name'] . ' ' . $contact['last_name']);
		label_cell($contact['role_name'] ?? '');
		label_cell($contact['phone'] ?? '');
		label_cell($contact['email'] ?? '');
		label_cell($contact['is_primary'] ? _("Yes") : _("No"));
		edit_button_cell("Edit" . $contact['id'], _("Edit"));
		delete_button_cell("Delete" . $contact['id'], _("Delete"));
		end_row();
	}

	end_table();
}

/**
 * Display customer opportunities
 */
function display_customer_opportunities($customer_id)
{
	global $db;

	$sql = "SELECT o.*, s.stage_name, u.user_id as assigned_to_name
	FROM " . TB_PREF . "crm_opportunities o
	LEFT JOIN " . TB_PREF . "crm_opportunity_stages s ON o.stage_id = s.id
	LEFT JOIN " . TB_PREF . "users u ON o.assigned_to = u.id
	WHERE o.debtor_no = " . db_escape($customer_id) . "
	ORDER BY o.created_at DESC";

	$result = db_query($sql);

	if (db_num_rows($result) == 0) {
		echo '<p>' . _("No opportunities found for this customer.") . '</p>';
		return;
	}

	start_table(TABLESTYLE);
	table_header(array(_("Opportunity"), _("Value"), _("Probability"), _("Stage"), _("Close Date"), _("Assigned To"), ""));

	while ($opp = db_fetch($result)) {
		start_row();
		label_cell($opp['opportunity_name']);
		amount_cell($opp['estimated_value']);
		label_cell($opp['probability'] . '%');
		label_cell($opp['stage_name'] ?? $opp['status']);
		label_cell(sql2date($opp['expected_close_date']));
		label_cell($opp['assigned_to_name'] ?? '');
		edit_button_cell("EditOpp" . $opp['id'], _("Edit"));
		end_row();
	}

	end_table();
}

/**
 * Sales stage dropdown for opportunities
 */
function sales_stage_list_row($label, $name, $value = null)
{
	$stages = array(
		'prospecting' => _("Prospecting"),
		'qualification' => _("Qualification"),
		'proposal' => _("Proposal"),
		'negotiation' => _("Negotiation"),
		'closed_won' => _("Closed Won"),
		'closed_lost' => _("Closed Lost")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $stages);
	echo '</td></tr>';
}

/**
 * Meeting type dropdown
 */
function meeting_type_list_row($label, $name, $value = null)
{
	$types = array(
		'meeting' => _("Meeting"),
		'call' => _("Phone Call"),
		'presentation' => _("Presentation"),
		'training' => _("Training"),
		'other' => _("Other")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $types);
	echo '</td></tr>';
}

/**
 * Location type dropdown
 */
function location_type_list_row($label, $name, $value = null)
{
	$types = array(
		'physical' => _("Physical Location"),
		'virtual' => _("Virtual Meeting"),
		'phone' => _("Phone Conference")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $types);
	echo '</td></tr>';
}

/**
 * Meeting rooms dropdown
 */
function meeting_rooms_list_row($label, $name, $value = null, $location_type = null)
{
	global $db;

	$sql = "SELECT id, room_name, room_type FROM " . TB_PREF . "crm_meeting_rooms WHERE active = 1";

	if ($location_type) {
		$sql .= " AND room_type = " . db_escape($location_type);
	}

	$sql .= " ORDER BY room_name";

	$result = db_query($sql);
	$options = array('' => _("Select Room"));

	while ($room = db_fetch($result)) {
		$options[$room['id']] = $room['room_name'] . ' (' . ucfirst($room['room_type']) . ')';
	}

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $options);
	echo '</td></tr>';
}

/**
 * Meeting status dropdown
 */
function meeting_status_list_row($label, $name, $value = null)
{
	$statuses = array(
		'planned' => _("Planned"),
		'confirmed' => _("Confirmed"),
		'in_progress' => _("In Progress"),
		'completed' => _("Completed"),
		'cancelled' => _("Cancelled"),
		'postponed' => _("Postponed")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $statuses);
	echo '</td></tr>';
}

/**
 * Attendee role dropdown
 */
function attendee_role_list_row($label, $name, $value = null)
{
	$roles = array(
		'organizer' => _("Organizer"),
		'required' => _("Required"),
		'optional' => _("Optional"),
		'resource' => _("Resource")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $roles);
	echo '</td></tr>';
}

/**
 * Attendee type dropdown
 */
function attendee_type_list_row($label, $name, $value = null)
{
	$types = array(
		'employee' => _("Employee"),
		'contact' => _("Contact"),
		'external' => _("External")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $types);
	echo '</td></tr>';
}

/**
 * Response status dropdown
 */
function response_status_list_row($label, $name, $value = null)
{
	$statuses = array(
		'pending' => _("Pending"),
		'accepted' => _("Accepted"),
		'declined' => _("Declined"),
		'tentative' => _("Tentative")
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $statuses);
	echo '</td></tr>';
}

/**
 * Time zone dropdown
 */
function timezone_list_row($label, $name, $value = null)
{
	$timezones = array(
		'UTC' => 'UTC',
		'America/New_York' => 'Eastern Time',
		'America/Chicago' => 'Central Time',
		'America/Denver' => 'Mountain Time',
		'America/Los_Angeles' => 'Pacific Time',
		'Europe/London' => 'London',
		'Europe/Paris' => 'Paris',
		'Asia/Tokyo' => 'Tokyo',
		'Australia/Sydney' => 'Sydney'
	);

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $timezones);
	echo '</td></tr>';
}

/**
 * Opportunities dropdown
 */
function opportunity_list_row($label, $name, $value = null, $customer_id = null)
{
	global $db;

	$sql = "SELECT id, opportunity_name FROM " . TB_PREF . "crm_opportunities WHERE 1=1";

	if ($customer_id) {
		$sql .= " AND customer_id = " . db_escape($customer_id);
	}

	$sql .= " ORDER BY opportunity_name";

	$result = db_query($sql);
	$options = array('' => _("Select Opportunity"));

	while ($opp = db_fetch($result)) {
		$options[$opp['id']] = $opp['opportunity_name'];
	}

	echo '<tr><td class="label">' . $label . '</td><td>';
	echo array_selector($name, $value, $options);
	echo '</td></tr>';
}