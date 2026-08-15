# SEKAR-MU — Project Overview
## Stack
- Laravel 13
- TALL Stack: Tailwind CSS, Alpine.js, Laravel, Livewire
- Blade
- Laravel Breeze
- MySQL

## Architecture
Route → Livewire/Controller → Form Request → Service → Eloquent Model → MySQL

## Core Domains
User, Institution, KEPK, Application, Application Information, Application Profile, Self Assessment, Assessment, Research Protocol, Document, Review, Notification, Report.

## Naming
Do not use document codes such as B01, B01-01, B01-02 as source-code names. Use domain names such as Application, SelfAssessment, ResearchProtocol.
