# Architecture - ksf_CRM

## Document Information
- **Module**: ksf_CRM
- **Version**: 1.0.0
- **Date**: 2026-05-25
- **Status**: Implemented
- **Author**: KSFII Development Team

## 1. Purpose

ksf_CRM is the framework-agnostic CRM business logic library. It contains domain entities, services, and PSR-14 event classes with zero dependencies on FrontAccounting or any other platform.

## 2. Directory Structure

```
ksf_CRM/
├── src/Ksfraser/CRM/
│   ├── Entity/
│   │   ├── Customer.php         # Domain entity: typed properties, fluent setters
│   │   ├── Contact.php          # Domain entity: name, email, phone, primary contact
│   │   ├── Opportunity.php      # Domain entity: stage progression, close won/lost
│   │   └── Communication.php    # Domain entity: type, direction, status
│   ├── Service/
│   │   ├── CustomerService.php         # CRUD, search, segmentation
│   │   ├── OpportunityService.php       # Pipeline management, forecasting
│   │   └── CommunicationService.php     # Timeline, activity summary
│   └── Event/
│       ├── CustomerCreatedEvent.php     # PSR-14 event
│       ├── CustomerUpdatedEvent.php
│       ├── OpportunityCreatedEvent.php
│       └── OpportunityStageChangedEvent.php
├── tests/
│   └── Unit/
│       ├── Entity/CustomerTest.php       # 10 tests
│       ├── Entity/ContactTest.php
│       ├── Entity/OpportunityTest.php
│       └── Service/CustomerServiceTest.php
└── composer.json              # Pure PHP library, no FA dependencies
```

## 3. Design Principles

- **Framework-agnostic**: No references to FA db_query, TB_PREF, or any FA functions
- **PSR-4 autoloading**: Namespace Ksfraser\CRM\
- **Testable**: All services accept injected dependencies
- **Event-driven**: PSR-14 events for lifecycle hooks
- **Fluent API**: Entities use fluent setters returning `$this`

## 4. Platform Adapters

| Adapter | Repository | Namespace |
|---------|-----------|-----------|
| FrontAccounting | ksf_FA_CRM | Ksfraser\FA\CRM |
| WordPress (future) | ksf_WP_CRM | Ksfraser\WP\CRM |

## 5. Dependencies

- PHP >= 7.4
- ksfraser/exceptions ^1.2
