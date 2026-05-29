# FA_CRM Technical Architecture

## Document Information
- **Module**: FA_CRM (Customer Relationship Management)
- **Version**: 1.0.0
- **Date**: 2024-04-26
- **Status**: Implemented
- **Author**: KSFII Development Team

## 1. Architecture Overview

### 1.1 Design Principles
The FA_CRM module follows these architectural principles:

1. **Modularity**: Clean separation between UI, business logic, and data layers
2. **Extensibility**: Hooks and events for integration with other modules
3. **Compatibility**: WebERP-style functions for FA integration
4. **Type Safety**: PHP 8.0+ features with type declarations

### 1.2 Technology Stack
- **PHP**: 8.0+ with strict typing
- **Database**: MySQL 5.7+ / MariaDB 10.0+
- **Frontend**: Bootstrap 5.x (via FA)
- **Integration**: PSR-14 Event Dispatcher

## 2. Directory Structure

```
ksf_CRM/
├── FA_CRM_Module.php      # Module registration & hooks
├── crm.php               # Main module entry
├── hooks.php             # Install/activate/deactivate hooks
├── README.md            # Module documentation
├── includes/
│   ├── crm_db.inc       # Database functions
│   ├── crm_ui.inc       # UI components
│   └── EmailImportService.php  # Email import service
├── pages/
│   ├── dashboard.php    # CRM dashboard
│   ├── customers.php    # Customer management
│   ├── customer_types.php  # Customer type CRUD
│   ├── territories.php  # Territory management
│   ├── contacts.php     # Contact management
│   ├── opportunities.php  # Opportunity pipeline
│   ├── communications.php   # Communication log
│   ├── meetings.php    # Meeting management
│   ├── calendar.php    # Calendar view
│   ├── email_accounts.php  # Email config
│   ├── meeting_rooms.php  # Meeting room CRUD
│   ├── reports.php     # Analytics reports
│   ├── settings.php    # Module settings
│   ├── leads.php      # Lead management
│   ├── convert_lead.php   # Lead conversion
│   ├── webtolead.php # Web form processor
│   ├── quotes.php     # Quote management
│   └── realms.php    # Realm management
├── sql/
│   └── install.sql    # Database schema
├── src/                # Additional source files
├── tests/              # Unit and integration tests
└── doc/
    └── ProjectDocuments/
        └── ProjectDcs/
            ├── Architecture.md
            ├── Functional Requirements.md
            ├── Test Plan.md
            └── UAT Plan.md
```

## 3. Module Components

### 3.1 FA_CRM_Module.php
Main module class providing:
- Module metadata
- Permission definitions
- Menu items
- Lifecycle hooks (install, activate, deactivate, uninstall)

**Key Functions**:
```php
function fa_crm_get_module_info()    // Returns module metadata
function fa_crm_install()           // Creates database tables
function fa_crm_activate()         // Activates module
function fa_crm_deactivate()       // Deactivates module
function fa_crm_uninstall()       // Cleanup on uninstall
function fa_crm_get_menu_items()   // Returns navigation menu
```

### 3.2 hooks.php
Handles module lifecycle operations:
- Database installation
- Permission registration
- Menu registration
- Hook registration

### 3.3 crm_db.inc
Database abstraction layer with functions for:
- Customer CRUD operations
- Contact management
- Opportunity management
- Communication logging
- Territory management
- Customer type management
- Email account management
- Analytics calculations

### 3.4 crm_ui.inc
UI helper functions:
- Form input generators
- Display components
- Dashboard widgets
- Navigation menus
- Selector helpers

### 3.5 EmailImportService.php
Class for email import functionality:
- IMAP connection
- Email parsing
- ICS attachment processing
- Meeting creation

## 4. Database Schema

### 4.1 Core Tables

