# Architecture Design
Browser → Laravel → Route → Livewire/Controller → Form Request → Service → Eloquent → MySQL

## TALL
- Tailwind: styling
- Alpine: lightweight browser interactions
- Laravel: backend/application infrastructure
- Livewire: reactive server-driven UI

## Service Layer
Business logic belongs in services.

## Repository
Repository Pattern is not used by default. Use Eloquent directly through Services. Introduce repositories only for a concrete need.

## Authorization
Use Laravel Policies.

## File Storage
Use Laravel Storage and generated filenames.
