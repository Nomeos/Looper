<?php

namespace Db\seeders;

use App\lib\db\Seeder;
use App\models\QuestionType;
use App\models\Quiz;

class QuestionTypeSeeder implements Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $question_type = QuestionType::make(["label" => "Single line text"]);
        $question_type->create();

        $question_type = QuestionType::make(["label" => "List of single lines"]);
        $question_type->create();

        $question_type = QuestionType::make(["label" => "Multi-line text"]);
        $question_type->create();
    }
}