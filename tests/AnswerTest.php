<?php

namespace Test;

require_once("vendor/autoload.php");
require_once('.env.php');

use App\lib\db\Seeder;
use App\models\Fulfillment;
use ByJG\DbMigration\Database\MySqlDatabase;
use ByJG\DbMigration\Exception\DatabaseDoesNotRegistered;
use ByJG\DbMigration\Exception\DatabaseIsIncompleteException;
use ByJG\DbMigration\Exception\DatabaseNotVersionedException;
use ByJG\DbMigration\Exception\InvalidMigrationFile;
use ByJG\DbMigration\Exception\OldVersionSchemaException;
use ByJG\DbMigration\Migration;
use ByJG\Util\Uri;
use Db\seeders\DatabaseSeeder;
use phpDocumentor\Reflection\DocBlock\Tags\See;
use PHPUnit\Framework\TestCase;
use App\models\Answer;
use ReflectionException;

class AnswerTest extends TestCase
{
    private Migration $migration;
    private Seeder $seeder;

    /**
     * @throws InvalidMigrationFile
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->seeder = new DatabaseSeeder();

        $connectionUri = new Uri(sprintf('mysql://%s:%s@localhost/looper', USERNAME, PASSWORD));

        // Create the Migration instance
        $this->migration = new Migration($connectionUri, '.');

        // Register the Database or Databases can handle that URI:
        $this->migration->registerDatabase('mysql', MySqlDatabase::class);
        $this->migration->registerDatabase('maria', MySqlDatabase::class);

        // Add a callback progress function to receive info from the execution
        $this->migration->addCallbackProgress(function ($action, $currentVersion, $fileInfo) {
            echo "$action, $currentVersion, ${fileInfo['description']}\n";
        });
    }

    /**
     * @throws DatabaseDoesNotRegistered
     * @throws DatabaseNotVersionedException
     * @throws InvalidMigrationFile
     * @throws DatabaseIsIncompleteException
     * @throws OldVersionSchemaException
     */
    public function setUp(): void
    {
        $this->migration->reset();
        $this->seeder->run();
    }

    public function testAll()
    {
      $this->assertCount(2, Fulfillment::all());
    }

    public function testFind()
    {
        $this->assertEquals(
            "Answer to question2",
            Answer::find(2)->value
        );
        $this->assertNotEquals(
            "Answer to question3",
            Answer::find(2)->value
        );

    }

    public  function testWhere()
    {
        $this->assertEquals(1,count(Answer::where("value","Answer to question1")));
    }

    /**
     * @covers $answer->create()
     */
    public function testCreate()
    {
        $fulfillment = New Fulfillment();
        $fulfillment->date = "2021-09-30 00:00:00";
        $fulfillment->create();

        $answer = new Answer();
        $answer->value = "My answer to question 1";
        $answer->question_id = 1;
        $answer->fulfillment_id = 3;
        $this->assertTrue($answer->create());
        // there is no way to check if an answer is unique
        // that is why I do not test if $answer->create() returns false
    }

    /**
     * @throws ReflectionException
     */
    public function testSave()
    {
        $answer = Answer::find(1);
        $answer->value = "Answer toto";
        $answer->save();

        $this->assertEquals(
            "Answer toto",
            Answer::find(1)->value
        );

        // TODO test id update (try to set id to null or 0)
    }

    /**
     * @throws ReflectionException
     */
    public function testDelete()
    {
        $answer = Answer::find(1);
        $answer->delete();
        $this->assertNull(Answer::find(1));

        $answer = Answer::find(4);

        $this->assertTrue($answer->delete());
        $this->assertNull(Answer::find(4));
    }

    public function testFulfillment()
    {
        $answer = Answer::find(1);

        $this->assertEquals(
            "2021-01-14 09:00:00",
            $answer->fulfillment()->date
        );

        $answer = Answer::find(2);

        $this->assertNotEquals(
            "2021-01-14 11:00:00",
            $answer->fulfillment()->date
        );
    }
}
