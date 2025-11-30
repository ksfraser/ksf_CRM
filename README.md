# FrontAccounting WebERP-Style CRM Module

Advanced Customer Relationship Management system based on WebERP's comprehensive CRM capabilities.

## Status

✅ **IMPLEMENTED** - Core CRM module is fully implemented and functional.

- Module loads successfully and implements ModuleInterface
- Database schema created with separate CRM tables (not extending debtors_master)
- Email import service with IMAP support
- Communications tracking with multiple types (calls, meetings, emails, SMS, notes)
- Contact management with roles
- Customer analytics and lifetime value calculations
- UI components and management pages ready
- Unit tests passing (with minor mock requirements)
- Ready for integration with FrontAccounting module system

## Overview

This CRM module transforms FrontAccounting's basic customer management into a full-featured CRM system with:

- **Separate CRM Tables**: CRM data stored in dedicated tables, not extending core FA tables
- **Advanced Contact Management**: Multiple contacts per customer with roles and communication tracking
- **Comprehensive Communications**: Track calls, meetings, emails, SMS, and notes with contact association
- **Email Import**: Automated import from SMTP/IMAP servers with contact matching
- **Sales Pipeline**: Opportunity tracking and sales forecasting
- **Customer Analytics**: Lifetime value analysis and customer segmentation
- **Territory Management**: Geographic sales territory support
- **Customer Types**: Categorization system for different customer segments

## Features

### Customer Management
- Customer types and categorization
- Customer segments for targeted marketing
- Sales territories and territory management
- Enhanced customer profiles with industry, size, website
- Geographic mapping with latitude/longitude
- Account managers and relationship tracking

### Contact Management
- Multiple contacts per customer
- Contact roles (Primary, Billing, Technical, Sales)
- Communication preferences and history
- Contact-specific notes and follow-ups

### Sales & Opportunities
- Sales opportunity tracking
- Pipeline management and forecasting
- Probability-based revenue projections
- Sales person assignment and tracking
- **Opportunities Management Page** (`/modules/CRM/pages/opportunities.php`)

### Analytics & Reporting
- Customer lifetime value analysis
- Payment reliability tracking
- Sales performance by territory
- Customer segmentation reports
- Marketing campaign effectiveness

### Integration Features
- EDI configuration for B2B customers
- Marketing opt-out management
- External system integration hooks
- API endpoints for third-party integration

## Installation

1. **Module Registration**: The CRM module is automatically registered with the FA module system
2. **Database Setup**: Run the module installation to create CRM database tables
3. **Permissions**: Ensure users have appropriate CRM permissions (SA_CUSTOMER)

### Database Tables Created

The module creates the following tables:
- `crm_customer_types` - Customer type definitions
- `crm_customer_segments` - Customer segmentation
- `crm_contact_roles` - Contact role definitions
- `crm_territories` - Sales territory management
- `crm_opportunities` - Sales opportunity tracking
- `crm_campaigns` - Marketing campaign management
- `crm_edi_config` - EDI configuration
- `crm_customer_analytics` - Customer analytics data

### Extended Tables

The module extends `debtors_master` with additional CRM fields:
- `customer_type_id`
- `customer_segment_id`
- `territory_id`
- `customer_since`
- `website`
- `industry`
- `employee_count`
- `annual_revenue`
- `parent_company`
- `latitude/longitude`
- `edi_enabled`
- `marketing_opt_out`
- `preferred_contact_method`
- `last_contact_date`
- `next_followup_date`
- `account_manager`
- `credit_rating`
- `payment_reliability`

## Usage

### Accessing CRM Features

1. **CRM Dashboard**: Main CRM overview at `/modules/CRM/pages/dashboard.php`
2. **Enhanced Customer Management**: Use `/sales/manage/enhanced_customers.php` for full CRM customer management
3. **Customer Types**: Manage customer types at `/modules/CRM/pages/customer_types.php`
4. **Territories**: Manage sales territories at `/modules/CRM/pages/territories.php`

### Key Workflows

#### Adding a New Customer with CRM Features
1. Go to Enhanced Customer Management
2. Fill in basic customer information
3. Set customer type, segment, and territory
4. Add geographic information (optional)
5. Configure EDI settings if applicable
6. Add multiple contacts with roles
7. Set account manager and follow-up dates

#### Managing Sales Opportunities
1. From customer details, add sales opportunities
2. Track opportunity status and probability
3. Monitor pipeline value and forecasting
4. Generate opportunity reports

