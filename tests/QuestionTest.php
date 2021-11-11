<?php
namespace Test;

require_once("vendor/autoload.php");
require_once('.env.php');

use App\models\QuestionType;
use ByJG\DbMigration\Database\MySqlDatabase;
use ByJG\DbMigration\Exception\DatabaseDoesNotRegistered;
use ByJG\DbMigration\Exception\DatabaseIsIncompleteException;
use ByJG\DbMigration\Exception\DatabaseNotVersionedException;
use ByJG\DbMigration\Exception\InvalidMigrationFile;
use ByJG\DbMigration\Exception\OldVersionSchemaException;
use ByJG\DbMigration\Migration;
use ByJG\Util\Uri;
use PHPUnit\Framework\TestCase;
use App\models\Question;
use ReflectionException;

class QuestionTest extends TestCase
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
      $this->assertCount(5, Question::all());
    }

    public function testFind()
    {
        $this->assertEquals(
            "Question2",
            Question::find(2)->label
        );
        $this->assertNotEquals(
            "Question1",
            Question::find(2)->label
        );

    }

    public  function testWhere()
    {
        $this->assertEquals(1,count(Question::where("label","Question1")));
        $this->assertInstanceOf(Question::class, Question::where("label", "Question1")[0]);
    }

    /**
     * @throws ReflectionException
     */
    public function testSave()
    {
        $question = Question::find(1);
        $question->label = "Question1";
        $question->save();

        $this->assertEquals(
            "Question1",
            Question::find(1)->label
        );

        $question->id = 0;
        // event though id is not a valid value
        // the database server ignores it because it is autoincremented
        $this->assertFalse($question->save());
    }

    /**
     * @throws ReflectionException
     */
    public function testDelete()
    {
        $question = Question::find(1);
        $question->delete();
        $this->assertNull(Question::find(1));

        $question = Question::find(4);
        $question->delete();
        $this->assertNull(Question::find(10));
    }

    public function testType()
    {
        $question = Question::find(1);
        $type = QuestionType::find(1);

        $this->assertEquals(
            $type->label,
            $question->type()->label
        );

        $question = Question::find(2);

        $this->assertNotEquals(
            $type->label,
            $question->type()->label
        );
    }

    public function testQuiz()
    {
        $question = Question::find(1);
        $quiz = $question->quiz();

        $this->assertEquals(
            "Building form",
            $quiz->title
        );

        $question = Question::find(2);
        $quiz = $question->quiz();

        $this->assertNotEquals(
            "Closed",
            $quiz->title
        );
    }

}
