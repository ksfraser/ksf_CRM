# Functional Requirements - ksf_FA_CampaignBuilder

## Document Information
- **Module**: ksf_FA_CampaignBuilder
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

### 1.1 Purpose
ksf_FA_CampaignBuilder provides marketing campaign management integrated with FrontAccounting's customer and sales data.

### 1.2 Scope
- Campaign CRUD operations
- Target audience management
- FA debtor integration
- Sales attribution
- Analytics and reporting

---

## 2. Core Entities

### 2.1 Campaign

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| name | string | Yes | Campaign name |
| type | string | Yes | email/event/promotion/social |
| status | string | Yes | draft/planning/active/completed/cancelled |
| start_date | Date | No | Campaign start |
| end_date | Date | No | Campaign end |
| budget | decimal | No | Budget amount |
| spent | decimal | No | Amount spent |
| target_segment_id | int | No | FK to segment |
| channel | string | Yes | marketing channel |
| description | text | No | Campaign details |
| created_by | string | Yes | User who created |
| inactive | bool | No | Soft delete |
| created_at | DateTime | Yes | Auto |
| updated_at | DateTime | Yes | Auto |

### 2.2 CampaignTarget

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| campaign_id | int | Yes | FK to Campaign |
| debtor_no | string | No | FA debtor number |
| contact_id | int | No | FK to Contact |
| status | string | Yes | added/contacted/engaged/converted |
| converted_date | DateTime | No | Conversion timestamp |
| converted_order_id | int | No | Linked sales order |
| attributed_revenue | decimal | No | Revenue from conversion |
| created_at | DateTime | Yes | Auto |

### 2.3 CampaignTemplate

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| name | string | Yes | Template name |
| type | string | Yes | Campaign type |
| default_budget | decimal | No | Default budget |
| default_channel | string | No | Default channel |
| content | text | No | Template content |
| inactive | bool | No | Soft delete |

---

## 3. Functional Requirements

### FR-CAMP-001: Campaign Management
**Requirement**: System shall allow CRUD operations for campaigns.

**Features**:
- Create campaign with type, channel, dates
- Edit campaign details
- Duplicate campaign
- Archive/completed status change
- Soft delete

### FR-CAMP-002: Budget Tracking
**Requirement**: System shall track campaign budget and spending.

**Features**:
- Set campaign budget
- Track spending against budget
- Budget alerts at thresholds (50%, 80%, 100%)
- Budget vs actual reporting

### FR-CAMP-003: Target Audience
**Requirement**: System shall manage campaign target audience.

**Features**:
- Import from FA debtors
- Import from CRM segments
- Manual target addition
- Bulk target import (CSV)
- Exclusion rules
- Target status tracking

### FR-CAMP-004: FA Debtor Integration
**Requirement**: System shall integrate with FA debtor table.

**Features**:
- Read debtor data for targeting
- Link targets to debtor numbers
- Sync customer data to targets
- Territory-based targeting

### FR-CAMP-005: Sales Attribution
**Requirement**: System shall track sales attributed to campaigns.

**Features**:
- Link sales orders to campaigns
- Calculate attributed revenue
- Conversion tracking (lead → customer)
- ROI calculation

### FR-CAMP-006: Analytics & Reporting
**Requirement**: System shall provide campaign analytics.

**Features**:
- Conversion funnel by stage
- Revenue attribution report
- ROI analysis
- Budget utilization
- Channel performance comparison

### FR-CAMP-007: Template Management
**Requirement**: System shall support campaign templates.

**Features**:
- Create campaign templates
- Apply template to new campaign
- Template categorization by type
- Template duplication

### FR-CAMP-008: Multi-Channel Support
**Requirement**: System shall support multiple campaign channels.

**Channels**:
- Email (ksf_EmailManager)
- Events (ksf_Calendar)
- Promotions
- Social media
- Direct mail

---

## 4. User Interactions

### 4.1 Campaign Creation Flow

1. User selects "New Campaign"
2. Enter campaign details (name, type, channel)
3. Set dates and budget
4. Select target audience (debtors/segments)
5. Configure channel-specific settings
6. Save as draft or publish

### 4.2 Target Selection

1. Choose target source (debtors, segments, manual)
2. Apply filters (territory, customer type, date range)
3. Preview target count
4. Confirm selection
5. System creates target records

### 4.3 Conversion Tracking

1. Sales order created
2. Check for campaign target match
3. If match, link order to campaign
4. Update target status to "converted"
5. Calculate attributed revenue

---

## 5. FA Hook Integration

### 5.1 Security Areas

```php
SS_CAMPAIGN = 115 << 8
SA_CAMPAIGN_VIEW = SS_CAMPAIGN | 1
SA_CAMPAIGN_CREATE = SS_CAMPAIGN | 2
SA_CAMPAIGN_REPORT = SS_CAMPAIGN | 3
SA_CAMPAIGN_ADMIN = SS_CAMPAIGN | 4
```

### 5.2 Menu Items

| Menu | Title | Path | Permission |
|------|-------|------|------------|
| Sales | Campaign Builder | /modules/ksf_FA_CampaignBuilder/campaigns.php | SA_CAMPAIGN_VIEW |
| Reports | Campaign Analytics | /modules/ksf_FA_CampaignBuilder/campaign_analytics.php | SA_CAMPAIGN_REPORT |
| Setup | Campaign Setup | /modules/ksf_FA_CampaignBuilder/setup.php | SA_CAMPAIGN_ADMIN |

---

## 6. Database Integration

### 6.1 Tables

| Table | Purpose |
|-------|---------|
| `{TB_PREF}campaign` | Campaign records |
| `{TB_PREF}campaign_target` | Campaign targets |
| `{TB_PREF}campaign_template` | Templates |
| `{TB_PREF}campaign_conversion` | Conversion tracking |
| `{TB_PREF}campaign_spend` | Budget tracking |

### 6.2 FA Table Usage

| FA Table | Purpose |
|----------|---------|
| `{TB_PREF}debtors` | Customer targeting |
| `{TB_PREF}fa_crm_customers` | Segment data |
| `{TB_PREF}fa_crm_contacts` | Email addresses |
| `{TB_PREF}sales_orders` | Conversion tracking |
| `{TB_PREF}debtor_trans` | Revenue attribution |

---

## 7. AJAX Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| campaign_list | GET | List campaigns |
| campaign_get | GET | Single campaign |
| campaign_create | POST | Create campaign |
| campaign_update | POST | Update campaign |
| campaign_delete | POST | Delete campaign |
| target_add | POST | Add targets |
| target_remove | POST | Remove targets |
| analytics_summary | GET | Campaign metrics |
| analytics_funnel | GET | Conversion funnel |
| analytics_roi | GET | ROI analysis |

---

## 8. Composer Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| ksfraser/exceptions | ^1.2 | Exception hierarchy |
| ksfraser/traits | ^1.0 | Trait library |
| ksfraser/campaign | * | Business logic |

---

## 9. Exceptions

| Exception | Extends | Description |
|-----------|---------|-------------|
| CampaignException | RuntimeException | Base campaign exception |
| CampaignNotFoundException | CampaignException | Campaign not found |
| CampaignValidationException | CampaignException | Validation errors |
| TargetNotFoundException | CampaignException | Target not found |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*