#### fa_crm_customers
```sql
CREATE TABLE fa_crm_customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debtor_no VARCHAR(20) NOT NULL UNIQUE,
    customer_type_id INT,
    customer_segment_id INT,
    territory_id INT,
    customer_since DATE,
    website VARCHAR(255),
    industry VARCHAR(100),
    employee_count INT,
    annual_revenue DECIMAL(15,2),
    parent_company VARCHAR(100),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    edi_enabled TINYINT(1) DEFAULT 0,
    marketing_opt_out TINYINT(1) DEFAULT 0,
    preferred_contact_method VARCHAR(20) DEFAULT 'email',
    last_contact_date DATETIME,
    next_followup_date DATETIME,
    account_manager VARCHAR(100),
    credit_rating VARCHAR(20) DEFAULT 'good',
    payment_reliability DECIMAL(5,2) DEFAULT 100.00,
    inactive TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_debtor_no (debtor_no),
    INDEX idx_customer_type (customer_type_id),
    INDEX idx_territory (territory_id)
);
```

#### fa_crm_contacts
```sql
CREATE TABLE fa_crm_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debtor_no VARCHAR(20) NOT NULL,
    contact_role_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    title VARCHAR(50),
    department VARCHAR(50),
    phone VARCHAR(20),
    mobile VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    notes TEXT,
    is_primary TINYINT(1) DEFAULT 0,
    inactive TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_debtor_no (debtor_no),
    INDEX idx_is_primary (is_primary)
);
```

#### fa_crm_opportunities
```sql
CREATE TABLE fa_crm_opportunities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    opportunity_name VARCHAR(100) NOT NULL,
    debtor_no VARCHAR(20),
    contact_id INT,
    sales_person VARCHAR(100),
    opportunity_type VARCHAR(50),
    status VARCHAR(20) DEFAULT 'prospecting',
    stage VARCHAR(30) DEFAULT 'qualification',
    source VARCHAR(50),
    estimated_value DECIMAL(15,2),
    probability DECIMAL(5,2) DEFAULT 0,
    expected_close_date DATE,
    actual_close_date DATE,
    lost_reason TEXT,
    won_notes TEXT,
    notes TEXT,
    assigned_to VARCHAR(100),
    campaign_id INT,
    inactive TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_debtor_no (debtor_no),
    INDEX idx_status (status),
    INDEX idx_stage (stage)
);
```

#### fa_crm_communications
```sql
CREATE TABLE fa_crm_communications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debtor_no VARCHAR(20),
    contact_id INT,
    opportunity_id INT,
    communication_type VARCHAR(20) NOT NULL,
    direction VARCHAR(10) DEFAULT 'outbound',
    subject VARCHAR(255),
    message TEXT,
    email_from VARCHAR(100),
    email_to VARCHAR(100),
    phone_number VARCHAR(20),
    duration_minutes INT,
    status VARCHAR(20) DEFAULT 'completed',
    scheduled_date DATETIME,
    completed_date DATETIME,
    assigned_to VARCHAR(100),
    priority VARCHAR(10) DEFAULT 'medium',
    follow_up_required TINYINT(1) DEFAULT 0,
    follow_up_date DATETIME,
    notes TEXT,
    email_message_id VARCHAR(255),
    attachment_path VARCHAR(500),
    created_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_debtor_no (debtor_no),
    INDEX idx_follow_up (follow_up_required, follow_up_date)
);
```

### 4.2 Reference Tables

#### fa_crm_customer_types
```sql
CREATE TABLE fa_crm_customer_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    inactive TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0
);
```

#### fa_crm_territories
```sql
CREATE TABLE fa_crm_territories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    region VARCHAR(50),
    inactive TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0
);
```

### 4.3 Activity Logging

#### fa_crm_activity_log
```sql
CREATE TABLE fa_crm_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    activity_type VARCHAR(30) NOT NULL,
    entity_type VARCHAR(30) NOT NULL,
    entity_id INT NOT NULL,
    debtor_no VARCHAR(20),
    user_id VARCHAR(100),
    action VARCHAR(50) NOT NULL,
    details TEXT,
    old_values TEXT,
    new_values TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_debtor_no (debtor_no)
);
```

## 5. Integration Architecture

### 5.1 FrontAccounting Integration
The module integrates with FA through:

1. **Hooks System**: Using FA's hook mechanism
```php
add_action('customer_created', $callback);
add_action('customer_updated', $callback);
add_hook('crm.event', $callback);
```

2. **Database Table Prefix**: Using `TB_PREF` constant
```php
$sql = "SELECT * FROM " . TB_PREF . "fa_crm_customers";
```

