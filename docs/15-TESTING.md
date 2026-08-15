# Testing Strategy

## Framework
Pest (built on PHPUnit). Use Pest syntax for all tests.

## Unit
ApplicationServiceTest, SelfAssessmentServiceTest, ResearchProtocolServiceTest.

## Feature
ApplicantCanCreateApplicationTest, ApplicantCanSubmitApplicationTest, ApplicantCanUpdateSelfAssessmentTest, ReviewerCanReviewApplicationTest.

## Authorization
ApplicantCannotAccessOtherApplicationTest, ReviewerCannotAccessUnassignedApplicationTest.

## Validation
Test invalid and valid request payloads.

## Livewire
Test component state, validation, save actions, and authorization.

## Spatie Roles
Test role assignment, middleware protection, and role-based access control.
