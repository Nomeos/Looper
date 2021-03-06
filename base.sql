-- -----------------------------------------------------
-- Table `looper`.`fulfillments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`fulfillments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `fulfillments_unique` (`date` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `looper`.`question_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`question_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `question_type_unique` (`label` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `looper`.`quiz_states`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`quiz_states` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) NOT NULL,
  `slug` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `quiz_state_unique` (`label` ASC) VISIBLE,
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `looper`.`quizzes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`quizzes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `is_public` TINYINT(4) NOT NULL DEFAULT 0,
  `quiz_state_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `quizzes_unique` (`title` ASC) VISIBLE,
  INDEX `fk_quizzes_quiz_states` (`quiz_state_id` ASC) VISIBLE,
  CONSTRAINT `fk_quizzes_quiz_states`
    FOREIGN KEY (`quiz_state_id`)
    REFERENCES `looper`.`quiz_states` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `looper`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`questions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) NOT NULL,
  `question_type_id` INT(11) NOT NULL,
  `quiz_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `questions_unique` (`label` ASC, `quiz_id` ASC) VISIBLE,
  INDEX `fk_question_question_type_idx` (`question_type_id` ASC) VISIBLE,
  INDEX `fk_question_questionnaire1_idx` (`quiz_id` ASC) VISIBLE,
  CONSTRAINT `fk_questions_question_types`
    FOREIGN KEY (`question_type_id`)
    REFERENCES `looper`.`question_types` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_questions_quizzes`
    FOREIGN KEY (`quiz_id`)
    REFERENCES `looper`.`quizzes` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `looper`.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`answers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `value` LONGTEXT NULL DEFAULT NULL,
  `question_id` INT(11) NOT NULL,
  `fulfillment_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `answers_unique` (`question_id` ASC, `fulfillment_id` ASC) VISIBLE,
  INDEX `fk_answers_questions_idx` (`question_id` ASC) VISIBLE,
  INDEX `fk_answers_fulfillments_idx` (`fulfillment_id` ASC) VISIBLE,
  CONSTRAINT `fk_answers_fulfillments`
    FOREIGN KEY (`fulfillment_id`)
    REFERENCES `looper`.`fulfillments` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_answers_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `looper`.`questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;