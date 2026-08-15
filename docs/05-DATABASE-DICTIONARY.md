# Database Dictionary
## users
id, name, email, password, role, timestamps

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
