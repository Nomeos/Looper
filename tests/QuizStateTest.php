<?php
namespace Test;

require_once("vendor/autoload.php");
require_once('.env.php');

use App\lib\db\Seeder;
use App\models\Quiz;
use App\models\QuizState;
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

class QuizStateTest extends TestCase
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
      $this->assertCount(3, QuizState::all());
    }

    public function testFind()
    {
        $this->assertEquals(
            "Answering",
            QuizState::find(2)->label
        );
        $this->assertNotEquals(
            "Building",
            QuizState::find(2)->label
        );

    }

    public  function testWhere()
    {
        $this->assertEquals(1,count(QuizState::where("label","Building")));
    }

    /**
     * @covers $quiz_state->create()
     */
    public function testCreate()
    {
        $quiz_state = new QuestionType();
        $quiz_state->label = "QuizState1234";
        $this->assertTrue($quiz_state->create());

        $this->expectException(\PDOException::class);
        $quiz_state->create();
    }

    /**
     * @throws ReflectionException
     */
    public function testSave()
    {
        $quiz_state = QuizState::find(1);
        $quiz_state->label = "QuizState1";
        $quiz_state->save();

        $this->assertEquals(
            "QuizState1",
            QuizState::find(1)->label
        );

        // TODO test id update (try to set id to null or 0)
    }

    /**
     * @throws ReflectionException
     */
    public function testDelete()
    {
        $quiz_state = QuizState::find(1);
        $quiz_state->delete();
        $this->assertNull(QuizState::find(1));

        $quiz_state = QuizState::find(2);
        $quiz_state->delete();
        // check if delete() only deleted quiz_state 2
        $this->assertNotNull(QuizState::find(3));
    }

    public function testQuizzes()
    {
        $quizzes = QuizState::find(1)->quizzes();

        $this->assertCount(
            1,
            $quizzes
        );

        $quizzes = QuizState::find(2)->quizzes();

        $this->assertNotCount(
            2,
            $quizzes
        );
    }

    public function testDefaultState()
    {
        $quiz_state = QuizState::defaultState();
        $this->assertEquals("BUILD", $quiz_state->slug);

        $quiz_state = QuizState::defaultState();
        $this->assertNotEquals("CLOS", $quiz_state->slug);
    }

    public function testNext()
    {
        $quiz_state = QuizState::where("slug", "BUILD")[0];
        $this->assertEquals("ANSW", $quiz_state->next()->slug);

        $quiz_state = QuizState::where("slug", "ANSW")[0];
        $this->assertEquals("CLOS", $quiz_state->next()->slug);

        $quiz_state = QuizState::where("slug", "BUILD")[0];
        $this->assertNotEquals("CLOS", $quiz_state->next()->slug);
    }
}
