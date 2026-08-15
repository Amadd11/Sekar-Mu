# Database Dictionary

## users
id, name, email, password, timestamps

Roles managed via spatie/laravel-permission (tables: roles, model_has_roles, model_has_permissions, role_has_permissions).

## institutions
id, name, address, city, phone, email, timestamps

## kepks
id, institution_id, name, code, status, timestamps

## applications
id, user_id, kepk_id, status, submitted_at, timestamps, softDeletes

## application_informations
id, application_id, name, abbreviation, address, city, phone, email, timestamps

## application_profiles
id, application_id, description, vision, mission, timestamps

## application_members
id, application_id, name, position, email, phone, timestamps

## assessment_sections
id, name, order, timestamps

## assessment_groups
id, assessment_section_id, name, order, timestamps

## assessment_items
id, assessment_group_id, question, order, timestamps

## assessment_answers
id, application_id, assessment_item_id, score, comment, evidence, timestamps

## research_protocols
id, application_id, protocol_number, title, principal_investigator, submission_date, status, timestamps

## documents
id, application_id, uploaded_by, original_name, stored_name, path, mime_type, size, timestamps

## application_reviewers
id, application_id, user_id, assigned_at, timestamps

## reviews
id, application_id, user_id, decision, comment, reviewed_at, timestamps

## review_comments
id, review_id, user_id, comment, timestamps
