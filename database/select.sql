use looper;

# Get list of answering quizs that are public (/exercises/answering)
SELECT quiz.title
FROM quiz
INNER JOIN quiz_state ON quiz_state.id = quiz.quiz_state_id
WHERE quiz_state.label = "Answering"
AND quiz.is_public = 1;

# Fetch building quizs (is_public = 0) (usefull to show button 'be ready for answers)
SELECT quiz.title, quiz_state.label AS state, COUNT(*) AS questions
FROM quiz
INNER JOIN quiz_state ON quiz_state.id = quiz.quiz_state_id
INNER JOIN question ON question.quiz_id = quiz.id
WHERE quiz.is_public = 1;

# Show statistics for a quiz
SELECT answer.date, answer.value
FROM answer
INNER JOIN question ON question.id = answer.question_id
INNER JOIN quiz ON quiz.id = question.quiz_id
WHERE quiz.id = 2;

# Fetch quiz's questions (TO BE FIXED!!!)
SELECT question.id, question.label, question_type.label
FROM question
INNER JOIN question_type ON question_type.id = question.question_type_id
WHERE question.quiz_id = 1;