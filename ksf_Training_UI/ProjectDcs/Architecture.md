# Architecture - ksf_Training_UI

## Document Information
- **Module**: ksf_Training_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_Training_UI provides the FrontAccounting web interface layer for training management.

### 1.1 Namespace
```php
Ksfraser\FA\Training\UI\
```

### 1.2 Layer Pattern
```
ksf_Training_UI/
├── composer.json
├── ProjectDcs/
├── pages/
├── js/
├── src/Ksfraser/FA/Training/UI/
│   ├── Presenter/
│   │   ├── CatalogPresenter.php
│   │   ├── CoursePresenter.php
│   │   ├── EnrollmentPresenter.php
│   │   ├── SessionPresenter.php
│   │   └── AttendancePresenter.php
│   ├── Component/
│   │   ├── CourseCard.php
│   │   ├── EnrollmentForm.php
│   │   ├── SessionCalendar.php
│   │   ├── AttendanceSheet.php
│   │   ├── CompetencyGapChart.php
│   │   └── CertificateGenerator.php
│   └── Handler/
│       └── TrainingAjaxHandler.php
└── templates/
    └── training/
```

---

## 2. Presenter Layer

### 2.1 CatalogPresenter

```php
class CatalogPresenter {
    public function getCourses(array $filters): array;
    public function getCourseCategories(): array;
    public function searchCourses(string $query): array;
    public function getFeaturedCourses(): array;
}
```

### 2.2 CoursePresenter

```php
class CoursePresenter {
    public function getCourse(string $courseId): array;
    public function saveCourse(array $data): Course;
    public function getSessions(string $courseId): array;
    public function getCourseMaterials(string $courseId): array;
}
```

### 2.3 EnrollmentPresenter

```php
class EnrollmentPresenter {
    public function getEnrollments(array $filters): array;
    public function getEmployeeEnrollments(string $employeeId): array;
    public function enrollEmployee(string $employeeId, string $sessionId): Enrollment;
    public function cancelEnrollment(string $enrollmentId): bool;
    public function getCompletionStats(string $employeeId): array;
}
```

### 2.4 AttendancePresenter

```php
class AttendancePresenter {
    public function getAttendanceList(string $sessionId): array;
    public function markAttendance(string $sessionId, array $attendance): bool;
    public function getAttendanceReport(string $courseId): array;
    public function getCompletionCertificates(string $courseId): array;
}
```

---

## 3. Component Layer

| Component | Description |
|-----------|-------------|
| `CourseCard` | Course summary card |
| `EnrollmentForm` | Registration form |
| `SessionCalendar` | Training calendar view |
| `AttendanceSheet` | Checkbox attendance list |
| `CompetencyGapChart` | Skill gap visualization |
| `CertificateGenerator` | Certificate PDF |
| `CategoryFilter` | Course category filter |
| `ProgressBadge` | Enrollment progress |

---

## 4. AJAX Handler Layer

| Action | Method | Description |
|--------|--------|-------------|
| `trn_courses` | handleCourses | Course list |
| `trn_course_get` | handleCourseGet | Get course |
| `trn_course_save` | handleCourseSave | Create/update |
| `trn_sessions` | handleSessions | Session list |
| `trn_enroll` | handleEnroll | Enroll employee |
| `trn_cancel` | handleCancel | Cancel enrollment |
| `trn_attendance` | handleAttendance | Mark attendance |
| `trn_feedback` | handleFeedback | Submit feedback |
| `trn_certificate` | handleCertificate | Generate PDF |

---

## 5. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Training Catalog | `pages/training_catalog.php` | Browse courses |
| Course Edit | `pages/course_edit.php` | Create/edit course |
| Enrollments | `pages/enrollments.php` | Enrollment list |
| Sessions | `pages/sessions.php` | Schedule sessions |
| Attendance | `pages/attendance.php` | Track attendance |
| Training Calendar | `pages/training_calendar.php` | Calendar view |
| Reports | `pages/training_reports.php` | Analytics |

---

## 6. Integration Points

### 6.1 With ksf_JobDescriptions
```php
// Get required training from job descriptions
$jobDescService = container()->get(JobDescriptionServiceInterface::class);
```

### 6.2 With ksf_Performance
```php
// Link training to competency
$performanceService = container()->get(PerformanceServiceInterface::class);
```

### 6.3 With ksf_Documents
```php
// Store materials and certificates
$documentService = container()->get(DocumentServiceInterface::class);
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
