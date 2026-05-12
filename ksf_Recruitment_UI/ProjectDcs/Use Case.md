# Use Cases - ksf_Recruitment_UI

---

## UC-REC-001: Create Job Opening
**Actor**: HR Manager

**Preconditions**: User has HR access

**Flow**:
1. Navigate to HR > Recruitment > Job Openings
2. Click "New Opening"
3. Enter job details (title, department, location)
4. Link to job description
5. Set hiring manager
6. Define pipeline stages
7. Publish opening

---

## UC-REC-002: Search Candidates
**Actor**: Recruiter, Hiring Manager

**Preconditions**: Candidates in database

**Flow**:
1. Navigate to Candidates
2. Search by name, skills, or keywords
3. Filter by source, stage, date
4. View candidate cards
5. Click to view full profile

---

## UC-REC-003: Add Candidate to Pipeline
**Actor**: Recruiter

**Preconditions**: Job opening exists

**Flow**:
1. Search for candidate
2. View candidate profile
3. Click "Add to Pipeline"
4. Select job opening
5. Assign to "Applied" stage
6. Candidate appears in pipeline

---

## UC-REC-004: Move Candidate Through Pipeline
**Actor**: Hiring Manager, Recruiter

**Preconditions**: Candidate in pipeline

**Flow**:
1. Open pipeline view
2. Drag candidate chip to next column
3. Confirm stage change
4. System logs activity
5. Notification sent to candidate (optional)

---

## UC-REC-005: Schedule Interview
**Actor**: Recruiter

**Preconditions**: Candidate at interview stage

**Flow**:
1. Click candidate in pipeline
2. Click "Schedule Interview"
3. Select interview type (phone/video/onsite)
4. Choose interviewers
5. Check availability
6. Select time slot
7. Send calendar invites

---

## UC-REC-006: Submit Interview Feedback
**Actor**: Interviewer

**Preconditions**: Interview completed

**Flow**:
1. Navigate to My Interviews
2. Open completed interview
3. Fill feedback form:
   - Rating (1-5)
   - Strengths
   - Concerns
   - Recommendation
4. Submit feedback
5. System notifies recruiter

---

## UC-REC-007: Generate Offer
**Actor**: HR Manager

**Preconditions**: Candidate at offer stage

**Flow**:
1. Open candidate profile
2. Click "Generate Offer"
3. Enter offer details:
   - Salary
   - Start date
   - Benefits
4. System generates offer letter
5. Manager reviews
6. Send to candidate

---

## UC-REC-008: View Hiring Dashboard
**Actor**: HR Manager, Hiring Manager

**Preconditions**: Openings and candidates exist

**Flow**:
1. Navigate to Hiring Dashboard
2. View metrics:
   - Open positions
   - Candidates per opening
   - Time-to-hire
   - Conversion rates
3. Filter by department/period
4. Export reports

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
