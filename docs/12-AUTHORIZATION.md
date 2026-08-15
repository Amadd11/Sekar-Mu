# Authorization

## Role-Based Access Control (Spatie)
Use spatie/laravel-permission for role management.

Available roles: `admin`, `applicant`, `reviewer` (defined as constants on User model).

### Route Middleware
```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Admin routes
});

Route::middleware(['auth', 'role:reviewer'])->prefix('reviewer')->group(function () {
    // Reviewer routes
});
```

## Resource-Level Authorization (Policies)
Use Laravel Policies for resource-level checks.

ApplicationPolicy should cover:
- viewAny
- view
- create
- update
- delete
- submit
- review

Centralize authorization rather than duplicating checks.

## Blade Directives
```blade
@role('admin')
    {{-- Admin-only content --}}
@endrole

@can('update', $application)
    {{-- Show edit button --}}
@endcan
```
