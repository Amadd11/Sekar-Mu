# Routes
GET /dashboard

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

GET /reviewer/applications
GET /reviewer/applications/{application}
POST /reviewer/applications/{application}/review

GET /reports
