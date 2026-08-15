# Routes

## Dashboard
GET /dashboard

## Applications (Applicant)
GET /applications
GET /applications/create
POST /applications
GET /applications/{application}
GET /applications/{application}/edit
PUT /applications/{application}
DELETE /applications/{application}

GET /applications/{application}/information
PUT /applications/{application}/information

GET /applications/{application}/profile
PUT /applications/{application}/profile

GET /applications/{application}/self-assessment
PUT /applications/{application}/self-assessment

GET /applications/{application}/protocols
GET /applications/{application}/protocols/create
POST /applications/{application}/protocols
GET /applications/{application}/protocols/{protocol}/edit
PUT /applications/{application}/protocols/{protocol}
DELETE /applications/{application}/protocols/{protocol}

GET /applications/{application}/documents
POST /applications/{application}/documents
DELETE /applications/{application}/documents/{document}
GET /applications/{application}/documents/{document}/download

POST /applications/{application}/submit

## Reviewer
GET /reviewer/applications
GET /reviewer/applications/{application}
POST /reviewer/applications/{application}/review

## Admin
GET /admin/dashboard
GET /admin/users
GET /admin/users/create
POST /admin/users
GET /admin/users/{user}/edit
PUT /admin/users/{user}
DELETE /admin/users/{user}
GET /admin/applications
GET /admin/applications/{application}
POST /admin/applications/{application}/assign-reviewer
GET /admin/reports
