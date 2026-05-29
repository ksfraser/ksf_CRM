# UAT Plan - ksf_FA_CampaignBuilder

## Document Information
- **Module**: ksf_FA_CampaignBuilder
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate FrontAccounting adapter functionality for campaign management.

### 1.2 Prerequisites
- FrontAccounting installed
- ksf_CampaignBuilder business logic installed
- ksf_FA_CRM installed (customer data)
- ksf_EmailManager installed (email campaigns)
- Test FA company with debtors/contacts

### 1.3 Test Users
- Marketing Manager (SA_CAMPAIGN_CREATE)
- Marketing Viewer (SA_CAMPAIGN_VIEW)
- Finance (SA_CAMPAIGN_REPORT)

---

## 2. UAT Scenarios

### UAT-CAMP-001: Create Campaign
**Scenario**: Marketing Manager creates new email campaign

**Steps**:
1. Login to FrontAccounting
2. Navigate to Sales > Campaign Builder
3. Click "New Campaign"
4. Enter name: "Summer Sale Campaign"
5. Select type: "Email Campaign"
6. Select channel: "Email"
7. Set start date: Tomorrow
8. Set end date: 2 weeks from now
9. Set budget: $3000
10. Enter description
11. Save

**Expected Results**:
- [ ] Campaign created successfully
- [ ] Appears in campaign list
- [ ] Status = Draft
- [ ] Budget displayed correctly

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-002: Select Target Audience from FA
**Scenario**: Marketing Manager selects targets from FA customer database

**Steps**:
1. Open campaign "Summer Sale Campaign"
2. Click "Manage Targets"
3. Select source: "FA Debtors"
4. Filter by territory: "North"
5. Filter by customer type: "Active"
6. Preview: "Estimated 45 targets"
7. Click "Apply"
8. Wait for import

**Expected Results**:
- [ ] Import completes successfully
- [ ] 45 targets created
- [ ] Target list displays correctly
- [ ] Each target links to valid debtor

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-003: Select Targets from CRM Segment
**Scenario**: Marketing Manager uses CRM segment for targeting

**Steps**:
1. Open campaign > Targets
2. Select source: "CRM Segment"
3. Choose segment: "Enterprise Customers"
4. Preview: "Estimated 120 targets"
5. Click "Import"

**Expected Results**:
- [ ] Segment data loads
- [ ] 120 targets created
- [ ] Targets linked to debtor numbers

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-004: Publish Campaign
**Scenario**: Marketing Manager publishes completed campaign

**Steps**:
1. Verify campaign has targets
2. Verify campaign has valid dates
3. Click "Publish Campaign"
4. Confirm publication

**Expected Results**:
- [ ] Status changes to "Active"
- [ ] Email service notified
- [ ] Target status starts progressing

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-005: Track Campaign Budget
**Scenario**: Marketing Manager tracks campaign spending

**Steps**:
1. Open active campaign
2. Navigate to Budget tab
3. View: Budget $3000, Spent $1500
4. Add expense: "Ad spend", $500
5. Save

**Expected Results**:
- [ ] Spent updated to $2000
- [ ] Budget utilization shows 67%
- [ ] No alert triggered (< 80%)

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-006: Budget Alert
**Scenario**: System triggers budget alert at threshold

**Steps**:
1. Add expense bringing spent to $2400 (80% of $3000)
2. Save

**Expected Results**:
- [ ] Alert displayed: "Budget at 80%"
- [ ] Alert sent to campaign owner
- [ ] Campaign flagged in list view

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-007: View Conversion Funnel
**Scenario**: Marketing Manager views campaign conversion

**Steps**:
1. Navigate to Reports > Campaign Analytics
2. Select campaign "Summer Sale Campaign"
3. View Conversion Funnel:
   - Targets: 165
   - Contacted: 120
   - Engaged: 45
   - Converted: 12

**Expected Results**:
- [ ] Funnel displays correctly
- [ ] Percentages calculated
- [ ] Visual representation renders

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-008: View Revenue Attribution
**Scenario**: Finance views campaign revenue

**Steps**:
1. Navigate to Reports > Campaign Analytics
2. Select campaign
3. View Revenue Attribution:
   - Total Attributed Revenue: $15,000
   - Cost: $3,000
   - ROI: 400%

**Expected Results**:
- [ ] Revenue totals calculated
- [ ] ROI percentage correct
- [ ] Linked orders displayed

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-009: Sales Order Attribution
**Scenario**: System links new order to campaign

**Steps**:
1. Create new customer from campaign target
2. Process sales order for new customer
3. Verify order linked to campaign
4. View campaign analytics

**Expected Results**:
- [ ] Order linked automatically
- [ ] Target marked as "Converted"
- [ ] Revenue attribution updated
- [ ] Analytics reflect new conversion

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-010: Use Campaign Template
**Scenario**: Marketing Manager uses template

**Steps**:
1. Create new campaign
2. Click "Use Template"
3. Select "Monthly Newsletter"
4. Template populates form
5. Modify dates and budget
6. Save

**Expected Results**:
- [ ] Template fields populated
- [ ] Channel pre-selected
- [ ] Campaign created from template

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-011: Cancel Campaign
**Scenario**: Marketing Manager cancels campaign

**Steps**:
1. Open draft campaign
2. Click "Cancel Campaign"
3. Select reason: "Budget cuts"
4. Confirm

**Expected Results**:
- [ ] Status changes to "Cancelled"
- [ ] Historical data preserved
- [ ] Targets archived (not deleted)
- [ ] No active sends

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CAMP-012: Export Analytics Report
**Scenario**: Marketing Manager exports report

**Steps**:
1. View campaign analytics
2. Click "Export"
3. Select format: PDF
4. Download file
5. Verify content

**Expected Results**:
- [ ] PDF downloads
- [ ] Contains all metrics
- [ ] Formatting intact
- [ ] Date range correct

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

## 3. Sign-Off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Owner | | | |
| UAT Lead | | | |
| Technical Lead | | | |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*