<?php
/**
 * WebERP-Style CRM Module for FrontAccounting
 *
 * This module implements advanced CRM features based on WebERP's comprehensive
 * customer relationship management capabilities, including:
 * - Enhanced customer profiling with multiple contacts and branches
 * - Customer types and categorization
 * - Geographic mapping and analysis
 * - EDI integration capabilities
 * - Advanced customer analytics
 * - Sales pipeline and opportunity management
 */

namespace FA\Modules;

use FA\Modules\ModuleInterface;

/**
 * CRM Module Class
 *
 * Implements WebERP-style CRM features for FrontAccounting
 */
class CRMModule implements ModuleInterface
{
    /**
     * Module name
     */
    public function getName(): string
    {
        return 'CRM';
    }

    /**
     * Module version
     */
    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * Module description
     */
    public function getDescription(): string
    {
        return 'Advanced Customer Relationship Management with WebERP-style features';
    }

    /**
     * Module author
     */
    public function getAuthor(): string
    {
        return 'FrontAccounting Development Team';
    }

    /**
     * Minimum application version required
     */
    public function getMinimumAppVersion(): string
    {
        return '2.4.0';
    }

    /**
     * Maximum application version supported
     */
    public function getMaximumAppVersion(): ?string
    {
        return null; // No upper limit
    }

    /**
     * Module dependencies
     */
    public function getDependencies(): array
    {
        return ['Core'];
    }

    /**
     * Get menu items this module adds
     */
    public function getMenuItems(): array
    {
        return [
            [
                'title' => 'CRM Dashboard',
                'url' => '/modules/CRM/pages/dashboard.php',
                'access' => 'SA_CUSTOMER',
                'parent' => 'Sales',
                'order' => 10
            ],
            [
                'title' => 'Enhanced Customers',
                'url' => '/sales/manage/enhanced_customers.php',
                'access' => 'SA_CUSTOMER',
                'parent' => 'Sales',
                'order' => 15
            ],
            [
                'title' => 'CRM Meetings',
                'url' => '/modules/CRM/pages/meetings.php',
                'access' => 'SA_CUSTOMER',
                'parent' => 'Sales',
                'order' => 20
            ],
            [
                'title' => 'Customer Types',
                'url' => '/modules/CRM/pages/customer_types.php',
                'access' => 'CRM_CUSTOMER_TYPES',
                'parent' => 'Setup',
                'order' => 20
            ],
            [
                'title' => 'Sales Territories',
                'url' => '/modules/CRM/pages/territories.php',
                'access' => 'CRM_TERRITORIES',
                'parent' => 'Setup',
                'order' => 25
            ]
        ];
    }

    /**
     * Get permissions this module requires
     */
    public function getPermissions(): array
    {
        return [
            'CRM_CUSTOMER_TYPES' => [
                'name' => 'Manage Customer Types',
                'description' => 'Allow user to create and manage customer types',
                'section' => 'CRM'
            ],
            'CRM_TERRITORIES' => [
                'name' => 'Manage Sales Territories',
                'description' => 'Allow user to create and manage sales territories',
                'section' => 'CRM'
            ],
            'CRM_OPPORTUNITIES' => [
                'name' => 'Manage Sales Opportunities',
                'description' => 'Allow user to create and manage sales opportunities',
                'section' => 'CRM'
            ],
            'CRM_ANALYTICS' => [
                'name' => 'View Customer Analytics',
                'description' => 'Allow user to view customer analytics and reports',
                'section' => 'CRM'
            ]
        ];
    }

