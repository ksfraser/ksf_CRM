# FA_CRM - FrontAccounting CRM Module

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.0+-777bb6)
![FA](https://img.shields.io/badge/FrontAccounting-2.4.x-green)
![License](https://img.shields.io/badge/license-GPL--3.0-orange)

## Overview

FA_CRM is an advanced Customer Relationship Management (CRM) module for FrontAccounting that transforms basic customer management into a full-featured CRM system with modern PHP architecture.

### Features

- **Enhanced Customer Profiles** - Industry, territory, segmentation, credit rating
- **Multi-Contact Management** - Multiple contacts per customer with roles
- **Sales Pipeline** - Opportunity tracking with probability-based forecasting
- **Communication Tracking** - Phone, email, meetings with follow-up management
- **Email Integration** - IMAP import and ICS meeting parsing
- **Meeting Scheduling** - Physical and virtual meetings with room booking
- **Lead Management** - Web-to-lead capture and conversion
- **Analytics & Reporting** - Customer LTV, pipeline metrics, territory reports
- **Activity Logging** - Complete audit trail

### Status

**IMPLEMENTED** - Production Ready

- Service-oriented architecture with dependency injection
- PSR-4 autoloading and modern PHP 8.0+ features
- Event-driven communication with PSR-14 event dispatcher
- Comprehensive entity models with type safety
- Custom exception hierarchy for error handling
- Database abstraction with FA-compatible functions
- Full CRUD operations for all entities
- Comprehensive unit tests included

## Quick Start

### Installation

1. **Copy module files**:
```bash
cp -r FA_CRM /path/to/frontaccounting/modules/
```

2. **Install via FrontAccounting**:
- Go to Administrator > Modules > Install Modules
- Find FA_CRM and click Install

3. **Database tables** are created automatically on install

4. **Assign permissions** to users via Administrator > User Roles

### Using the Module

Access via the CRM menu after installation:

- **CRM Dashboard**: Overview and statistics
- **Customers**: Enhanced customer management
- **Opportunities**: Sales pipeline
- **Communications**: Activity logging
- **Calendar**: Meetings and appointments
- **Reports**: Analytics and reporting
- **Settings**: Configuration

## Database Tables

### Core Tables

| Table | Description |
|-------|-------------|
| `fa_crm_customers` | Enhanced customer profiles |
| `fa_crm_contacts` | Customer contacts |
| `fa_crm_opportunities` | Sales opportunities |
| `fa_crm_communications` | Communication log |
| `fa_crm_customer_types` | Customer type definitions |
| `fa_crm_territories` | Sales territories |
| `fa_crm_activity_log` | Audit trail |

### Reference Data

Default customer types:
- Prospect, Active, Inactive, VIP, Partner

Default territories:
- North, South, East, West, Central

## Permissions

| Permission | Description |
|------------|-------------|
| `CRM_VIEW_CUSTOMER` | View customer data |
| `CRM_MANAGE_CUSTOMER` | Manage customer data |
| `CRM_VIEW_QUALIFY` | View opportunities |
| `CRM_MANAGE_QUALIFY` | Manage opportunities |
| `CRM_VIEW_COMMUNICATIONS` | View communications |
| `CRM_MANAGE_COMMUNICATIONS` | Manage communications |
| `CRM_VIEW_CALENDAR` | View calendar |
| `CRM_MANAGE_CALENDAR` | Manage calendar |
| `CRM_VIEW_ANALYTICS` | View analytics |
| `CRM_ADMIN` | Full administrative access |

## API Reference

### Database Functions

```php
// Customer CRM data
update_customer_crm_data($customer_id, $crm_data);
get_customer_crm_data($customer_id);

// Contact management
add_customer_contact($debtor_no, $contact_data);
update_customer_contact($contact_id, $contact_data);
delete_customer_contact($contact_id);
get_customer_contacts($debtor_no);

// Opportunities
add_opportunity($opportunity_data);
update_opportunity($opportunity_id, $opportunity_data);
delete_opportunity($opportunity_id);
get_customer_opportunities($debtor_no);

// Communications
add_communication($communication_data);
update_communication($communication_id, $data);
get_customer_communications($debtor_no);
get_pending_followups();

// Analytics
calculate_customer_analytics($customer_id);
get_customer_analytics($customer_id);
```

### UI Functions

```php
// Display components
crm_customer_summary($customer_id);
crm_customer_contacts_summary($customer_id);
crm_recent_communications($customer_id);
crm_dashboard_widget();

// Form helpers
customer_types_list_row($label, $name, $value);
territories_list_row($label, $name, $value);
contact_roles_list_row($label, $name, $value);
credit_rating_row($label, $name, $value);
```

## Events

The module dispatches events for integration:

- `crm.customer.created` - New customer
- `crm.customer.updated` - Customer updated
- `crm.contact.created` - New contact
- `crm.contact.updated` - Contact updated
- `crm.opportunity.created` - New opportunity
- `crm.opportunity.updated` - Opportunity updated
- `crm.communication.created` - Communication logged
- `crm.followup.required` - Follow-up scheduled

## Development

### Testing

```bash
# Run unit tests
./vendor/bin/phpunit tests/

# Run specific test file
./vendor/bin/phpunit tests/CRMModuleTest.php
```

### File Structure

```
FA_CRM/
├── FA_CRM_Module.php      # Module registration
├── crm.php              # Main module
├── hooks.php             # Installation hooks
├── includes/
│   ├── crm_db.inc       # Database functions
│   ├── crm_ui.inc       # UI components
│   └── EmailImportService.php  # Email import
├── pages/              # Page handlers
├── sql/
│   └── install.sql    # Schema
└── tests/             # Unit tests
```

## Troubleshooting

### Common Issues

1. **CRM data not showing**
   - Verify CRM profile exists for customer
   - Check database connection

2. **Email import failing**
   - Verify IMAP credentials
   - Check PHP IMAP extension

3. **Permissions errors**
   - Assign CRM permissions to user role

4. **Database errors**
   - Verify all tables created
   - Check MySQL user permissions

## Version History

| Version | Changes |
|---------|---------|
| 1.0.0 | Initial release with complete CRM features |

## Requirements

- FrontAccounting 2.4.0+
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.0+
- PHP IMAP extension (for email import)

## License

Copyright (C) 2024 KSFII Development Team

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

## Support

For issues and feature requests, please open an issue on the project repository.

## Documentation

Full documentation is available in `doc/ProjectDocuments/ProjectDcs/`:

- [Functional Requirements](doc/ProjectDocuments/ProjectDcs/Functional%20Requirements.md)
- [Architecture](doc/ProjectDocuments/ProjectDcs/Architecture.md)
- [Test Plan](doc/ProjectDocuments/ProjectDcs/Test%20Plan.md)
- [UAT Plan](doc/ProjectDocuments/ProjectDcs/UAT%20Plan.md)

---
*FA_CRM Module v1.0.0*
*For FrontAccounting 2.4.x*
