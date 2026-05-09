# AGENTS.md - Development Guidelines

> **DO NOT MODIFY THIS FILE.** Create `AGENTS.local.md` for project-specific overrides.

## Core Philosophy

This project follows enterprise-grade software engineering principles. Every decision should align with: **SOLID**, **DRY**, **SRP**, **DI**, and **TDD**.

---

## Repository Architecture

### Pattern: Business Logic + Platform Adapters

```
Ksfraser\<Module>\           # Business logic (framework-agnostic)
    └── src/Ksfraser/<Module>/

ksf_<Platform>_<Module>/    # Platform-specific UI & DB adapters
    └── src/Ksfraser/<Platform>/<Module>/
```

**Examples:**
- `ksf_CRM/` → Business logic (Ksfraser\CRM)
- `ksf_FA_CRM/` → FrontAccounting adapter (Ksfraser\FA\CRM)
- `ksf_WP_CRM/` → WordPress adapter (Ksfraser\WP\CRM)

---

## Namespace Convention

```php
Ksfraser\<Module>\           # Generic business logic
Ksfraser\<Platform>\<Module> # Platform-specific (FA, WP, WOO, etc.)
Ksfraser\Exceptions\...      # Shared exception library
Ksfraser\Traits\...          # Shared trait library
```

**Examples:**
- `Ksfraser\CRM\Exception\CRMException` → Local, module-specific
- `Ksfraser\Exceptions\CRM\CRMException` → Shared library
- `Ksfraser\Exceptions\Domain\EntityNotFoundException` → Generic domain

---

## Dependency Libraries

Always prefer shared libraries over local duplication:

| Library | Purpose | Namespace |
|---------|---------|-----------|
| `ksfraser/exceptions` | Exception hierarchy | `Ksfraser\Exceptions\` |
| `ksfraser/traits` | Reusable traits | `Ksfraser\Traits\` |
| `ksfraser/famock` | FA testing utilities | `Ksfraser\FAMock\` |
| `ksfraser/validation` | Validation helpers | `Ksfraser\Validation\` |

---

## Coding Standards

### PHP Compatibility
- **Minimum**: PHP 7.3
- **Recommended**: PHP 8.0+
- Always use `declare(strict_types=1);`

### Naming Conventions
- **Classes**: PascalCase (`CustomerService`)
- **Methods/Functions**: camelCase (`getCustomerById`)
- **Constants**: UPPER_SNAKE_CASE (`MAX_RETRY_COUNT`)
- **Files**: Match class name (`CustomerService.php`)

### DocBlock Standards
```php
/**
 * Create a new customer record.
 *
 * @param int $debtorNo The customer debtor number
 * @param array $data Customer data array
 * @return Customer The created customer entity
 * @throws CRMCustomerAlreadyExistsException If customer exists
 *
 * @since 1.0.0
 * @see CustomerRepository::update()
 */
public function create(int $debtorNo, array $data): Customer
```

**Required tags**: `@param`, `@return`, `@throws`, `@since`
**Optional tags**: `@see`, `@link`, `@deprecated`

---

## Documentation Requirements

### Code Documentation
- All classes, methods, and complex logic require PHPDoc
- Include `@UML` reference for architecture diagrams
- Include `@BABOK` reference for requirements alignment

### Project Documents (`doc/ProjectDocuments/`)
```
doc/ProjectDocuments/
├── ProjectDcs/              # Project deployment & configuration
│   ├── Architecture.md
│   ├── Functional Requirements.md
│   ├── Test Plan.md
│   └── UAT Plan.md
├── BABOK/                   # Business Analysis Body of Knowledge
├── UML/                     # Class, sequence, component diagrams
└── RTM/                     # Requirements Traceability Matrix
```

### UML Generation
- Use `phpuml` or equivalent for class diagrams
- Document complex functions with sequence diagrams
- Update diagrams when architecture changes

---

## Testing Standards

### TDD Workflow
1. **RED**: Write failing test
2. **GREEN**: Write minimal code to pass
3. **REFACTOR**: Improve while keeping tests green

### Coverage Requirements
- **Target**: 100% code coverage
- Skipped tests = failed tests (treat as incomplete)
- All new code requires tests

### Test Structure
```php
namespace Ksfraser\Tests\Unit;