    /**
     * Activate the module
     */
    public function activate(): bool
    {
        try {
            // Register CRM menu items
            $this->registerMenus();

            // Register event listeners
            $this->registerEventListeners();

            // Register hooks
            $this->registerHooks();

            return true;
        } catch (\Exception $e) {
            error_log("CRM Module activation failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deactivate the module
     */
    public function deactivate(): bool
    {
        try {
            // Unregister event listeners and hooks would go here
            return true;
        } catch (\Exception $e) {
            error_log("CRM Module deactivation failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Install the module
     */
    public function install(): bool
    {
        try {
            // Create CRM database tables
            $this->createDatabaseTables();

            // Create default CRM data
            $this->createDefaultData();

            return true;
        } catch (\Exception $e) {
            error_log("CRM Module installation failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Uninstall the module
     */
    public function uninstall(): bool
    {
        try {
            // Remove CRM database tables
            $this->dropDatabaseTables();

            return true;
        } catch (\Exception $e) {
            error_log("CRM Module uninstallation failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Upgrade the module
     */
    public function upgrade(string $oldVersion, string $newVersion): bool
    {
        // For now, just return true
        // In a real implementation, you'd handle version-specific upgrades
        return true;
    }

    /**
     * Register CRM menu items
     */
    private function registerMenus(): void
    {
        // Add CRM menu to main navigation
        add_access_extensions(array(
            'SA_CUSTOMER' => array(
                'CRM_CUSTOMER_TYPES' => _('Customer Types'),
                'CRM_CUSTOMER_SEGMENTS' => _('Customer Segments'),
                'CRM_CONTACT_ROLES' => _('Contact Roles'),
                'CRM_TERRITORIES' => _('Sales Territories'),
                'CRM_OPPORTUNITIES' => _('Sales Opportunities'),
                'CRM_CAMPAIGNS' => _('Marketing Campaigns'),
                'CRM_EDI_SETUP' => _('EDI Configuration'),
                'CRM_ANALYTICS' => _('Customer Analytics'),
            )
        ));
    }

    /**
     * Register event listeners
     */
    private function registerEventListeners(): void
    {
        // Event listeners would be registered here
        // This requires the event system to be in place
    }

    /**
     * Register hooks
     */
    private function registerHooks(): void
    {
        // Register pre/post hooks for customer operations
        // This requires the hook system to be in place
    }

    /**
     * Create CRM database tables
     */
    private function createDatabaseTables(): void
    {
        // CRM Customers table (separate from debtors_master)
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            debtor_no VARCHAR(10) NOT NULL UNIQUE,
            customer_type_id INT DEFAULT NULL,
            customer_segment_id INT DEFAULT NULL,
            territory_id INT DEFAULT NULL,
            customer_since DATE DEFAULT NULL,
            website VARCHAR(100) DEFAULT NULL,
            industry VARCHAR(50) DEFAULT NULL,
            employee_count INT DEFAULT NULL,
            annual_revenue DECIMAL(15,2) DEFAULT NULL,
            parent_company VARCHAR(100) DEFAULT NULL,
            latitude DECIMAL(10,8) DEFAULT NULL,
            longitude DECIMAL(11,8) DEFAULT NULL,
            edi_enabled TINYINT DEFAULT 0,
            marketing_opt_out TINYINT DEFAULT 0,
            preferred_contact_method ENUM('email','phone','mail') DEFAULT 'email',
            last_contact_date DATE DEFAULT NULL,
            next_followup_date DATE DEFAULT NULL,
            account_manager VARCHAR(50) DEFAULT NULL,
            credit_rating ENUM('excellent','good','fair','poor') DEFAULT 'good',
            payment_reliability DECIMAL(5,2) DEFAULT 100.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (debtor_no) REFERENCES " . TB_PREF . "debtors_master(debtor_no) ON DELETE CASCADE,
            FOREIGN KEY (customer_type_id) REFERENCES " . TB_PREF . "crm_customer_types(id),
            FOREIGN KEY (customer_segment_id) REFERENCES " . TB_PREF . "crm_customer_segments(id),
            FOREIGN KEY (territory_id) REFERENCES " . TB_PREF . "crm_territories(id)
        )";
        db_query($sql, "Could not create crm_customers table");

        // Customer Types table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_customer_types (
            id INT AUTO_INCREMENT PRIMARY KEY,
            type_name VARCHAR(50) NOT NULL UNIQUE,
            description TEXT,
            discount_percent DECIMAL(5,2) DEFAULT 0,
            credit_limit DECIMAL(12,2) DEFAULT 0,
            payment_terms VARCHAR(10),
            inactive TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_customer_types table");

        // Customer Segments table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_customer_segments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            segment_name VARCHAR(50) NOT NULL UNIQUE,
            description TEXT,
            criteria TEXT,
            inactive TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_customer_segments table");

        // Contact Roles table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_contact_roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_name VARCHAR(50) NOT NULL UNIQUE,
            description TEXT,
            default_phone VARCHAR(30),
            default_email VARCHAR(100),
            inactive TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_contact_roles table");

        // Customer Contacts table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            debtor_no VARCHAR(10) NOT NULL,
            contact_role_id INT DEFAULT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            title VARCHAR(50),
            department VARCHAR(50),
            phone VARCHAR(30),
            mobile VARCHAR(30),
            email VARCHAR(100),
            address TEXT,
            notes TEXT,
            is_primary TINYINT DEFAULT 0,
            inactive TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (debtor_no) REFERENCES " . TB_PREF . "debtors_master(debtor_no) ON DELETE CASCADE,
            FOREIGN KEY (contact_role_id) REFERENCES " . TB_PREF . "crm_contact_roles(id)
        )";
        db_query($sql, "Could not create crm_contacts table");

        // Sales Territories table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_territories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            territory_name VARCHAR(50) NOT NULL UNIQUE,
            description TEXT,
            sales_person VARCHAR(50),
            region VARCHAR(50),
            inactive TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_territories table");

        // Sales Opportunities table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_opportunities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            opportunity_name VARCHAR(100) NOT NULL,
            debtor_no VARCHAR(10),
            contact_id INT,
            sales_person VARCHAR(50),
            opportunity_type VARCHAR(20),
            status VARCHAR(20) DEFAULT 'prospect',
            estimated_value DECIMAL(12,2),
            probability DECIMAL(5,2),
            expected_close_date DATE,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (debtor_no) REFERENCES " . TB_PREF . "debtors_master(debtor_no),
            FOREIGN KEY (contact_id) REFERENCES " . TB_PREF . "crm_contacts(id)
        )";
        db_query($sql, "Could not create crm_opportunities table");

        // Marketing Campaigns table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_campaigns (
            id INT AUTO_INCREMENT PRIMARY KEY,
            campaign_name VARCHAR(100) NOT NULL,
            campaign_type VARCHAR(20),
            start_date DATE,
            end_date DATE,
            budget DECIMAL(12,2),
            target_audience TEXT,
            status VARCHAR(20) DEFAULT 'planned',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_campaigns table");

        // EDI Configuration table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_edi_config (
            id INT AUTO_INCREMENT PRIMARY KEY,
            debtor_no VARCHAR(10),
            edi_type VARCHAR(20),
            edi_code VARCHAR(20),
            ftp_host VARCHAR(100),
            ftp_username VARCHAR(50),
            ftp_password VARCHAR(50),
            email_address VARCHAR(100),
            active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (debtor_no) REFERENCES " . TB_PREF . "debtors_master(debtor_no)
        )";
        db_query($sql, "Could not create crm_edi_config table");

        // Communications table for tracking all customer communications
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_communications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            debtor_no VARCHAR(10),
            contact_id INT,
            communication_type ENUM('call','meeting','email','sms','note','letter') NOT NULL,
            direction ENUM('inbound','outbound','internal') DEFAULT 'outbound',
            subject VARCHAR(255),
            message TEXT,
            email_from VARCHAR(100),
            email_to VARCHAR(100),
            phone_number VARCHAR(30),
            duration_minutes INT,
            status ENUM('scheduled','completed','cancelled','failed') DEFAULT 'completed',
            scheduled_date DATETIME,
            completed_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            assigned_to VARCHAR(50),
            priority ENUM('low','medium','high','urgent') DEFAULT 'medium',
            follow_up_required TINYINT DEFAULT 0,
            follow_up_date DATE,
            notes TEXT,
            email_message_id VARCHAR(255), -- For email threading
            attachment_path VARCHAR(255),
            created_by VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (debtor_no) REFERENCES " . TB_PREF . "debtors_master(debtor_no),
            FOREIGN KEY (contact_id) REFERENCES " . TB_PREF . "crm_contacts(id)
        )";
        db_query($sql, "Could not create crm_communications table");

        // Email Import Configuration table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_email_accounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            account_name VARCHAR(100) NOT NULL,
            email_address VARCHAR(100) NOT NULL UNIQUE,
            smtp_host VARCHAR(100),
            smtp_port INT DEFAULT 587,
            smtp_username VARCHAR(100),
            smtp_password VARCHAR(255),
            smtp_encryption ENUM('none','ssl','tls') DEFAULT 'tls',
            imap_host VARCHAR(100),
            imap_port INT DEFAULT 993,
            imap_username VARCHAR(100),
            imap_password VARCHAR(255),
            imap_encryption ENUM('none','ssl','tls') DEFAULT 'ssl',
            last_sync TIMESTAMP,
            active TINYINT DEFAULT 1,
            auto_import TINYINT DEFAULT 0,
            import_frequency_minutes INT DEFAULT 15,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_email_accounts table");

        // Customer Analytics table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_customer_analytics (
            id INT AUTO_INCREMENT PRIMARY KEY,
            debtor_no VARCHAR(10),
            period_start DATE,
            period_end DATE,
            total_sales DECIMAL(12,2) DEFAULT 0,
            total_payments DECIMAL(12,2) DEFAULT 0,
            outstanding_balance DECIMAL(12,2) DEFAULT 0,
            payment_days_avg DECIMAL(6,2) DEFAULT 0,
            order_frequency DECIMAL(6,2) DEFAULT 0,
            customer_lifetime_value DECIMAL(12,2) DEFAULT 0,
            last_communication_date DATE,
            communication_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (debtor_no) REFERENCES " . TB_PREF . "debtors_master(debtor_no)
        )";
        db_query($sql, "Could not create crm_customer_analytics table");

        // Meeting Rooms/Resources table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_meeting_rooms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            room_name VARCHAR(100) NOT NULL,
            room_type ENUM('physical','virtual','phone') DEFAULT 'physical',
            location VARCHAR(255),
            capacity INT DEFAULT 0,
            equipment TEXT,
            phone_number VARCHAR(30),
            conference_url VARCHAR(255),
            active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_meeting_rooms table");

        // Meetings table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_meetings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            meeting_name VARCHAR(255) NOT NULL,
            meeting_type ENUM('meeting','call','presentation','training','other') DEFAULT 'meeting',
            description TEXT,
            start_date DATETIME NOT NULL,
            end_date DATETIME NOT NULL,
            duration_minutes INT,
            time_zone VARCHAR(50) DEFAULT 'UTC',
            location_type ENUM('physical','virtual','phone') DEFAULT 'physical',
            room_id INT,
            custom_location VARCHAR(255),
            phone_number VARCHAR(30),
            conference_url VARCHAR(255),
            meeting_url VARCHAR(255),
            dial_in_number VARCHAR(30),
            access_code VARCHAR(20),
            host_pin VARCHAR(20),

            -- Associations
            debtor_no VARCHAR(10), -- Account/Customer
            opportunity_id INT,
            project_id INT,
            quote_id INT,
            campaign_id INT,

            -- Meeting details
            agenda TEXT,
            preparation_notes TEXT,
            meeting_outcome TEXT,
            follow_up_actions TEXT,

            -- Status and ownership
            status ENUM('planned','confirmed','in_progress','completed','cancelled','postponed') DEFAULT 'planned',
            priority ENUM('low','normal','high','urgent') DEFAULT 'normal',
            assigned_to VARCHAR(50), -- Meeting owner/organizer
            created_by VARCHAR(50),

            -- Recurrence
            is_recurring TINYINT DEFAULT 0,
            recurrence_id INT,

            -- Reminders
            reminder_minutes_before INT DEFAULT 15,
            reminder_sent TINYINT DEFAULT 0,

            -- ICS and external integration
            ics_uid VARCHAR(255) UNIQUE,
            external_id VARCHAR(100),

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            FOREIGN KEY (debtor_no) REFERENCES " . TB_PREF . "debtors_master(debtor_no),
            FOREIGN KEY (opportunity_id) REFERENCES " . TB_PREF . "crm_opportunities(id),
            FOREIGN KEY (room_id) REFERENCES " . TB_PREF . "crm_meeting_rooms(id),
            FOREIGN KEY (campaign_id) REFERENCES " . TB_PREF . "crm_campaigns(id)
        )";
        db_query($sql, "Could not create crm_meetings table");

        // Meeting Attendees table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_meeting_attendees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            meeting_id INT NOT NULL,
            attendee_type ENUM('employee','contact','external') DEFAULT 'contact',
            employee_id INT, -- FA users table reference
            contact_id INT, -- CRM contacts table reference
            external_name VARCHAR(100),
            external_email VARCHAR(100),
            external_phone VARCHAR(30),
            attendee_role ENUM('organizer','required','optional','resource') DEFAULT 'required',
            response_status ENUM('pending','accepted','declined','tentative') DEFAULT 'pending',
            response_date TIMESTAMP NULL,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (meeting_id) REFERENCES " . TB_PREF . "crm_meetings(id) ON DELETE CASCADE,
            FOREIGN KEY (contact_id) REFERENCES " . TB_PREF . "crm_contacts(id)
        )";
        db_query($sql, "Could not create crm_meeting_attendees table");

        // Meeting Recurrences table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_meeting_recurrences (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pattern ENUM('daily','weekly','monthly','yearly') NOT NULL,
            interval_count INT DEFAULT 1, -- Every X days/weeks/months/years
            days_of_week VARCHAR(20), -- For weekly: '1,3,5' (Monday, Wednesday, Friday)
            day_of_month INT, -- For monthly: 15th of month
            month_of_year INT, -- For yearly: 6 (June)
            end_type ENUM('never','after_occurrences','on_date') DEFAULT 'never',
            occurrences_count INT, -- End after X occurrences
            end_date DATE, -- End on specific date
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        db_query($sql, "Could not create crm_meeting_recurrences table");

        // Meeting Attachments table
        $sql = "CREATE TABLE IF NOT EXISTS " . TB_PREF . "crm_meeting_attachments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            meeting_id INT NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            file_size INT,
            mime_type VARCHAR(100),
            uploaded_by VARCHAR(50),
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (meeting_id) REFERENCES " . TB_PREF . "crm_meetings(id) ON DELETE CASCADE
        )";
        db_query($sql, "Could not create crm_meeting_attachments table");
    }

    /**
     * Drop CRM database tables
     */
    private function dropDatabaseTables(): void
    {
        $tables = [
            'crm_meeting_attachments',
            'crm_meeting_attendees',
            'crm_meeting_recurrences',
            'crm_meetings',
            'crm_meeting_rooms',
            'crm_customer_analytics',
            'crm_email_accounts',
            'crm_communications',
            'crm_edi_config',
            'crm_campaigns',
            'crm_opportunities',
            'crm_contacts',
            'crm_territories',
            'crm_contact_roles',
            'crm_customer_segments',
            'crm_customer_types',
            'crm_customers'
        ];

        foreach ($tables as $table) {
            $sql = "DROP TABLE IF EXISTS " . TB_PREF . $table;
            db_query($sql);
        }
    }
    {
        $tables = [
            'crm_meeting_attachments',
            'crm_meeting_attendees',
            'crm_meeting_recurrences',
            'crm_meetings',
            'crm_meeting_rooms',
            'crm_customer_analytics',
            'crm_email_accounts',
            'crm_communications',
            'crm_edi_config',
            'crm_campaigns',
            'crm_opportunities',
            'crm_contacts',
            'crm_territories',
            'crm_contact_roles',
            'crm_customer_segments',
            'crm_customer_types',
            'crm_customers'
        ];

        foreach ($tables as $table) {
            $sql = "DROP TABLE IF EXISTS " . TB_PREF . $table;
            db_query($sql);
        }
    }
}