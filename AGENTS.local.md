<!-- Repo-specific appendix to the shared AGENTS.md. Generic conventions live in AGENTS_ARCH.md (hardlinked). -->

# AGENTS.local.md — ksf_CRM
## Entity Base Class Pattern
### Legacy Inheritance Migration
**Historical Problem:** Legacy modules had extensive inheritance with:
- Magic methods (`__get`, `__set`) calling type validators
- Event notifications in setters (`NOTIFY_*`, `NOTIFY_LOG`)
- CRUD hooks via `hook_invoke_all()`
**Migration Pattern:** Replace inheritance with trait composition:
```php
// OLD: Deep inheritance hierarchy
class Customer extends BaseCRM {
    protected $name;
    public function __set($k, $v) {
        validate_type($k, $v);  // Type validation in magic setter
        $this->$k = $v;
        $this->notify("NOTIFY_SET_{$k}", $v);  // Event notification
    }
}
// NEW: Trait-based composition
class Customer {
    use ValidatableTrait;      // Type validation
    use EventEmitterTrait;     // Event notifications
    use EntityStateTrait;      // State tracking
    use TimestampTrait;         // Created/updated timestamps
    private ?string $name = null;
    public function setName(string $name): self
    {
        $this->assertNotEmptyString($name, 'name');
        $this->name = $name;
        $this->markModified();
        $this->emit('customer.name.changed', $name);
        return $this;
    }
}
```
### BaseEntity (Future: KSFII/Core)
A `Ksfraser\Core\BaseEntity` will be created in the future for common entity functionality. Until then, use traits:
```php
namespace Ksfraser\EmailManager\Entity;
use Ksfraser\Traits\ValidatableTrait;
use Ksfraser\Traits\TimestampTrait;
use Ksfraser\Traits\EntityStateTrait;
class MailingList {
    use ValidatableTrait;
    use TimestampTrait;
    use EntityStateTrait;
    private ?string $listName = null;
    // Fluent setters with validation
    public function setListName(string $name): self
    {
        $this->assertNotEmptyString($name, 'listName');
        $this->listName = $name;
        $this->markModified();
        return $this;
    }
    // Virtual getters via trait
    public function getListName(): ?string
    {
        return $this->listName;
    }
}
```
## Event/Hook Integration
### PSR-14 Event Dispatcher
```php
use Ksfraser\Traits\EventEmitterTrait;
class CustomerService {
    use EventEmitterTrait;
    public function create(array $data): Customer
    {
        $customer = new Customer($data);
        $customer->setName($data['name']);
        $this->emit('customer.created', $customer);
        return $customer;
    }
}
```
### FrontAccounting Hook Integration
FA modules use `hook_invoke_all()` for plugin extensibility:
```php
// In FA platform adapter
function customer_dispatch_event(string $eventName, $eventData = null): void
{
    // Dispatch PSR-14 event
    if ($container->has(EventDispatcherInterface::class)) {
        $dispatcher = $container->get(EventDispatcherInterface::class);
        $dispatcher->dispatch(new CustomerEvent($eventName, $eventData));
    }
    // Also call FA hook for legacy plugins
    if (function_exists('add_hook')) {
        add_hook($eventName, function($data) use ($eventData) {
            return $eventData;
        });
    }
}
```
## Development Workflow
All development is done in the **devel tree** (`~/Documents/ksf_CRM`).
### Workflow Steps
1. **Develop** in this repo (feature branches preferred)
2. **Test**: run repo-appropriate tests
3. **Lint**: `php -l` on modified PHP files (no syntax errors)
4. **Commit** and **Push** branch to GitHub
5. **Merge** to `master` when ready
6. **Push** `master` to GitHub
*No UAT bind point in `~/ksf_Infrastructure/fa_modules/ksf_CRM` — this repo is consumed via Composer path repos or other means.*
