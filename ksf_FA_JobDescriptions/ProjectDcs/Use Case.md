# Use Cases - ksf_FA_JobDescriptions

---

## UC-JD-001: Create Job Description
**Actor**: HR Manager

**Preconditions**: User has SA_JOB_DESC_CREATE permission

**Flow**:
1. Navigate to HRM > Job Descriptions
2. Click "New Job Description"
3. Enter title "Senior Software Engineer"
4. Select department "Engineering" (from FA)
5. Set hierarchy level 4
6. Add description: "Leads technical development..."
7. Add responsibilities (5 items)
8. Add qualifications (3 items)
9. Select required competencies (8 items with levels)
10. Save as Draft
11. System creates job description v1

**Postconditions**: Job description exists in draft status

---

## UC-JD-002: Use Job Description Template
**Actor**: HR Manager

**Preconditions**: Existing templates

**Flow**:
1. Create new job description
2. Click "Use Template"
3. Select "Software Engineer Standard"
4. Template populates:
   - Default responsibilities
   - Default qualifications
   - Suggested competencies
5. Modify for specific role
6. Save

**Postconditions**: Job description created from template

---

## UC-JD-003: Submit for Approval
**Actor**: HR Manager, Department Head

**Preconditions**: Job description in draft status

**Flow**:
1. Open draft job description
2. Review all fields
3. Click "Submit for Approval"
4. System sends notification to department head
5. Status changes to "Pending Approval"

**Postconditions**: Status = Pending Approval, approver notified

---

## UC-JD-004: Approve Job Description
**Actor**: Department Head

**Preconditions**: Job description pending approval

**Flow**:
1. Receive notification of pending job description
2. Review details, responsibilities, competencies
3. Click "Approve"
4. Enter approval comment
5. Confirm

**Postconditions**: Status = Active, approver recorded

---

## UC-JD-005: Reject Job Description
**Actor**: Department Head

**Preconditions**: Job description pending approval

**Flow**:
1. Review job description
2. Identify issues (missing competencies, incorrect level)
3. Click "Reject"
4. Enter rejection reason: "Missing cloud competencies level 4"
5. Confirm

**Postconditions**: Status = Draft, creator notified with reason

---

## UC-JD-006: Add Competencies
**Actor**: HR Manager

**Preconditions**: Job description exists

**Flow**:
1. Open job description
2. Click "Add Competency"
3. Search "Python"
4. Select from suggestions: "Python Programming"
5. Set required level: 4
6. Set importance: "Required"
7. Add notes: "Must have Django experience"
8. Save

**Postconditions**: Competency linked to job description

---

## UC-JD-007: View Competency Matrix
**Actor**: HR Manager, Department Head

**Preconditions**: Competencies and jobs exist

**Flow**:
1. Navigate to HRM > Competency Matrix
2. Filter by department "Engineering"
3. View matrix grid:
   - Rows: Job descriptions
   - Columns: Competencies
   - Cells: Required level (1-5)
4. Click competency header for details
5. Export matrix to Excel

**Postconditions**: Matrix displayed, exported

---

## UC-JD-008: Competency Gap Analysis
**Actor**: HR Manager, Manager

**Preconditions**: Employee, job description, competency assessments

**Flow**:
1. Open job description "Senior Software Engineer"
2. Click "Gap Analysis"
3. Select employee "John Smith"
4. System calculates:
   - Required: Python (4), Agile (3), Leadership (3)
   - Current: Python (3), Agile (3), Leadership (2)
5. Display gaps:
   - Python: Gap -1
   - Leadership: Gap -1
6. Recommend training

**Postconditions**: Gap analysis displayed, training plan suggested

---

## UC-JD-009: Archive Job Description
**Actor**: HR Manager

**Preconditions**: Job description exists (active or draft)

**Flow**:
1. Open job description
2. Click "Archive"
3. Confirm archive action
4. System sets status to "Archived"
5. Job no longer appears in active list

**Postconditions**: Job description archived, preserved for history

---

## UC-JD-010: Create Competency
**Actor**: HR Manager, Admin

**Preconditions**: Admin permission

**Flow**:
1. Navigate to Setup > Competency Admin
2. Click "Add Competency"
3. Enter name "Cloud Architecture"
4. Select category "Technical"
5. Add description: "Design and implement cloud solutions"
6. Define proficiency levels:
   - L1: Understands basics
   - L2: Can implement basic designs
   - L3: Designs complex solutions
   - L4: Architects enterprise systems
   - L5: Defines organizational strategy
7. Save

**Postconditions**: Competency available in library

---

## UC-JD-011: Search Job Descriptions
**Actor**: HR Manager, Hiring Manager

**Preconditions**: Job descriptions exist

**Flow**:
1. Navigate to Job Descriptions list
2. Enter search query "engineer"
3. Results filter in real-time
4. Apply department filter "Engineering"
5. Apply status filter "Active"
6. Results: "Senior Software Engineer", "Software Engineer", "QA Engineer"

**Postconditions**: Filtered results displayed

---

## UC-JD-012: Link to Position (Recruitment)
**Actor**: HR Manager

**Preconditions**: ksf_Recruitment installed

**Flow**:
1. Create job description
2. Click "Link to Position"
3. Open recruitment module
4. Select/create position "Senior Software Engineer"
5. System links job description to position
6. Position displays job description details

**Postconditions**: Recruitment position linked

---

## UC-JD-013: Version History
**Actor**: HR Manager

**Preconditions**: Job description has been edited

**Flow**:
1. Open job description
2. Click "Version History"
3. View list:
   - v3: 2026-05-10 - Added AWS competency
   - v2: 2026-05-05 - Updated responsibilities
   - v1: 2026-04-28 - Initial creation
4. Click version to view
5. Compare versions

**Postconditions**: Version history displayed

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*