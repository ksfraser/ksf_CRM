# Use Cases - ksf_JobDescriptions_UI

---

## UC-JD-001: Create Job Description
**Actor**: HR Manager

**Preconditions**: User has HR Manager role, logged into FA

**Flow**:
1. Navigate to HR > Job Descriptions > New
2. Enter job title
3. Select department
4. Select template (optional)
5. Add responsibilities (dynamic list)
6. Add qualifications (dynamic list)
7. Select required competencies
8. Set status to Active
9. Save

**Postconditions**: Job description created, visible in list

---

## UC-JD-002: Edit Job Description
**Actor**: HR Manager

**Preconditions**: Existing job description

**Flow**:
1. Navigate to Job Descriptions list
2. Click edit icon on row
3. Modify fields
4. Add/remove competencies
5. Save changes
6. Version history updated

**Postconditions**: Changes saved, version incremented

---

## UC-JD-003: Search Job Descriptions
**Actor**: Hiring Manager, HR User

**Preconditions**: User has search access

**Flow**:
1. Enter search query in search box
2. System searches title, description, competencies
3. Results displayed in real-time
4. Apply additional filters
5. Export results

---

## UC-JD-004: View Competency Matrix
**Actor**: HR Manager, Department Head

**Preconditions**: User has matrix access

**Flow**:
1. Navigate to HR > Competency Matrix
2. Select department filter
3. View matrix grid (competencies vs levels)
4. Click competency for details
5. Compare with employee profile (optional)

**Postconditions**: Competency gaps identified

---

## UC-JD-005: Use Job Template
**Actor**: HR Manager

**Preconditions**: Existing templates

**Flow**:
1. Create new job description
2. Select template from dropdown
3. Form auto-populates with template data
4. Modify as needed
5. Save new description

---

## UC-JD-006: Export Job Description
**Actor**: HR Manager, Hiring Manager

**Preconditions**: Job description exists

**Flow**:
1. Open job description detail
2. Click Export button
3. Select format (PDF/Word)
4. Download generated file

---

## UC-JD-007: Competency Autocomplete
**Actor**: HR Manager

**Preconditions**: Creating/editing job description

**Flow**:
1. Click in competency field
2. Type search term
3. Autocomplete suggestions appear
4. Select from list or add new
5. Set proficiency level (1-5)

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
