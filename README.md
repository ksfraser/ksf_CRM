# FrontAccounting WebERP-Style CRM Module

Advanced Customer Relationship Management system based on WebERP's comprehensive CRM capabilities.

## Status

✅ **IMPLEMENTED** - Complete CRM module with modern PHP architecture.

- Service-oriented architecture with dependency injection
- PSR-4 autoloading and modern PHP 8.0+ features
- Event-driven communication with PSR-14 event dispatcher
- Comprehensive entity models with type safety
- Custom exception hierarchy for error handling
- Database abstraction with Doctrine DBAL
- Full CRUD operations for customers, contacts, opportunities, and communications
- Analytics and reporting capabilities
- Integration with existing FA services (Sales, EDI, etc.)
- Unit tests and validation
- Ready for production deployment

## Overview

This CRM module transforms FrontAccounting's basic customer management into a full-featured CRM system with modern PHP architecture:

- **Service-Oriented Architecture**: Clean separation of concerns with dedicated service classes
- **Event-Driven Design**: PSR-14 compatible event system for module communication
- **Entity Models**: Strongly-typed entity classes with business logic
- **Exception Hierarchy**: Custom exceptions for comprehensive error handling
- **Database Abstraction**: Doctrine DBAL integration for complex queries
- **Dependency Injection**: PSR-11 container support for testability
- **Type Safety**: Full PHP 8.0+ type declarations and return types

## Architecture

### Core Components

#### CRMService.php
Main service class providing CRM business logic:
- Customer profile management with enhanced fields
- Contact management with roles and communication tracking
- Sales opportunity pipeline management
- Communication logging and follow-up tracking
- Customer analytics and reporting
- Integration with FA core services

#### Entities.php
Entity classes representing CRM domain objects:
- `CRMCustomer`: Enhanced customer profiles with industry, territory, analytics
- `CRMContact`: Contact management with roles and communication preferences
- `CRMOpportunity`: Sales opportunity tracking with pipeline management
- `CRMCommunication`: Communication logging with multiple types and follow-ups

#### Events.php
PSR-14 compatible event classes for CRM operations:
- Customer lifecycle events (created, updated, deleted)
- Contact management events
- Opportunity status changes and pipeline updates
- Communication tracking and follow-up events

#### CRMException.php
Custom exception hierarchy for CRM-specific errors:
- Validation exceptions with detailed error information
- Not found exceptions for missing entities
- Permission and configuration exceptions
- Database and integration error handling

## Features

### Customer Management
- Enhanced customer profiles with industry, size, website, geography
- Customer types and segmentation for targeted marketing
- Sales territories and territory management
- Account managers and relationship tracking
- EDI configuration for B2B customers
- Marketing opt-out management
- Credit rating and payment reliability tracking

### Contact Management
- Multiple contacts per customer with role-based access
- Communication preferences and history tracking
- Contact-specific notes and follow-up scheduling
- Primary contact designation and hierarchy

### Sales Pipeline & Opportunities
- Sales opportunity tracking with probability-based forecasting
- Pipeline management with status transitions
- Sales person assignment and performance tracking
- Weighted value calculations for forecasting
- Opportunity lifecycle management

### Communication Tracking
- Multi-channel communication logging (phone, email, meeting, SMS, notes)
- Inbound/outbound communication tracking
- Follow-up scheduling and reminders
- Communication analytics and reporting
- Attachment support for emails and documents

### Analytics & Reporting
- Customer lifetime value analysis
- Payment reliability and credit scoring
- Sales performance by territory and salesperson
- Customer segmentation and targeting
- Communication effectiveness metrics
- Pipeline forecasting and trend analysis

### Integration Features
- Event-driven integration with other FA modules
- EDI configuration for automated B2B communications
- Sales service integration for order/invoice tracking
- Audit trail and compliance logging
- API-ready architecture for external integrations

## Installation

### Prerequisites
- FrontAccounting 2.4.0 or higher
- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.0+
- Composer for dependency management
- PSR-4 autoloading configured

### Installation Steps

1. **Module Files**
   ```bash
   # Copy CRM module to FA modules directory
   cp -r modules/CRM /path/to/frontaccounting/modules/
   ```

2. **Dependencies**
   ```bash
   # Install required packages via Composer
   composer require doctrine/dbal psr/event-dispatcher psr/log
   ```

3. **Module Registration**
   - Access FA Admin → Setup → Modules
   - Install and activate the CRM module

4. **Database Setup**
   - Module creates CRM tables automatically:
     - `crm_customers` - Enhanced customer profiles
     - `crm_contacts` - Contact management
     - `crm_opportunities` - Sales opportunities
     - `crm_communications` - Communication tracking

5. **Permissions**
   - Assign CRM permissions to users:
     - `SA_CUSTOMER` - Basic customer access
     - `CRM_MANAGE_CUSTOMERS` - Full customer management
     - `CRM_MANAGE_OPPORTUNITIES` - Opportunity management
     - `CRM_VIEW_ANALYTICS` - Analytics access

## Usage

### Service Integration

