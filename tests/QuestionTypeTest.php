<?php
namespace Test;

require_once("vendor/autoload.php");
require_once('.env.php');

use App\lib\db\Seeder;
use App\models\Question;
use ByJG\DbMigration\Database\MySqlDatabase;
use ByJG\DbMigration\Exception\DatabaseDoesNotRegistered;
use ByJG\DbMigration\Exception\DatabaseIsIncompleteException;
use ByJG\DbMigration\Exception\DatabaseNotVersionedException;
use ByJG\DbMigration\Exception\InvalidMigrationFile;
use ByJG\DbMigration\Exception\OldVersionSchemaException;
use ByJG\DbMigration\Migration;
use ByJG\Util\Uri;
use Db\seeders\DatabaseSeeder;
use PHPUnit\Framework\TestCase;
use App\models\QuestionType;
use ReflectionException;

class QuestionTypeTest extends TestCase
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
      $this->assertCount(3, QuestionType::all());
    }

    public function testFind()
    {
        $this->assertEquals(
            "List of single lines",
            QuestionType::find(2)->label
        );
        $this->assertNotEquals(
            "Multi-line text",
            QuestionType::find(2)->label
        );
    }

    /**
     * @covers $question_type->create()
     */
    public function testCreate()
    {
        $question_type = new QuestionType();
        $question_type->label = "QuestionTypeLabel5";
        $this->assertTrue($question_type->create());

        $this->expectException(\PDOException::class);
        $question_type->create();
    }

    public  function testWhere()
    {
        $this->assertEquals(
            1,
            QuestionType::where("id", 1)[0]->id
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testSave()
    {
        $question_type = QuestionType::find(1);
        $question_type->label = "QuestionType1";
        $question_type->save();

        $this->assertEquals(
            "QuestionType1",
            QuestionType::find(1)->label
        );

        // TODO test id update (try to set id to null or 0)
    }

    /**
     * @throws ReflectionException
     */
    public function testDelete()
    {
        $question_type = QuestionType::find(1);
        $question_type->delete();
        $this->assertNull(QuestionType::find(1));

        $this->assertNull(QuestionType::find(10));
    }

    public function testQuestions()
    {
        $type = QuestionType::find(1);
        $questions = $type->questions();


        $this->assertCount(
            3,
            $questions
        );

        $this->assertNotCount(
            2,
            QuestionType::find(1)->questions()
        );
    }
}
