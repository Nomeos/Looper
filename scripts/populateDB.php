<?php
require_once("./config/config.php");
require_once(".env.php");
require_once("vendor/autoload.php");

use ByJG\DbMigration\Database\MySqlDatabase;
use ByJG\DbMigration\Migration;
use ByJG\Util\Uri;
use Db\seeders\DatabaseSeeder;

function main()
{
    $seeder = new DatabaseSeeder();
    $connectionUri = new Uri(sprintf('mysql://%s:%s@localhost/looper', USERNAME, PASSWORD));

    // Create the Migration instance
    $migration = new Migration($connectionUri, '.');

    // Register the Database or Databases can handle that URI:
    $migration->registerDatabase('mysql', MySqlDatabase::class);
    $migration->registerDatabase('maria', MySqlDatabase::class);

    // Execute migrations (under migrations/up/*.sql)
    $migration->reset();
    $seeder->run();
}

main();