```php
// Inject CRM service via DI container
$crmService = $container->get(CRMService::class);

// Create enhanced customer profile
$customerData = [
    'debtor_no' => 'CUST001',
    'customer_type_id' => 1,
    'industry' => 'Manufacturing',
    'annual_revenue' => 5000000.00,
    'territory_id' => 2,
    'account_manager' => 'john.doe'
];

$customer = $crmService->createCRMCustomer($customerData);

// Add contact
$contactData = [
    'debtor_no' => 'CUST001',
    'first_name' => 'Jane',
    'last_name' => 'Smith',
    'title' => 'Purchasing Manager',
    'email' => 'jane.smith@customer.com',
    'is_primary' => true
];

$contact = $crmService->createContact($contactData);

// Create sales opportunity
$opportunityData = [
    'opportunity_name' => 'Q4 Equipment Upgrade',
    'debtor_no' => 'CUST001',
    'contact_id' => $contact->getId(),
    'estimated_value' => 150000.00,
    'probability' => 75.0,
    'expected_close_date' => '2024-12-31'
];

$opportunity = $crmService->createOpportunity($opportunityData);

// Record communication
$communicationData = [
    'debtor_no' => 'CUST001',
    'contact_id' => $contact->getId(),
    'communication_type' => 'phone',
    'direction' => 'outbound',
    'subject' => 'Discussed Q4 upgrade opportunity',
    'duration_minutes' => 30,
    'follow_up_required' => true,
    'follow_up_date' => '2024-10-15'
];

$communication = $crmService->recordCommunication($communicationData);
```

### Event Handling

```php
// Listen for CRM events
$eventDispatcher->addListener(CRMCustomerCreatedEvent::class, function(CRMCustomerCreatedEvent $event) {
    $customer = $event->getCustomer();
    // Send welcome email, create EDI config, etc.
});

$eventDispatcher->addListener(CRMOpportunityStatusChangedEvent::class, function(CRMOpportunityStatusChangedEvent $event) {
    if ($event->isWon()) {
        // Trigger order creation workflow
    }
});
```

### Analytics Usage

```php
// Get customer analytics
$analytics = $crmService->getCustomerAnalytics('CUST001');
echo "Lifetime Value: $" . number_format($analytics['lifetime_value'], 2);
echo "Payment Reliability: " . $analytics['payment_reliability'] . "%";

// Get sales pipeline summary
$pipeline = $crmService->getSalesPipelineSummary();
echo "Total Pipeline Value: $" . number_format($pipeline['total_value'], 2);
echo "Weighted Value: $" . number_format($pipeline['weighted_value'], 2);
```

## API Reference

### CRMService Methods

#### Customer Management
- `createCRMCustomer(array $data): CRMCustomer`
- `getCRMCustomer(string $debtorNo): CRMCustomer`
- `updateCRMCustomer(string $debtorNo, array $data): CRMCustomer`
- `deleteCRMCustomer(string $debtorNo): void`

#### Contact Management
- `createContact(array $data): CRMContact`
- `getContact(int $contactId): CRMContact`
- `getContactsByCustomer(string $debtorNo): CRMContact[]`
- `updateContact(int $contactId, array $data): CRMContact`
- `deleteContact(int $contactId): void`

#### Opportunity Management
- `createOpportunity(array $data): CRMOpportunity`
- `getOpportunity(int $opportunityId): CRMOpportunity`
- `getOpportunitiesByCustomer(string $debtorNo): CRMOpportunity[]`
- `updateOpportunity(int $opportunityId, array $data): CRMOpportunity`
- `deleteOpportunity(int $opportunityId): void`

#### Communication Tracking
- `recordCommunication(array $data): CRMCommunication`
- `getCommunication(int $communicationId): CRMCommunication`
- `getCommunicationsByCustomer(string $debtorNo): CRMCommunication[]`
- `updateCommunication(int $communicationId, array $data): CRMCommunication`

#### Analytics & Reporting
- `getCustomerAnalytics(string $debtorNo): array`
- `getSalesPipelineSummary(): array`
- `getTerritoryPerformance(): array`
- `getCommunicationAnalytics(): array`

## Database Schema

