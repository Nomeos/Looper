<?php
namespace Test;

require_once("vendor/autoload.php");
require_once('.env.php');

use App\models\Fulfillment;
use ByJG\DbMigration\Database\MySqlDatabase;
use ByJG\DbMigration\Exception\DatabaseDoesNotRegistered;
use ByJG\DbMigration\Exception\DatabaseIsIncompleteException;
use ByJG\DbMigration\Exception\DatabaseNotVersionedException;
use ByJG\DbMigration\Exception\InvalidMigrationFile;
use ByJG\DbMigration\Exception\OldVersionSchemaException;
use ByJG\DbMigration\Migration;
use ByJG\Util\Uri;
use PHPUnit\Framework\TestCase;
use App\models\QuestionType;
use ReflectionException;

class FullfillmentTest extends TestCase
{
    private Migration $migration;

    /**
     * @throws InvalidMigrationFile
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

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
        $this->migration->up(1);
    }

    public function testAll()
    {
      $this->assertCount(2, Fulfillment::all());
    }

    public function testFind()
    {
        $this->assertEquals(
            "2021-01-14 10:00:00",
            Fulfillment::find(2)->date
        );
        $this->assertNotEquals(
            "2021-02-23 10:00:00",
            Fulfillment::find(2)->date
        );
    }

    /**
     * @covers $question_type->create()
     */
    public function testCreate()
    {
        $fullfillment = new Fulfillment();
        $fullfillment->date = "2021-05-30 10:10:00";
        $this->assertTrue($fullfillment->create());
        $this->assertFalse($fullfillment->create());
    }

    public  function testWhere()
    {
        $this->assertEquals(
            1,
            Fulfillment::where("date", "2021-01-14 09:00:00")[0]->id
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testSave()
    {
        $fullfillment = Fulfillment::find(1);
        $fullfillment->date = "2021-08-13 13:34:12";
        $fullfillment->save();

        $this->assertEquals(
            "2021-08-13 13:34:12",
            Fulfillment::find(1)->date
        );

        // TODO test id update (try to set id to null or 0)
    }

    /**
     * @throws ReflectionException
     */
    public function testDelete()
    {
        $fullfillment = Fulfillment::find(1);
        $fullfillment->delete();
        $this->assertNull(Fulfillment::find(1));
    }

    public function testAnswers()
    {
        $fulfillment = Fulfillment::find(1);

        $this->assertCount(
            5,
            $fulfillment->answers()
        );

        $fulfillment = Fulfillment::find(2);

        $this->assertNotCount(
            6,
            $fulfillment->answers()
        );
    }
}
