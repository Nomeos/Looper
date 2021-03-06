<?php

namespace Test;

require_once("vendor/autoload.php");
require_once('.env.php');

use App\lib\db\Seeder;
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
use App\models\Quiz;
use ReflectionException;

class QuizTest extends TestCase
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
      $this->assertCount(3, Quiz::all());
    }

    public function testFind()
    {
        $this->assertEquals(
            "Answering form",
            Quiz::find(2)->title
        );
        $this->assertNotEquals(
            "Building form 123",
            Quiz::find(2)->title
        );

    }

    public function testWhere()
    {
        $this->assertEquals(1,count(Quiz::where("title","Building form")));
    }

    /**
     * @covers $quiz->create()
     */
    public function testCreate()
    {
        $quiz = new Quiz();
        $quiz->title = "QuizToto";
        $quiz->is_public = 1;
        $quiz->quiz_state_id = 1;
        $this->assertTrue($quiz->create());

        $this->expectException(\PDOException::class);
        $quiz->create();
    }

    /**
     * @throws ReflectionException
     */
    public function testSave()
    {
        $quiz = Quiz::find(2);
        $quiz->title = "QuizTest";
        $quiz->save();

        $this->assertEquals(
            "QuizTest",
            Quiz::find(2)->title
        );

        // test boolean value update
        $quiz->is_public = 0;
        $quiz->save();

        $this->assertEquals(
            false,
            Quiz::find(2)->is_public
        );

        // TODO test id update (try to set id to null or 0)
    }

    /**
     * @throws ReflectionException
     */
    public function testDelete()
    {
        $quiz = Quiz::find(1);
        $quiz->delete();
        $this->assertNull(Quiz::find(1));

        $quiz = Quiz::find(3);
        $quiz->delete();
        $this->assertNull(Quiz::find(5));
    }

    public function testState()
    {
        $quiz = Quiz::find(2);
        $quiz_state = QuizState::find(2);

        $this->assertEquals(
            $quiz_state->label,
            $quiz->state()->label
        );

        $quiz = Quiz::find(1);

        $this->assertNotEquals(
            $quiz_state->label,
            $quiz->state()->label
        );
    }

    public function testQuestions()
    {
        $quiz = Quiz::find(1);
        $questions = $quiz->questions();

        $this->assertCount(
            2,
            $questions
        );

        $this->assertEquals(
            "Question1",
            $questions[0]->label
        );

        $quiz = Quiz::find(2);
        $questions = $quiz->questions();

        $this->assertNotCount(
            3,
            $questions
        );

        $this->assertNotEquals(
            "Question2",
            $questions[1]->label
        );
    }

    public function testFilterByState()
    {
        $this->assertCount(1, Quiz::filterByState("ANSW"));
        $this->assertCount(0, Quiz::filterByState("Toto"));
    }

    public function testIsEditable()
    {
        // building quiz
        $quiz = Quiz::find(1);
        $this->assertTrue($quiz->isEditable());

        // answering quiz
        $quiz = Quiz::find(2);
        $this->assertFalse($quiz->isEditable());

        // closed quiz
        $quiz = Quiz::find(3);
        $this->assertFalse($quiz->isEditable());
    }

    public function testIsAnswerable()
    {
        // building quiz
        $quiz = Quiz::find(1);
        $this->assertFalse($quiz->isAnswerable());

        // answering quiz
        $quiz = Quiz::find(2);
        $this->assertTrue($quiz->isAnswerable());

        // closed quiz
        $quiz = Quiz::find(3);
        $this->assertFalse($quiz->isAnswerable());
    }

    public function testIsRemovable()
    {
        // building quiz
        $quiz = Quiz::find(1);
        $this->assertTrue($quiz->isRemovable());

        // answering quiz
        $quiz = Quiz::find(2);
        $this->assertFalse($quiz->isRemovable());

        // closed quiz
        $quiz = Quiz::find(3);
        $this->assertTrue($quiz->isRemovable());
    }

    public function testCanBeAnswered()
    {
        // building quiz
        $quiz = Quiz::find(1);
        $this->assertTrue($quiz->canBeAnswered());

        // building quiz
        $quiz = Quiz::find(2);
        $this->assertFalse($quiz->canBeAnswered());
    }

    public function canBeClosed()
    {
        // building quiz
        $quiz = Quiz::find(1);
        $this->assertFalse($quiz->canBeClosed());

        // building quiz
        $quiz = Quiz::find(2);
        $this->assertTrue($quiz->canBeClosed());
    }
}
