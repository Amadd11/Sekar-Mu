# SEKAR-MU — Project Overview
## Stack
- Laravel 13
- TALL Stack: Tailwind CSS v4 (@tailwindcss/vite), Alpine.js, Laravel 13, Livewire
- Blade
- Laravel Breeze (authentication)
- spatie/laravel-permission (role & permission management)
- MySQL
- Pest (testing)

## Architecture
Route → Livewire/Controller → Form Request → Service → Eloquent Model → MySQL

## Core Domains
User, Institution, KEPK, Application, Application Information, Application Profile, Application Member, Self Assessment, Assessment, Research Protocol, Document, Review, Notification, Report.

## Naming
Do not use document codes such as B01, B01-01, B01-02 as source-code names. Use domain names such as Application, SelfAssessment, ResearchProtocol.

## Conventions
- Roles and statuses use Model string constants (e.g. User::ROLE_ADMIN, Application::STATUS_DRAFT). Do not use PHP Enums.
- Authorization via Spatie Permission (roles) + Laravel Policies (resource-level).
