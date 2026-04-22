-- ============================================================================
-- FA_CRM Module Installation SQL
-- ============================================================================
-- This file contains the table creation statements for the FA_CRM module
-- Run this file to install the module manually, or use hooks.php for auto-install
-- ============================================================================

-- CRM Customers table (extends FA debtors)
CREATE TABLE IF NOT EXISTS `fa_crm_customers` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Contacts table
CREATE TABLE IF NOT EXISTS `fa_crm_contacts` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Opportunities table
CREATE TABLE IF NOT EXISTS `fa_crm_opportunities` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `opportunity_name` VARCHAR(100) NOT NULL,
    `debtor_no` VARCHAR(20) DEFAULT NULL,
    `contact_id` INT(11) DEFAULT NULL,
    `sales_person` VARCHAR(100) DEFAULT NULL,
    `opportunity_type` VARCHAR(50) DEFAULT NULL,
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
    `campaign_id` INT(11) DEFAULT NULL,
    `inactive` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_debtor_no` (`debtor_no`),
    KEY `idx_status` (`status`),
    KEY `idx_stage` (`stage`),
    KEY `idx_expected_close` (`expected_close_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Communications table
CREATE TABLE IF NOT EXISTS `fa_crm_communications` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Customer Types
CREATE TABLE IF NOT EXISTS `fa_crm_customer_types` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `inactive` TINYINT(1) DEFAULT 0,
    `sort_order` INT(11) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Territories
CREATE TABLE IF NOT EXISTS `fa_crm_territories` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `region` VARCHAR(50) DEFAULT NULL,
    `inactive` TINYINT(1) DEFAULT 0,
    `sort_order` INT(11) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Activity Log
CREATE TABLE IF NOT EXISTS `fa_crm_activity_log` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Insert Initial Data
-- ============================================================================

-- Insert default customer types
INSERT INTO `fa_crm_customer_types` (`name`, `description`, `sort_order`) VALUES
('Prospect', 'Potential new customer', 1),
('Active', 'Current active customer', 2),
('Inactive', 'Former customer', 3),
('VIP', 'High-value customer', 4),
('Partner', 'Business partner', 5);

-- Insert default territories
INSERT INTO `fa_crm_territories` (`name`, `description`, `region`, `sort_order`) VALUES
('North', 'Northern region', 'North', 1),
('South', 'Southern region', 'South', 2),
('East', 'Eastern region', 'East', 3),
('West', 'Western region', 'West', 4),
('Central', 'Central region', 'Central', 5);
