-- -----------------------------------------------------
-- Table `looper`.`quiz_states`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`quiz_states` (
                                                      `id` INT NOT NULL AUTO_INCREMENT,
                                                      `label` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `looper`.`quizzes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`quizzes` (
                                                  `id` INT NOT NULL AUTO_INCREMENT,
                                                  `title` VARCHAR(45) NULL,
    `is_public` TINYINT NOT NULL DEFAULT 0,
    `quiz_state_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_quizzes_quiz_states` (`quiz_state_id` ASC) VISIBLE,
    UNIQUE INDEX `quizzes_unique` (`title` ASC) VISIBLE,
    CONSTRAINT `fk_quizzes_quiz_states`
    FOREIGN KEY (`quiz_state_id`)
    REFERENCES `looper`.`quiz_states` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `looper`.`question_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`question_types` (
                                                         `id` INT NOT NULL AUTO_INCREMENT,
                                                         `label` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `question_type_unique` (`label` ASC) VISIBLE)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `looper`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`questions` (
                                                    `id` INT NOT NULL AUTO_INCREMENT,
                                                    `label` VARCHAR(45) NOT NULL,
    `question_type_id` INT NOT NULL,
    `quiz_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_question_question_type_idx` (`question_type_id` ASC) VISIBLE,
    INDEX `fk_question_questionnaire1_idx` (`quiz_id` ASC) VISIBLE,
    UNIQUE INDEX `questions_unique` (`label` ASC, `quiz_id` ASC) VISIBLE,
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
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `looper`.`fullfillments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`fullfillments` (
                                                        `id` INT NOT NULL AUTO_INCREMENT,
                                                        `date` DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fullfillments_unique` (`date` ASC) VISIBLE)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `looper`.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `looper`.`answers` (
                                                  `id` INT NOT NULL AUTO_INCREMENT,
                                                  `value` VARCHAR(45) NULL,
    `question_id` INT NOT NULL,
    `fullfillment_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_answers_questions_idx` (`question_id` ASC) VISIBLE,
    INDEX `fk_answers_fullfillments_idx` (`fullfillment_id` ASC) VISIBLE,
    UNIQUE INDEX `answers_unique` (`question_id` ASC, `fullfillment_id` ASC) VISIBLE,
    CONSTRAINT `fk_answers_questions`
    FOREIGN KEY (`question_id`)
    REFERENCES `looper`.`questions` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_answers_fullfillments`
    FOREIGN KEY (`fullfillment_id`)
    REFERENCES `looper`.`fullfillments` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;