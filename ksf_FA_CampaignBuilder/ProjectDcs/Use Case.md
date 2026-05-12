# Use Cases - ksf_FA_CampaignBuilder

---

## UC-CAMP-001: Create Email Campaign
**Actor**: Marketing Manager

**Preconditions**: User has SA_CAMPAIGN_CREATE permission

**Flow**:
1. Navigate to Sales > Campaign Builder
2. Click "New Campaign"
3. Enter campaign name "Q2 Product Launch"
4. Select type "Email Campaign"
5. Set channel to "Email"
6. Set start date to next week
7. Set end date to 2 weeks later
8. Set budget to $5000
9. Click "Save Draft"
10. System creates campaign with draft status

**Postconditions**: Campaign exists with draft status

---

## UC-CAMP-002: Select Target Audience
**Actor**: Marketing Manager

**Preconditions**: Campaign exists in draft/planning status

**Flow**:
1. Open campaign "Q2 Product Launch"
2. Click "Manage Targets"
3. Select source "CRM Segment"
4. Choose segment "Enterprise Customers"
5. Optionally add filters (territory = North, customer since > 2024-01-01)
6. Preview: "Estimated 150 targets"
7. Click "Apply"
8. System creates target records for all matching debtors

**Postconditions**: 150 targets linked to campaign

---

## UC-CAMP-003: Import Targets from FA Debtors
**Actor**: Marketing Manager

**Preconditions**: Campaign exists

**Flow**:
1. Open campaign > Targets
2. Click "Import from FA"
3. Select import type "Debtor Numbers"
4. Upload CSV file with debtor numbers
5. System validates debtor numbers
6. Preview matches (145 valid, 5 not found)
7. Click "Import"
8. System creates target records

**Postconditions**: 145 new targets imported

---

## UC-CAMP-004: Publish Campaign
**Actor**: Marketing Manager

**Preconditions**: Campaign has targets and is in draft status

**Flow**:
1. Review campaign settings (dates, budget, targets)
2. Click "Publish Campaign"
3. System validates:
   - Start date is in future
   - Budget is set
   - At least one target
4. Confirm publish
5. System sets status to "Active"
6. Email service notified (ksf_EmailManager)

**Postconditions**: Campaign status = Active

---

## UC-CAMP-005: Track Campaign Spending
**Actor**: Marketing Manager, Finance

**Trigger**: Campaign expense incurred

**Flow**:
1. Marketing incurs expense (ad spend, printing, etc.)
2. Navigate to campaign > Budget
3. Click "Add Expense"
4. Enter: Description, Amount, Date, Category
5. Save
6. System updates spent amount
7. If spent >= threshold, send alert

**Postconditions**: Campaign spent updated

---

## UC-CAMP-006: Track Lead Conversion
**Actor**: System

**Trigger**: New customer created from lead

**Flow**:
1. Lead converted to customer
2. System checks for matching campaign targets
3. If match found:
   - Update target status to "Converted"
   - Set converted_date
   - Calculate attributed revenue (from orders)
   - Update campaign conversion metrics
4. Generate conversion notification

**Postconditions**: Target converted, campaign metrics updated

---

## UC-CAMP-007: Link Sales Order to Campaign
**Actor**: System

**Trigger**: Sales order created for campaign customer

**Flow**:
1. Sales order created for debtor
2. System queries for active campaigns with matching target
3. If found:
   - Create conversion record
   - Update attributed_revenue
   - Recalculate campaign ROI
4. Update customer record with campaign attribution

**Postconditions**: Order linked to campaign

---

## UC-CAMP-008: View Campaign Analytics
**Actor**: Marketing Manager, Management

**Preconditions**: Campaign has data

**Flow**:
1. Navigate to Reports > Campaign Analytics
2. Select campaign from dropdown
3. View dashboard:
   - Conversion funnel (targets → contacted → engaged → converted)
   - Revenue attribution
   - Budget utilization
   - ROI percentage
4. Export report (PDF/CSV)
5. Schedule recurring report

**Postconditions**: Analytics displayed

---

## UC-CAMP-009: Campaign Template Usage
**Actor**: Marketing Manager

**Preconditions**: Existing templates

**Flow**:
1. Create new campaign
2. Click "Use Template"
3. Select template "Annual Newsletter"
4. Template populates:
   - Default channel
   - Default budget
   - Content structure
5. Modify as needed
6. Save as new campaign

**Postconditions**: Campaign created from template

---

## UC-CAMP-010: Cancel Campaign
**Actor**: Marketing Manager

**Preconditions**: Campaign in draft/planning/active status

**Flow**:
1. Open campaign
2. Click "Cancel Campaign"
3. Select cancellation reason (budget cuts, strategy change, etc.)
4. Confirm
5. System:
   - Sets status to "Cancelled"
   - Stops email sends (if active)
   - Archives targets
   - Preserves historical data

**Postconditions**: Campaign cancelled, data preserved

---

## UC-CAMP-011: Territory-Based Targeting
**Actor**: Marketing Manager

**Preconditions**: FA territory data available

**Flow**:
1. Create new regional campaign
2. Select target source "FA Territories"
3. Choose territories: "North", "West"
4. Optional: Add customer type filter (Active customers only)
5. Preview target list
6. Apply selection
7. System imports all debtors from selected territories

**Postconditions**: Regional targets selected

---

## UC-CAMP-012: Campaign ROI Report
**Actor**: Finance, Management

**Flow**:
1. Navigate to Reports > Campaign ROI
2. Select date range (Q1 2026)
3. Select campaigns (or all)
4. Generate report showing:
   - Total campaign cost
   - Total attributed revenue
   - Net ROI percentage
   - Cost per conversion
   - Revenue per target
5. Export to PDF
6. Send to stakeholders

**Postconditions**: ROI report generated

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*