use PHPUnit\Framework\TestCase;

class CustomerServiceTest extends TestCase
{
    public function testCreateCustomerSuccess(): void
    {
        // Arrange
        $service = new CustomerService($this->createMockRepo());

        // Act
        $customer = $service->create(['name' => 'Test']);

        // Assert
        $this->assertInstanceOf(Customer::class, $customer);
    }
}
```

---

## Git & Version Control

### Branch Naming
- `main` / `master` - Production-ready code
- `feature/*` - New features
- `fix/*` - Bug fixes
- `refactor/*` - Code refactoring

### Commit Messages
```
type(scope): description

feat(crm): add customer segmentation
fix(calendar): resolve timezone issue
refactor(exceptions): simplify hierarchy
docs(readme): update installation steps
```

### .gitignore Requirements
```
/vendor/
/composer.lock
.phpunit.cache/
.idea/
.vscode/
```

**Never track vendor/ or composer.lock** - each developer runs `composer install`.

---

## Dependency Management

### Repository Configuration
```json
{
    "repositories": [
        {"type": "vcs", "url": "https://github.com/ksfraser/Exceptions"},
        {"type": "vcs", "url": "https://github.com/ksfraser/Traits"}
    ],
    "require": {
        "ksfraser/exceptions": "^1.0",
        "ksfraser/traits": "^1.0"
    }
}
```

---

## Design Patterns

### Repository Pattern
- Data access abstraction
- Interface-based design
- Testable without database

### Service Layer Pattern
- Business logic isolation
- Transaction management
- Event dispatching

### Factory Pattern
- Complex object creation
- Dependency resolution
- Configuration-driven

---

## Exception Handling

### Hierarchy
```
\Exception (or \RuntimeException)
└── Ksfraser\Exceptions\Domain\...
└── Ksfraser\Exceptions\Utility\...
└── Ksfraser\Exceptions\<Module>\...
```

### Module-Specific Exceptions
- Create under `Ksfraser\Exceptions\<Module>\`
- Extend appropriate base exception
- Use factory methods for common cases

---

## SOLID Principles Checklist

| Principle | Description | Checklist |
|-----------|-------------|-----------|
| **S**ingle Responsibility | One class, one purpose | Class has one reason to change |
| **O**pen/Closed | Open for extension, closed for modification | Use interfaces and abstraction |
| **L**iskov Substitution | Subtypes substitutable for base types | Child classes honor parent contracts |
| **I**nterface Segregation | Small, focused interfaces | Don't force unused methods |
| **D**ependency Inversion | Depend on abstractions | Inject dependencies via constructor |

---

## Code Review Checklist

- [ ] All new code has tests (100% coverage target)
- [ ] PHPDoc complete with `@param`, `@return`, `@throws`, `@since`
- [ ] No hardcoded values (use constants/config)
- [ ] No duplicate code (extract to shared library)
- [ ] Dependencies injected, not instantiated
- [ ] Exception handling for all external calls
- [ ] `.gitignore` excludes vendor/ and composer.lock
- [ ] ProjectDocs updated for architecture changes
- [ ] UML diagrams updated for complex functions

---

## Workflow Guidelines

### Starting New Work
1. Create feature branch from main
2. Write tests first (TDD)
3. Implement minimal code to pass
4. Refactor while keeping tests green
5. Update documentation

### Before Committing
- Run full test suite
- Run linter (`phpcs --standard=PSR12`)
- Update UML if architecture changed
- Update RTM if requirements added

### Pull Request Requirements
- All tests passing
- Code coverage maintained or improved
- Documentation updated
- No merge conflicts

---

## Local Overrides

To override these guidelines for a specific project, create:

```markdown
# AGENTS.local.md
# Project-specific overrides for ksf_<project>

## Override 1
[Your overrides here]
```

**Note**: Core principles (SOLID, DRY, TDD) cannot be overridden.