# Business Requirements - ksf_Recruitment_UI

## Document Information
- **Module**: ksf_Recruitment_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft
- **Author**: KSFII Development Team
- **Replaces**: OrangeHRM Recruitment module, ChurchCRM Recruitment, NotrinosERP Recruitment

---

## 1. Project Overview

ksf_Recruitment_UI is a FrontAccounting UI adapter module that provides web interface functionality for recruitment management. It bridges the business logic in ksf_Recruitment with FrontAccounting's page rendering and AJAX handling system.

### 1.1 Replaces
| Legacy System | Features Replaced |
|---------------|-------------------|
| OrangeHRM Recruitment | Candidate tracking, job openings |
| ChurchCRM Recruitment | Basic position management |
| NotrinosERP Recruitment | Position and applicant tracking |
| SuiteCRM Recruitment | Pipeline management |
| Vtiger Recruitment | Hiring workflow |

---

## 2. User Personas

### 2.1 Employee (Internal - WP_ESS)
- **Access**: WP_EmployeeSelfServe portal
- **Capabilities**:
  - View internal job postings
  - Submit internal applications
  - Track own application status
  - View assigned job descriptions

### 2.2 Recruiter/HR Manager
- **Access**: FrontAccounting
- **Capabilities**:
  - Create and manage job openings
  - Manage candidate database
  - Run recruitment pipeline
  - Schedule interviews
  - Generate offers

### 2.3 Hiring Manager
- **Access**: FrontAccounting
- **Capabilities**:
  - View positions in their department
  - Approve requisitions
  - Review candidates
  - Submit interview feedback

### 2.4 Project Manager (PM)
- **Access**: FrontAccounting
- **Capabilities**:
  - View project-linked positions
  - See candidate pipeline for project roles
  - Add comments to requisitions

### 2.5 Administrator
- **Access**: FrontAccounting + WP_ESS
- **Capabilities**:
  - Configure recruitment settings
  - Manage workflow stages
  - Access all recruitment data

---

## 3. Access Control Requirements

### 3.1 Access Matrix

| Role | View Positions | Create Positions | Manage Candidates | Approve Requisitions | Admin |
|------|---------------|-----------------|------------------|---------------------|-------|
| Employee (WP_ESS) | Internal only | No | Own only | No | No |
| Sales/Support | All (assigned) | Yes | Yes (assigned) | No | No |
| Recruiter/HR | All | Yes | Yes | Yes | Config |
| Hiring Manager | Department | Request | Department | Yes | No |
| Project Manager | Project-linked | No | Project-linked | No | No |
| Finance Manager | All | No | No | Budget review | No |
| Admin | All | Yes | Yes | Yes | Yes |

### 3.2 Employee Self-Service (WP_ESS) Access
- Employees see only internal job postings
- Employees can only view their own applications
- Access limited to personal data and assigned job descriptions
- No access to candidate pool or recruitment metrics

### 3.3 Project Manager Access
- Project Managers see positions linked to their projects
- Contract visibility determines position access
- Can view candidate pipeline for project roles
- Cannot modify recruitment workflow

### 3.4 Family Company Visibility
- Parent company users can see child company positions by default
- Gift-flagged contracts hide recruitment activity
- Separate subsidiary views with appropriate filtering

---

## 4. Data Privacy Requirements

### 4.1 Family Company Rules
- **Parent Account Visibility**: Parent company recruiters see child company positions
- **Gift Transaction Flag**: When `gift_flag = true` on contract:
  - Recruitment activity hidden from parent account
  - Visible only to: assigned recruiter, HR Manager, Finance Manager

### 4.2 Candidate Data Protection
- Candidate personal information encrypted at rest
- Resume/CV documents with access controls
- Candidate consent for data processing
- Right to access and withdraw application

---

## 5. Integration Requirements

### 5.1 FrontAccounting Integration
- Department/position linking with FA dimensions
- Employee data for internal candidates
- Budget coding for requisitions
- GL integration for hiring costs

### 5.2 WordPress Integration (WP_ESS)
- Employee login and authentication
- Internal job postings display
- Application submission form
- Application status tracking
- Job description viewing

### 5.3 Contract Integration
- Job openings linked to contracts
- Contract terms affecting recruitment
- Project-linked positions via contracts

### 5.4 Module Dependencies

| Module | Purpose |
|--------|---------|
| ksf_Recruitment | Core business logic |
| ksf_JobDescriptions | Job requirements and competencies |
| ksf_Calendar | Interview scheduling |
| ksf_Documents | Resume/CV storage |
| ksf_Contracts | Contract-linked recruitment |
| ksf_FA_ESS | Employee self-service |

---

## 6. Compliance Requirements

### 6.1 Employment Law Compliance
- Equal opportunity tracking
- Diversity metrics
- Anti-discrimination controls
- Documentation retention (7 years)

### 6.2 Data Protection (GDPR/POPIA)
- Candidate consent management
- Right to access personal data
- Right to withdraw
- Data breach notification

### 6.3 Audit Trail
- All recruitment actions logged
- Approval history maintained
- Interview feedback archived
- Offer generation tracked

---

## 7. Adapter Pattern

```
┌─────────────────────────────────────────────────────────┐
│                    UI Layer                             │
│  ksf_Recruitment_UI/                                    │
│    ├── pages/          → FA page handlers              │
│    ├── js/             → AJAX handlers                  │
│    └── templates/      → TWIG templates                │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                 Business Logic Layer                    │
│  ksf_Recruitment/                                      │
│    ├── Entity/        → Candidate, JobOpening, Pipeline │
│    └── Service/       → RecruitmentService               │
└─────────────────────────────────────────────────────────┘
```

---

## 8. Core UI Components

### 8.1 Pages
- Job openings list
- Job opening create/edit
- Candidate database
- Candidate profile
- Pipeline kanban view
- Interview scheduler
- Offer management
- Hiring dashboard

### 8.2 AJAX Handlers
- CRUD for job openings
- Candidate search/filter
- Pipeline stage updates
- Interview scheduling
- Feedback collection
- Offer generation

---

## 9. Success Metrics

| Metric | Target | Measurement |
|--------|--------|-------------|
| Time-to-hire | < 30 days | Average calendar days |
| Candidate experience | > 85% | Survey rating |
| Pipeline conversion | > 20% | Applied to hired |
| Offer acceptance | > 85% | Offers accepted/total |
| Internal fill rate | > 40% | Internal hires/total |

---

## 10. UAT Plan Cross-Reference

For detailed UAT test cases, refer to:
- [UAT Plan.md](./UAT Plan.md) - User acceptance test scenarios including:
  - Core recruitment workflows (UAT-REC-001 to UAT-006)
  - WP_ESS integration (UAT-REC-007)
  - Manager approval workflow (UAT-REC-008)
  - Project Manager access (UAT-REC-009)
  - Family company visibility (UAT-REC-010)
  - Contract-linked access (UAT-REC-011)

---

*Document Version: 1.1.0*
*Last Updated: 2026-05-13*
