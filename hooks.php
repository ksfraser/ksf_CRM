<?php

define('SS_CRM', 114 << 8);

/**
 * FA_CRM Module Hooks for FrontAccounting
 *
 * @package FA_CRM
 */
class hooks_fa_crm extends hooks {
    var $module_name = 'FA_CRM';

    /**
     * Install additional menu options provided by module
     */
    function install_options($app) {
        global $path_to_root;

        switch($app->id) {
            case 'Sales':
                $app->add_lapp_function(0, _("CRM Dashboard"),
                    $path_to_root."/modules/".$this->module_name."/dashboard.php", 'SA_CRM_DASHBOARD', MENU_MAIN);
                $app->add_lapp_function(1, _("CRM Customers"),
                    $path_to_root."/modules/".$this->module_name."/customers.php", 'SA_CRM_CUSTOMER', MENU_ENTRY);
                $app->add_lapp_function(1, _("Opportunities"),
                    $path_to_root."/modules/".$this->module_name."/opportunities.php", 'SA_CRM_OPPORTUNITY', MENU_ENTRY);
                $app->add_lapp_function(2, _("Communications Log"),
                    $path_to_root."/modules/".$this->module_name."/communications.php", 'SA_CRM_COMMUNICATION', MENU_INQUIRY);
                $app->add_rapp_function(3, _("CRM Setup"),
                    $path_to_root."/modules/".$this->module_name."/setup.php", 'SA_CRM_SETUP', MENU_MAINTENANCE);
                break;
        }
    }

    /**
     * Install access levels
     */
    function install_access() {
        $security_sections[SS_CRM] = _("CRM Management");
        $security_areas['SA_CRM_DASHBOARD'] = array(SS_CRM | 1, _("CRM Dashboard"));
        $security_areas['SA_CRM_CUSTOMER'] = array(SS_CRM | 2, _("CRM Customers"));
        $security_areas['SA_CRM_OPPORTUNITY'] = array(SS_CRM | 3, _("CRM Opportunities"));
        $security_areas['SA_CRM_COMMUNICATION'] = array(SS_CRM | 4, _("CRM Communications"));
        $security_areas['SA_CRM_SETUP'] = array(SS_CRM | 5, _("CRM Setup"));
        return array($security_areas, $security_sections);
    }

    /**
     * Activate extension
     */
    function activate_extension($company, $check_only=true) {
        $updates = array('sql/update.sql' => array($this->module_name));
        $ok = $this->update_databases($company, $updates, $check_only);
        if ($check_only || !$ok) {
            return $ok;
        }

        $this->ensure_crm_schema();
        return $ok;
    }

    /**
     * Ensure database schema exists
     */
    private function ensure_crm_schema() {
        $this->ensure_crm_tables();
        $this->ensure_crm_initial_data();
    }

    /**
     * Check if table exists
     */
    private function table_exists($table) {
        $sql = "SHOW TABLES LIKE " . db_escape($table);
        $res = db_query($sql, 'Failed checking table existence');
        return db_num_rows($res) > 0;
    }

    /**
     * Check if column exists
     */
    private function column_exists($table, $column) {
        $sql = "SHOW COLUMNS FROM `{$table}` LIKE " . db_escape($column);
        $res = db_query($sql, 'Failed checking column existence');
        return db_num_rows($res) > 0;
    }

    /**
     * Ensure column exists
     */
    private function ensure_column($table, $column, $definition) {
        if (!$this->table_exists($table)) {
            return;
        }
        if ($this->column_exists($table, $column)) {
            return;
        }
        $sql = "ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}";
        db_query($sql, 'Failed adding column to CRM schema');
    }

    /**
     * Ensure all CRM tables exist
     */
    private function ensure_crm_tables() {
        $tables = array(
            TB_PREF . "fa_crm_customers" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_customers` (
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

            TB_PREF . "fa_crm_contacts" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_contacts` (
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

            TB_PREF . "fa_crm_opportunities" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_opportunities` (
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

            TB_PREF . "fa_crm_communications" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_communications` (
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

            TB_PREF . "fa_crm_customer_types" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_customer_types` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(50) NOT NULL,
                    `description` VARCHAR(255) DEFAULT NULL,
                    `inactive` TINYINT(1) DEFAULT 0,
                    `sort_order` INT(11) DEFAULT 0,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            TB_PREF . "fa_crm_territories" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_territories` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(50) NOT NULL,
                    `description` VARCHAR(255) DEFAULT NULL,
                    `region` VARCHAR(50) DEFAULT NULL,
                    `inactive` TINYINT(1) DEFAULT 0,
                    `sort_order` INT(11) DEFAULT 0,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

            TB_PREF . "fa_crm_activity_log" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_activity_log` (
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

            TB_PREF . "fa_crm_leads" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_leads` (
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

            TB_PREF . "fa_crm_contact_accounts" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_contact_accounts` (
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

            TB_PREF . "fa_crm_realms" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_realms` (
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

            TB_PREF . "fa_crm_quotes" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_quotes` (
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

            TB_PREF . "fa_crm_quote_items" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_crm_quote_items` (
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

        foreach ($tables as $table_name => $sql) {
            db_query($sql, "Could not create CRM table: $table_name");
        }
    }

    /**
     * Ensure initial data
     */
    private function ensure_crm_initial_data() {
        $sql = "INSERT IGNORE INTO " . TB_PREF . "fa_crm_customer_types
            (name, description, sort_order) VALUES
            ('Prospect', 'Potential new customer', 1),
            ('Active', 'Current active customer', 2),
            ('Inactive', 'Former customer', 3),
            ('VIP', 'High-value customer', 4),
            ('Partner', 'Business partner', 5)";
        db_query($sql, "Could not insert customer types");

        $sql = "INSERT IGNORE INTO " . TB_PREF . "fa_crm_territories
            (name, description, region, sort_order) VALUES
            ('North', 'Northern region', 'North', 1),
            ('South', 'Southern region', 'South', 2),
            ('East', 'Eastern region', 'East', 3),
            ('West', 'Western region', 'West', 4),
            ('Central', 'Central region', 'Central', 5)";
        db_query($sql, "Could not insert territories");
    }

    /**
     * Handle void operations
     */
    function db_prevoid($trans_type, $trans_no) {
        // Handle voiding if CRM tracks financial transactions
    }
}
?>
