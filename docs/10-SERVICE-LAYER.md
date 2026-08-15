# Service Layer
Services own business logic and workflow.

Responsibilities:
- create
- update
- delete
- calculations
- status transitions
- transactions
- orchestration

Use validated arrays instead of passing Request objects into Services.

Use DB::transaction() for multi-step writes.
