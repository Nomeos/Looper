<?php

namespace Db\seeders;

use App\lib\db\Seeder;
use App\models\Question;

class QuestionSeeder implements Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $question = Question::make(["label" => "Question1", "question_type_id" => 1, "quiz_id" => 1]);
        $question->create();

        $question = Question::make(["label" => "Question2", "question_type_id" => 2, "quiz_id" => 2]);
        $question->create();

        $question = Question::make(["label" => "Question3", "question_type_id" => 3, "quiz_id" => 3]);
        $question->create();

        $question = Question::make(["label" => "Question4->belongs->1", "question_type_id" => 1, "quiz_id" => 1]);
        $question->create();

        $question = Question::make(["label" => "Question5->belongs to 2", "question_type_id" => 1, "quiz_id" => 2]);
        $question->create();
    }
}