#### Customer Analytics
- View customer lifetime value
- Track payment reliability
- Analyze sales patterns
- Generate segmentation reports

## API Integration

The CRM module provides hooks for integration with external systems:

### Event Hooks
- `customer.created` - Fired when new customer is added
- `customer.updated` - Fired when customer is modified
- `customer.deleted` - Fired when customer is deleted
- `sales.order.created` - Fired when sales order is created
- `sales.invoice.created` - Fired when sales invoice is created

### Database Hooks
- `pre_customer_save` - Before customer data is saved
- `post_customer_save` - After customer data is saved
- `pre_customer_delete` - Before customer is deleted
- `post_customer_delete` - After customer is deleted

## Configuration

### System Settings
- Default customer types and segments
- Territory assignments
- EDI configuration templates
- Marketing campaign settings

### User Permissions
- `SA_CUSTOMER` - Basic customer management
- `CRM_CUSTOMER_TYPES` - Manage customer types
- `CRM_TERRITORIES` - Manage territories
- `CRM_OPPORTUNITIES` - Manage sales opportunities
- `CRM_ANALYTICS` - View customer analytics

## Installation

### Prerequisites
- FrontAccounting 2.4.0 or higher
- PHP 8.1+
- MySQL 5.7+ or MariaDB 10.0+
- Write permissions for module directory

### Installation Steps

1. **Copy Module Files**
   ```bash
   # Copy the CRM module to FA modules directory
   cp -r modules/CRM /path/to/frontaccounting/modules/
   ```

2. **Module Registration**
   - Access FA Admin → Setup → Modules
   - Install the CRM module
   - Activate the module

3. **Database Setup**
   - Module automatically creates required tables on activation
   - Extended customer fields added to debtors_master table

4. **Permission Setup**
   - Assign CRM permissions to users:
     - CRM_CUSTOMER_TYPES: Manage customer types
     - CRM_TERRITORIES: Manage sales territories
     - CRM_OPPORTUNITIES: Manage sales opportunities
     - CRM_ANALYTICS: View customer analytics

### Testing

#### Unit Tests
```bash
# Run CRM module tests
cd /path/to/frontaccounting
./vendor/bin/phpunit modules/CRM/tests/CRMModuleTest.php
```

#### Manual Testing
1. **Module Loading**
   - Verify module appears in Admin → Modules
   - Check no PHP errors on module activation

2. **Database Tables**
   - Verify CRM tables created:
     - crm_customer_types
     - crm_customer_segments
     - crm_territories
     - crm_opportunities
     - crm_edi_config
     - crm_customer_analytics

3. **UI Components**
   - Access CRM Dashboard from Sales menu
   - Test customer type management
   - Test territory management
   - Verify enhanced customer page loads

#### Integration Testing
- Create test customers with CRM fields
- Test customer type assignment
- Verify territory assignment works
- Check analytics data generation

## WebERP Compatibility

This CRM module implements WebERP's advanced CRM features:

- **Customer Types**: Categorization system matching WebERP
- **Contact Management**: Multi-contact system with roles
- **Territory Management**: Geographic sales territory support
- **EDI Integration**: B2B electronic data interchange
- **Geographic Features**: Location-based customer mapping
- **Advanced Analytics**: Customer value and performance analysis

## Migration from Basic FA

### Existing Customers
- Existing customers are automatically available in CRM
- CRM fields are added with NULL defaults
- No data loss during migration

### Enhanced Features
- Gradually adopt CRM features as needed
- Existing workflows continue to work
- CRM features are additive, not replacing

## Troubleshooting

### Common Issues

1. **Module Not Loading**
   - Check module registration in FA module system
   - Verify database permissions
   - Check PHP error logs

2. **Database Errors**
   - Ensure CRM tables were created during installation
   - Check database user permissions
   - Verify table structure matches expectations

3. **Permission Errors**
   - Verify user has SA_CUSTOMER permission
   - Check CRM-specific permissions are assigned

### Support

For support and bug reports:
- Check FA community forums
- Review module documentation
- Contact FA development team

## Future Enhancements

- Email marketing integration
- Social media integration
- Mobile CRM app
- AI-powered customer insights
- Advanced workflow automation
- Integration with popular CRM platforms (SuiteCRM, HubSpot, etc.)

## Version History

- **1.0.0**: Initial release with core CRM features
  - Customer types and segments
  - Territory management
  - Enhanced customer profiles
  - Contact management
  - Sales opportunities
  - Basic analytics
  - EDI configuration