### crm_customers
```sql
CREATE TABLE crm_customers (
    debtor_no VARCHAR(20) PRIMARY KEY,
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
    edi_enabled BOOLEAN DEFAULT FALSE,
    marketing_opt_out BOOLEAN DEFAULT FALSE,
    preferred_contact_method VARCHAR(20) DEFAULT 'email',
    last_contact_date DATETIME,
    next_followup_date DATETIME,
    account_manager VARCHAR(100),
    credit_rating VARCHAR(20) DEFAULT 'good',
    payment_reliability DECIMAL(5,2) DEFAULT 100.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### crm_contacts
```sql
CREATE TABLE crm_contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    debtor_no VARCHAR(20),
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
    is_primary BOOLEAN DEFAULT FALSE,
    inactive BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (debtor_no) REFERENCES debtors_master(debtor_no)
);
```

### crm_opportunities
```sql
CREATE TABLE crm_opportunities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    opportunity_name VARCHAR(100) NOT NULL,
    debtor_no VARCHAR(20),
    contact_id INT,
    sales_person VARCHAR(100),
    opportunity_type VARCHAR(50),
    status VARCHAR(20) DEFAULT 'prospecting',
    estimated_value DECIMAL(15,2),
    probability DECIMAL(5,2),
    expected_close_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (debtor_no) REFERENCES debtors_master(debtor_no),
    FOREIGN KEY (contact_id) REFERENCES crm_contacts(id)
);
```

### crm_communications
```sql
CREATE TABLE crm_communications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    debtor_no VARCHAR(20),
    contact_id INT,
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
    follow_up_required BOOLEAN DEFAULT FALSE,
    follow_up_date DATETIME,
    notes TEXT,
    email_message_id VARCHAR(255),
    attachment_path VARCHAR(500),
    created_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (debtor_no) REFERENCES debtors_master(debtor_no),
    FOREIGN KEY (contact_id) REFERENCES crm_contacts(id)
);
```

## Event Reference

### Customer Events
- `CRMCustomerCreatedEvent` - Fired when customer profile is created
- `CRMCustomerUpdatedEvent` - Fired when customer profile is updated
- `CRMCustomerDeletedEvent` - Fired when customer profile is deleted

### Contact Events
- `CRMContactCreatedEvent` - Fired when contact is created
- `CRMContactUpdatedEvent` - Fired when contact is updated
- `CRMContactDeletedEvent` - Fired when contact is deleted

### Opportunity Events
- `CRMOpportunityCreatedEvent` - Fired when opportunity is created
- `CRMOpportunityUpdatedEvent` - Fired when opportunity is updated
- `CRMOpportunityDeletedEvent` - Fired when opportunity is deleted
- `CRMOpportunityStatusChangedEvent` - Fired when opportunity status changes

### Communication Events
- `CRMCommunicationCreatedEvent` - Fired when communication is recorded
- `CRMCommunicationUpdatedEvent` - Fired when communication is updated
- `CRMCommunicationCompletedEvent` - Fired when communication is completed
- `CRMFollowUpRequiredEvent` - Fired when follow-up is required

## Exception Reference

### Core Exceptions
- `CRMException` - Base CRM exception
- `CRMDatabaseException` - Database operation errors
- `CRMPermissionException` - Permission-related errors
- `CRMConfigurationException` - Configuration errors

### Entity Exceptions
- `CRMCustomerNotFoundException` - Customer not found
- `CRMCustomerAlreadyExistsException` - Customer already exists
- `CRMCustomerValidationException` - Customer validation errors
- `CRMContactNotFoundException` - Contact not found
- `CRMContactValidationException` - Contact validation errors
- `CRMOpportunityNotFoundException` - Opportunity not found
- `CRMOpportunityValidationException` - Opportunity validation errors
- `CRMCommunicationNotFoundException` - Communication not found
- `CRMCommunicationValidationException` - Communication validation errors

## Testing

### Unit Tests
```bash
# Run CRM module tests
cd /path/to/frontaccounting
./vendor/bin/phpunit modules/CRM/tests/
```

### Integration Tests
- Test service integration with FA core services
- Verify event dispatching and handling
- Validate database operations and constraints
- Test exception handling and error scenarios

## WebERP Compatibility

This CRM module implements WebERP's advanced CRM features with modern PHP architecture:

- **Customer Types**: Categorization system matching WebERP
- **Contact Management**: Multi-contact system with roles
- **Territory Management**: Geographic sales territory support
- **EDI Integration**: B2B electronic data interchange
- **Geographic Features**: Location-based customer mapping
- **Advanced Analytics**: Customer value and performance analysis

## Migration & Integration

### Existing FA Customers
- CRM fields are additive - no existing data affected
- Gradual adoption of CRM features possible
- Backward compatibility maintained

### Module Integration
- Event-driven communication with other modules
- Shared services for common functionality
- Database relationships maintained
- Permission system integration

## Troubleshooting

### Common Issues

1. **Service Not Found**
   - Verify DI container configuration
   - Check service registration in module bootstrap

2. **Database Errors**
   - Ensure CRM tables exist and have correct structure
   - Check database permissions for CRM operations

3. **Event Not Firing**
   - Verify event dispatcher is properly configured
   - Check event listener registration

4. **Permission Errors**
   - Ensure user has required CRM permissions
   - Check role-based access control configuration

## Future Enhancements

- Email marketing campaign management
- Social media integration and monitoring
- Mobile CRM application
- AI-powered customer insights and recommendations
- Advanced workflow automation
- Integration with popular CRM platforms
- Real-time communication features
- Advanced reporting and dashboard

## Version History

- **1.0.0**: Complete CRM module with service-oriented architecture
  - Service classes with dependency injection
  - Entity models with type safety
  - Event-driven architecture
  - Custom exception hierarchy
  - Database abstraction layer
  - Comprehensive CRUD operations
  - Analytics and reporting
  - Integration with FA core services
  - Full documentation and testing