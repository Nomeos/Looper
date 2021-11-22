<?php

namespace Db\seeders;

use App\lib\db\Seeder;
use App\models\Quiz;
use App\models\QuizState;

class QuizSeeder implements Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $quiz = Quiz::make(["title" => "Building form", "is_public" => 0, "quiz_state_id" => 1]);
        $quiz->create();

        $quiz = Quiz::make(["title" => "Answering form", "is_public" => 1, "quiz_state_id" => 2]);
        $quiz->create();

        $quiz = Quiz::make(["title" => "Closed", "is_public" => 1, "quiz_state_id" => 3]);
        $quiz->create();
    }
}