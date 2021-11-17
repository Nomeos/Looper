<?php

namespace Db\seeders;

use App\lib\db\Seeder;
use App\models\Fulfillment;
use App\models\QuestionType;

class FulfillmentSeeder implements Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $fulfillment = Fulfillment::make(["date" => "2021-01-14 09:00:00"]);
        $fulfillment->create();

        $fulfillment = Fulfillment::make(["date" => "2021-01-14 10:00:00"]);
        $fulfillment->create();
    }
}