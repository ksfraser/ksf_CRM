# Architecture - ksf_FA_CampaignBuilder

## Document Information
- **Module**: ksf_FA_CampaignBuilder
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_FA_CampaignBuilder provides FrontAccounting integration for marketing campaign management, leveraging FA's customer database and sales tracking for campaign analytics.

### 1.1 Namespace
```php
Ksfraser\FA\CampaignBuilder\
```

### 1.2 Layer Pattern
```
ksf_FA_CampaignBuilder/
├── composer.json
├── AGENTS.md
├── hooks.php                      → FA hooks (extension)
├── ProjectDcs/                    ← THIS DOCUMENTATION
├── pages/
│   ├── campaigns.php              → Campaign list
│   ├── campaign_edit.php          → Create/edit
│   ├── campaign_targets.php       → Target management
│   ├── campaign_analytics.php     → Performance reports
│   └── campaign_templates.php     → Template management
├── Integration/
│   ├── DebtorAdapter.php          → FA debtor integration
│   ├── SalesAdapter.php           → Sales order integration
│   └── CustomerSegmentAdapter.php  → CRM segment integration
└── src/
    └── Ksfraser/FA/CampaignBuilder/
        ├── Presenter/
        │   ├── CampaignListPresenter.php
        │   ├── CampaignFormPresenter.php
        │   └── AnalyticsPresenter.php
        ├── Component/
        │   ├── CampaignCard.php
        │   ├── BudgetTracker.php
        │   ├── ConversionFunnel.php
        │   └── TargetSelector.php
        └── Handler/
            └── CampaignAjaxHandler.php
```

---

## 2. FA Hook Integration

### 2.1 hooks.php Structure

```php
class hooks_ksf_fa_campaignbuilder extends hooks {
    var $module_name = 'ksf_FA_CampaignBuilder';

    function install_access() {
        $security_sections['SS_CAMPAIGN'] = _("Campaign Management");
        $security_areas['SA_CAMPAIGN_VIEW'] = array(SS_CAMPAIGN | 1, _("View Campaigns"));
        $security_areas['SA_CAMPAIGN_CREATE'] = array(SS_CAMPAIGN | 2, _("Manage Campaigns"));
        $security_areas['SA_CAMPAIGN_REPORT'] = array(SS_CAMPAIGN | 3, _("Campaign Reports"));
        return array($security_areas, $security_sections);
    }

    function install_options($app) {
        switch($app->id) {
            case 'orders':
                $app->add_lapp_function(0, _("Campaign Builder"),
                    $path_to_root."/modules/ksf_FA_CampaignBuilder/campaigns.php",
                    'SA_CAMPAIGN_VIEW', MENU_MAIN);
                break;
        }
    }

    function activate_extension($company, $check_only=true) {
        $updates = array('sql/update.sql' => array($this->module_name));
        return $this->update_databases($company, $updates, $check_only);
    }
}
```

---

## 3. Database Adapters

### 3.1 DebtorAdapter

```php
namespace Ksfraser\FA\CampaignBuilder\Integration;

class DebtorAdapter {
    public function getDebtorsBySegment(string $segmentId): array;
    public function getDebtorsByTerritory(string $territoryId): array;
    public function getDebtorContacts(int $debtorNo): array;
    public function getDebtorEmail(int $debtorNo): ?string;
    public function getRecentCustomers(\DateTime $since): array;
}
```

| Method | FA Table | Purpose |
|--------|----------|---------|
| getDebtorsBySegment | fa_crm_customers | Segment targeting |
| getDebtorsByTerritory | fa_crm_territories | Regional campaigns |
| getDebtorContacts | fa_crm_contacts | Email targeting |
| getRecentCustomers | debtors | New customer campaigns |

### 3.2 SalesAdapter

```php
class SalesAdapter {
    public function getOrdersByCampaign(string $campaignId): array;
    public function getRevenueByCampaign(string $campaignId): float;
    public function getLeadConversionByCampaign(string $campaignId): array;
    public function getCustomerAcquisitionCost(string $campaignId): float;
}
```

| Method | FA Table | Purpose |
|--------|----------|---------|
| getOrdersByCampaign | sales_orders | Conversion tracking |
| getRevenueByCampaign | sales_orders + debtor_trans | Revenue attribution |
| getLeadConversionByCampaign | fa_crm_leads | Funnel metrics |

---

## 4. Entity Adaptation

### 4.1 Campaign (FA Adapted)

```php
namespace Ksfraser\FA\CampaignBuilder\Entity;

class Campaign {
    private string $id;
    private string $name;
    private ?int $debtorNo;         // FA link
    private CampaignType $type;
    private CampaignStatus $status;
    private ?\DateTime $startDate;
    private ?\DateTime $endDate;
    private float $budget;
    private float $spent;
    private string $channel;         // email, event, promotion
    private ?string $targetSegmentId;

    // FA integration methods
    public function getLinkedDebtors(): array;
    public function getAttributedRevenue(): float;
    public function calculateROI(): float;
}
```

