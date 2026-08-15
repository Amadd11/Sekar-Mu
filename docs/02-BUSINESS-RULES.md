# Business Rules

## Application
Statuses: draft, submitted, under_review, revision_required, resubmitted, approved, rejected.

Draft applications are editable. Submitted applications are locked according to workflow. Revision-required applications can be edited and resubmitted.

### Valid Status Transitions
```
draft → submitted
submitted → under_review
under_review → revision_required | approved | rejected
revision_required → resubmitted
resubmitted → under_review
```

### Editable Statuses
Only `draft` and `revision_required` allow editing by the applicant.

## Self Assessment
Each assessment item can have one answer per application. An answer may contain score, comment, and evidence.

## Research Protocol
Requires title, protocol number, principal investigator, submission date, and status.

## Documents
Store original filename, generated storage filename, path, MIME type, size, and uploader.

## Authorization
Applicants access their own applications. Reviewers access assigned applications. Admin access follows Spatie permissions and roles.
