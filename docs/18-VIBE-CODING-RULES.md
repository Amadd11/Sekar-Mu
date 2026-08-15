# Vibe Coding Master Rules — Sekar-Mu

You are a Senior Laravel Developer building Sekar-Mu with Laravel 13 and TALL Stack.

## Stack
Laravel 13, PHP, MySQL, Livewire, Blade, Alpine.js, Tailwind CSS, Laravel Breeze, spatie/laravel-permission, Pest.

## Architecture
Route → Livewire/Controller → Form Request → Service → Model → Database

## Rules
1. Never use document codes as source-code names.
2. Business logic belongs in Services.
3. Validation belongs in Form Requests.
4. Authorization belongs in Policies + Spatie Permission (role-based).
5. Use Eloquent by default; no Repository Pattern unless concretely needed.
6. Use Livewire only when reactive behavior is useful.
7. Use Blade for presentation.
8. Use Alpine for lightweight client-side interaction.
9. Use Tailwind for styling.
10. Avoid fat controllers/components.
11. Use DB::transaction() for multi-step writes.
12. Prevent N+1 with eager loading.
13. Paginate list pages.
14. Prefer typed properties and return types.
15. Avoid premature abstraction.
16. Every feature should include the necessary migration, model, request, service, policy, component/view, route, and tests.
17. Check existing code before generating new files.
18. Preserve existing conventions.
19. Do not invent database columns or business rules that are not specified.
20. Do not use PHP Enums; use Model string constants for roles and statuses.
21. Keep implementations readable, secure, testable, and maintainable.

### Constants Pattern Example
```php
// ✅ Correct — string constants on Model
class Application extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SUBMITTED,
    ];
}

// ❌ Wrong — PHP Enum
enum ApplicationStatus: string
{
    case DRAFT = 'draft';
}
```

## Feature Output Order
1. Files to create/update
2. Migration
3. Model
4. Form Request
5. Service
6. Policy
7. Livewire Component
8. Blade View
9. Route
10. Tests
