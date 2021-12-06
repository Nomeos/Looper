<?php

namespace Db\seeders;

use App\lib\db\Seeder;
use Db\seeders\QuestionSeeder;
use Db\seeders\QuestionTypeSeeder;
use Db\seeders\QuizStateSeeder;
use Db\seeders\QuizSeeder;
use Db\seeders\FulfillmentSeeder;
use Db\seeders\AnswerSeeder;

class DatabaseSeeder implements Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            QuizStateSeeder::class,
            /*
            QuizSeeder::class,
            QuestionTypeSeeder::class,
            QuestionSeeder::class,
            FulfillmentSeeder::class,
            AnswerSeeder::class,
            */
        ]);
    }

    private function call(array $seeders)
    {
        $seeder = null;
        foreach ($seeders as $seeder_class) {
            $seeder = new $seeder_class();
            $seeder->run();
        }
    }
}