### 4.2 CampaignTarget

```php
class CampaignTarget {
    private string $id;
    private string $campaignId;
    private ?int $debtorNo;          // FA debtor
    private string $contactId;       // CRM contact
    private TargetStatus $status;
    private ?\DateTime $convertedDate;
    private ?int $convertedOrderId;
    private float $attributedRevenue;

    public function getDebtor(): ?array;
    public function getContact(): ?array;
}
```

---

## 5. State Machines

### 5.1 Campaign Status

```
Draft ──> Planning ──> Active ──> Completed
    │           │              │
    └───────────┴──> Cancelled <┘
```

### 5.2 Target Status

```
Added ──> Contacted ──> Engaged ──> Converted
                      │
                      └─> Unresponsive ──> Removed
```

---

## 6. Presenter Layer

### 6.1 CampaignListPresenter

```php
class CampaignListPresenter {
    public function getCampaigns(array $filters): array;
    public function getCampaignSummary(): array;
    public function getActiveCampaigns(): array;
    public function searchCampaigns(string $query): array;
}
```

### 6.2 CampaignFormPresenter

```php
class CampaignFormPresenter {
    public function getFormData(string $id = null): array;
    public function getSegments(): array;
    public function getTerritories(): array;
    public function getTemplates(): array;
    public function saveCampaign(array $data): Campaign;
}
```

### 6.3 AnalyticsPresenter

```php
class AnalyticsPresenter {
    public function getCampaignMetrics(string $campaignId): array;
    public function getConversionFunnel(string $campaignId): array;
    public function getRevenueAttribution(string $campaignId): array;
    public function getROIAnalysis(string $campaignId): array;
}
```

---

## 7. AJAX Handler

### 7.1 Handler Actions

| Action | Method | Description |
|--------|--------|-------------|
| `campaign_list` | handleList | Paginated list |
| `campaign_create` | handleCreate | New campaign |
| `campaign_update` | handleUpdate | Edit campaign |
| `campaign_delete` | handleDelete | Soft delete |
| `campaign_publish` | handlePublish | Set active |
| `campaign_cancel` | handleCancel | Cancel campaign |
| `target_add` | handleTargetAdd | Add targets |
| `target_remove` | handleTargetRemove | Remove targets |
| `analytics_data` | handleAnalytics | Fetch metrics |

---

## 8. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Campaign List | `pages/campaigns.php` | Main list with filters |
| Campaign Edit | `pages/campaign_edit.php` | Create/edit form |
| Campaign Targets | `pages/campaign_targets.php` | Target management |
| Campaign Analytics | `pages/campaign_analytics.php` | Performance dashboard |
| Templates | `pages/campaign_templates.php` | Template management |

---

## 9. Integration Points

### 9.1 With Business Logic
```php
$service = container()->get(CampaignServiceInterface::class);
```

### 9.2 With ksf_FA_CRM
```php
// Customer segments for targeting
$segmentAdapter = container()->get(CustomerSegmentAdapter::class);
```

### 9.3 With ksf_EmailManager
```php
// Email campaign integration
$emailService = container()->get(EmailServiceInterface::class);
```

### 9.4 With ksf_Calendar
```php
// Event campaign tracking
$calendarService = container()->get(CalendarServiceInterface::class);
```

---

## 10. Error Handling

| Error Type | FA Handler Response |
|------------|---------------------|
| Validation Error | inline_errors() |
| Not Found | display_error() |
| Permission Denied | display_access_denied() |
| Database Error | db_error() |
| Service Error | alert() |

---

## 11. Database Schema

```sql
-- Campaign tables
CREATE TABLE IF NOT EXISTS `{TB_PREF}campaign` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `type` VARCHAR(30) DEFAULT 'email',
    `status` VARCHAR(20) DEFAULT 'draft',
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `budget` DECIMAL(15,2) DEFAULT 0,
    `spent` DECIMAL(15,2) DEFAULT 0,
    `target_segment_id` INT(11) DEFAULT NULL,
    `channel` VARCHAR(30) DEFAULT 'email',
    `created_by` VARCHAR(100) DEFAULT NULL,
    `inactive` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_dates` (`start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Campaign targets
CREATE TABLE IF NOT EXISTS `{TB_PREF}campaign_target` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `campaign_id` INT(11) NOT NULL,
    `debtor_no` VARCHAR(20) DEFAULT NULL,
    `contact_id` INT(11) DEFAULT NULL,
    `status` VARCHAR(20) DEFAULT 'added',
    `converted_date` DATETIME DEFAULT NULL,
    `converted_order_id` INT(11) DEFAULT NULL,
    `attributed_revenue` DECIMAL(15,2) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_campaign_id` (`campaign_id`),
    KEY `idx_debtor_no` (`debtor_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*