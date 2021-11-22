<?php

namespace Db\seeders;

use App\lib\db\Seeder;
use App\models\Answer;

class AnswerSeeder implements Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $answer = Answer::make(["value" => "Answer to question1", "question_id" => 1, "fulfillment_id" => 1]);
        $answer->create();

        $answer = Answer::make(["value" => "Answer to question2", "question_id" => 2, "fulfillment_id" => 1]);
        $answer->create();

        $answer = Answer::make(["value" => "Answer to question3", "question_id" => 3, "fulfillment_id" => 1]);
        $answer->create();

        $answer = Answer::make(["value" => "Answer to question4", "question_id" => 4, "fulfillment_id" => 1]);
        $answer->create();

        $answer = Answer::make(["value" => "Answer to question5", "question_id" => 5, "fulfillment_id" => 1]);
        $answer->create();


        $answer = Answer::make(["value" => "", "question_id" => 1, "fulfillment_id" => 2]);
        $answer->create();

        $answer = Answer::make(["value" => "", "question_id" => 2, "fulfillment_id" => 2]);
        $answer->create();

        $answer = Answer::make(["value" => "", "question_id" => 3, "fulfillment_id" => 2]);
        $answer->create();

        $answer = Answer::make(["value" => "", "question_id" => 4, "fulfillment_id" => 2]);
        $answer->create();

        $answer = Answer::make(["value" => "", "question_id" => 5, "fulfillment_id" => 2]);
        $answer->create();
    }
}