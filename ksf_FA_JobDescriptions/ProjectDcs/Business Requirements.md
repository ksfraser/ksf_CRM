# Business Requirements - ksf_FA_JobDescriptions

## Document Information
- **Module**: ksf_FA_JobDescriptions
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft
- **Author**: KSFII Development Team
- **Replaces**: OrangeHRM Job Descriptions, Vtiger Job Descriptions, SuiteCRM HR module

---

## 1. Project Overview

ksf_FA_JobDescriptions is a FrontAccounting adapter module that provides HR job description management functionality integrated with FA's employee and department data. It bridges business logic with FA's hook system, page rendering, and database adapters.

### 1.1 Replaces
| Legacy System | Features Replaced |
|---------------|-------------------|
| OrangeHRM Job Descriptions | Job description creation, competency tracking |
| Vtiger Job Descriptions | Position templates, hierarchy |
| SuiteCRM HR Module | Employee job requirements |
| SugarCRM HR | Job description management |
| Jetpack CRM Jobs | Basic job listings |

---

## 2. User Personas

### 2.1 Employee (Internal - WP_ESS)
- **Access**: WP_EmployeeSelfServe portal
- **Capabilities**:
  - View assigned job description
  - See competency requirements for role
  - View own competency gap analysis
  - Access training recommendations

### 2.2 HR Manager/Admin
- **Access**: FrontAccounting
- **Capabilities**:
  - Create/edit/archive job descriptions
  - Manage competency library
  - Configure templates
  - View all departments' job descriptions
  - System configuration

### 2.3 Department Head/Manager
- **Access**: FrontAccounting
- **Capabilities**:
  - View department job descriptions
  - Approve/reject job descriptions
  - Access team competency matrix
  - Review training needs

### 2.4 Hiring Manager
- **Access**: FrontAccounting
- **Capabilities**:
  - Use job description templates
  - View position requirements
  - Link to recruitment

### 2.5 Project Manager (PM)
- **Access**: FrontAccounting
- **Capabilities**:
  - View project-related job descriptions
  - See team member qualifications
  - Access contract-linked positions

---

## 3. Access Control Requirements

### 3.1 Access Matrix

| Role | View Job Descriptions | Create/Edit | Approve | Admin Settings | Gap Analysis |
|------|----------------------|-------------|---------|----------------|---------------|
| Employee (WP_ESS) | Assigned only | No | No | No | Own only |
| Sales/Support | General | No | No | No | No |
| Department Head | Department | Request | Yes | No | Team |
| Hiring Manager | All | Use templates | No | No | No |
| Project Manager | Project-linked | No | No | No | Project |
| HR Manager | All | Yes | Yes | Read | All |
| HR Admin | All | Yes | Yes | Yes | All |

### 3.2 Employee Self-Service (WP_ESS) Access
- Employees see ONLY their assigned job description
- Competency requirements displayed with required levels
- Gap analysis shows current vs required (if ksf_Performance linked)
- Training recommendations accessible
- No access to other employees' job descriptions

### 3.3 Manager Access Levels
- **Department Head**: Full access to department job descriptions
- **Hiring Manager**: Template access and position viewing
- **Project Manager**: Project-linked job descriptions only
- **HR Manager**: Department reports and analytics

### 3.4 Family Company Visibility
- Parent company HR sees child company job descriptions
- Gift-flagged contracts hide job descriptions
- Subsidiary filtering available

---

## 4. Data Privacy Requirements

### 4.1 Employee Data Protection
- Job descriptions linked to employees via position
- Competency levels stored securely
- Performance data separated from job description
- No cross-employee data visibility (except managers)

### 4.2 Gift Transaction Privacy
- Job descriptions linked to gift-flagged contracts hidden
- Only assigned HR and executives can see private descriptions

---

## 5. Integration Requirements

### 5.1 FrontAccounting Integration
- Department linking with FA dimensions
- Employee positions linked to FA employee records
- Competency levels mapped to FA skills

### 5.2 WordPress Integration (WP_ESS)
- Employee authentication
- Job description display
- Competency gap view
- Training recommendations

### 5.3 Module Dependencies

| Module | Purpose |
|--------|---------|
| ksf_JobDescriptions | Core business logic |
| ksf_Training | Training requirements from competencies |
| ksf_Performance | Competency assessment levels |
| ksf_Recruitment | Job templates for positions |
| ksf_Documents | Attachment storage |
| ksf_FA_ESS | Employee self-service |

### 5.4 Contract Integration
- Job descriptions linked to contracts
- Contract terms affecting job requirements
- Project-linked descriptions via contracts

---

## 6. Adapter Pattern

```
┌─────────────────────────────────────────────────────────┐
│                    FA Adapter Layer                     │
│  ksf_FA_JobDescriptions/                               │
│    ├── hooks.php        → Module registration          │
│    ├── pages/           → FA page handlers              │
│    ├── Integration/     → FA database adapters          │
│    └── src/             → Business logic adaptation      │
└─────────────────────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────┐
│                  Business Logic Layer                   │
│  ksf_JobDescriptions/                                    │
│    ├── Entity/        → JobDescription, Competency      │
│    └── Service/       → JobDescriptionService           │
└─────────────────────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────┐
│                  FrontAccounting Core                   │
│  (employees, departments, dimensions)                   │
└─────────────────────────────────────────────────────────┘
```

---

## 7. Core Functionality

### 7.1 Job Description Management
- Create/edit/archive job descriptions
- Link to FA departments and dimensions
- Version history tracking
- Template-based creation
- Status workflow (draft, active, archived)

### 7.2 Competency Management
- Competency library
- Proficiency level definitions (1-5)
- Required competencies per job description
- Gap analysis (current vs required)

### 7.3 Department Integration
- Link to FA departments
- Department-specific job descriptions
- Hierarchy visualization
- Department head approval

### 7.4 Integration Points
- ksf_Training for required training
- ksf_Performance for competency assessment
- ksf_Recruitment for position templates
- ksf_Documents for attachments

---

## 8. Compliance Requirements

### 8.1 Employment Standards
- Job descriptions meet regulatory requirements
- Competency levels auditable
- Approval history maintained

### 8.2 Audit Trail
- All job description changes logged
- Version history maintained
- Approval decisions recorded

---

## 9. Success Metrics

| Metric | Target | Measurement |
|--------|--------|-------------|
| Position coverage | 100% | Active positions with JDs |
| Creation time | < 20 min | Average time |
| Template usage | > 80% | Templates used/total |
| Gap closure | < 3 months | Competency gap resolution |
| Approval time | < 2 days | Average approval cycle |

---

## 10. UAT Plan Cross-Reference

For detailed UAT test cases, refer to:
- [UAT Plan.md](./UAT Plan.md) - User acceptance test scenarios including:
  - Core job description CRUD (UAT-JD-001 to UAT-JD-012)
  - WP_ESS employee view (UAT-JD-013)
  - Manager team access (UAT-JD-014)
  - Project Manager access (UAT-JD-015)
  - HR Admin full access (UAT-JD-016)
  - Privacy/gift transaction (UAT-JD-017)

---

*Document Version: 1.1.0*
*Last Updated: 2026-05-13*