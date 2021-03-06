<?php

namespace Db\seeders;

use App\lib\db\Seeder;
use App\models\QuizState;

class QuizStateSeeder implements Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $quiz_state = QuizState::make(["label" => "Building", "slug" => "BUILD"]);
        $quiz_state->create();

        $quiz_state = QuizState::make(["label" => "Answering", "slug" => "ANSW"]);
        $quiz_state->create();

        $quiz_state = QuizState::make(["label" => "Closed", "slug" => "CLOS"]);
        $quiz_state->create();
    }
}