3. **Permission System**: Using FA's permission constants
```php
define('CRM_VIEW_CUSTOMER', 'CRM_VIEW_CUSTOMER');
define('CRM_MANAGE_CUSTOMER', 'CRM_MANAGE_CUSTOMER');
```

4. **UI Components**: Using FA's form helpers
```php
combo_input($name, $selected_id, $sql, $id_field, $name_field);
```

### 5.2 Event System
PSR-14 compatible event system for:
- Cross-module communication
- Real-time notifications
- Workflow automation

### 5.3 Email Import Pipeline
```
IMAP Server → EmailImportService → Contact Matching → Communication Record
                                                              ↓
                                                   ICS Processing (optional)
                                                              ↓
                                                      Meeting Creation
```

## 6. Security Architecture

### 6.1 Input Validation
- All user inputs validated before database operations
- SQL injection prevention via `db_escape()`
- Type casting for numeric inputs

### 6.2 Output Escaping
- HTML output escaped via `htmlspecialchars()`
- JavaScript sanitization for dynamic content

### 6.3 Access Control
- Permission checks on all CRUD operations
- Role-based menu visibility
- Audit logging for sensitive operations

## 7. Extension Points

### 7.1 Custom Hooks
Modules can register hooks for:
- Customer lifecycle events
- Communication events
- Opportunity status changes

### 7.2 Custom Fields
The schema supports:
- Notes fields (TEXT) for custom data
- JSON columns for flexible attributes (future)

### 7.3 Email Import Plugins
The EmailImportService can be extended to:
- Process additional attachment types
- Integrate with external systems
- Custom matching rules

## 8. Performance Considerations

### 8.1 Database Indexes
Key indexes on:
- `debtor_no` - Customer lookups
- `status` - Pipeline filtering
- `follow_up_date` - Follow-up queries
- `created_at` - Date range queries

### 8.2 Query Optimization
- Pagination for large datasets
- Efficient JOINs with proper indexes
- Prepared statements for repeated queries

### 8.3 Caching (Future)
Potential caching for:
- Customer type lists
- Territory lists
- Dashboard statistics

## 9. API Design

### 9.1 Service Layer Pattern
```php
class CRMService {
    // Customer operations
    public function createCRMCustomer(array $data): CRMCustomer
    public function getCRMCustomer(string $debtorNo): CRMCustomer
    
    // Contact operations
    public function createContact(array $data): CRMContact
    public function getContactsByCustomer(string $debtorNo): array
    
    // Opportunity operations
    public function createOpportunity(array $data): CRMOpportunity
    public function getSalesPipelineSummary(): array
}
```

### 9.2 Factory Pattern
```php
class CRMEntityFactory {
    public static function createCustomer(array $data): CRMCustomer
    public static function createContact(array $data): CRMContact
}
```

### 9.3 Repository Pattern
```php
class CRMRepository {
    public function findCustomerById(string $id): ?CRMCustomer
    public function findContactsByCustomer(string $debtorNo): array
}
```

## 10. Error Handling

### 10.1 Exception Hierarchy
```php
class CRMException extends Exception
class CRMDatabaseException extends CRMException
class CRMCustomerNotFoundException extends CRMException
class CRMValidationException extends CRMException
```

### 10.2 Error Logging
- Database errors logged with query details
- Email import errors logged separately
- Activity log for audit trail

## 11. Testing Strategy

### 11.1 Unit Tests
- Database function tests
- UI component tests
- Email import service tests

### 11.2 Integration Tests
- FA module integration
- Database operations
- Event dispatching

### 11.3 Test Coverage
- Core CRUD operations
- Analytics calculations
- Email import scenarios

## 12. Deployment

### 12.1 Installation Process
1. Copy module files to FA modules directory
2. Install via FA module manager
3. Database tables created automatically
4. Permissions assigned to admin
5. Menu items registered

### 12.2 Upgrade Process
1. Backup database
2. Deactivate module
3. Replace files
4. Activate module
5. Run migration scripts (if any)

### 12.3 Uninstall Process
1. Deactivate module
2. Optionally remove data
3. Delete module files

---
*Document Version: 1.0.0*
*Last Updated: 2024-04-26*
