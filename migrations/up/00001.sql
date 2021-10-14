-- Quiz state
INSERT INTO `looper`.`quiz_states` (`label`) VALUES ('Building');
INSERT INTO `looper`.`quiz_states` (`label`) VALUES ('Answering');
INSERT INTO `looper`.`quiz_states` (`label`) VALUES ('Closed');

-- Quiz
INSERT INTO `looper`.`quizzes` (`title`, `is_public`, `quiz_state_id`) VALUES ('Building form', '0', '1');
INSERT INTO `looper`.`quizzes` (`title`, `is_public`, `quiz_state_id`) VALUES ('Answering form', '1', '2');
INSERT INTO `looper`.`quizzes` (`title`, `is_public`, `quiz_state_id`) VALUES ('Closed', '1', '3');

-- Question type
INSERT INTO `looper`.`question_types` (`label`) VALUES ('Single line text');
INSERT INTO `looper`.`question_types` (`label`) VALUES ('List of single lines');
INSERT INTO `looper`.`question_types` (`label`) VALUES ('Multi-line text');

-- Question
INSERT INTO `looper`.`questions` (`label`, `question_type_id`, `quiz_id`) VALUES ('Question1', '1', '1');
INSERT INTO `looper`.`questions` (`label`, `question_type_id`, `quiz_id`) VALUES ('Question2', '2', '2');
INSERT INTO `looper`.`questions` (`label`, `question_type_id`, `quiz_id`) VALUES ('Question3', '3', '3');
INSERT INTO `looper`.`questions` (`label`, `question_type_id`, `quiz_id`) VALUES ('Question4->belongs->1', '1', '1');
INSERT INTO `looper`.`questions` (`label`, `question_type_id`, `quiz_id`) VALUES ('Question5->belongs to 2', '1', '2');

-- Fullfillment
INSERT INTO `looper`.`fullfillments` (`date`) VALUES ('2021-01-14 09:00:00');
INSERT INTO `looper`.`fullfillments` (`date`) VALUES ('2021-01-14 10:00:00');

-- Answer
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('Answer to question1', '1', 1);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('Answer to question2', '2', 1);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('Answer to question3', '3', 1);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('Answer to question4', '4', 1);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('Answer to question5', '5', 1);

INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('', '1', 2);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('', '2', 2);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('', '3', 2);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('', '4', 2);
INSERT INTO `looper`.`answers` (`value`, `question_id`, `fullfillment_id`) VALUES ('', '5', 2);