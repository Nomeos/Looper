use looper;

# Get list of answering questionnaires that are public (/exercises/answering)
SELECT questionnaire.title
FROM questionnaire
INNER JOIN questionnaire_state ON questionnaire_state.id = questionnaire.questionnaire_state_id
WHERE questionnaire_state.label = "Answering"
AND questionnaire.is_public = 1;

# Fetch building questionnaires (is_public = 0) (usefull to show button 'be ready for answers)
SELECT questionnaire.title, questionnaire_state.label AS state, COUNT(*) AS questions
FROM questionnaire
INNER JOIN questionnaire_state ON questionnaire_state.id = questionnaire.questionnaire_state_id
INNER JOIN question ON question.questionnaire_id = questionnaire.id
WHERE questionnaire.is_public = 1;

# Show statistics for a questionnaire
SELECT answer.date, answer.value
FROM answer
INNER JOIN question ON question.id = answer.question_id
INNER JOIN questionnaire ON questionnaire.id = question.questionnaire_id
WHERE questionnaire.id = 2;

# Fetch questionnaire's questions (TO BE FIXED!!!)
SELECT id, question.label, question_type.label
FROM question
INNER JOIN question_type ON question_type.id = question.question_type_id
WHERE question.questionnaire.id = 1;