<?php

require dirname(dirname(__DIR__)) . "/paths.php";

use App\Service\UserBO;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Illuminate\Database\Capsule\Manager;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Xdebug;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $connection;

    private $userBO;

    private $users = [];

    private $coverage;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $driver = new Xdebug();
        $filter = new Filter();
        $filter->addDirectoryToWhitelist(ROOT . DS . "app/App");
        $this->coverage = new CodeCoverage($driver, $filter);
        $this->coverage->start("test");

        require dirname(dirname(__DIR__)) . "/app/database.php";

        $this->connection = Manager::connection();
        $this->userBO = new UserBO($this->connection);
        $this->connection->beginTransaction();
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->connection->rollBack();
        $this->coverage->stop();

        $writer = new Clover;
        $writer->process($this->coverage, ROOT . DS . "coverage.xml");

        $writer = new Facade();
        $writer->process($this->coverage, ROOT . DS . "code-coverage-report");
    }

    /**
     * @When /^I create an user with:$/
     * @When /^that exists the user:$/
     * @param TableNode $table
     */
    public function iCreateAnUserWith(TableNode $table)
    {
        $users = $table->getHash();
        foreach ($users as $user) {
            $user = $this->userBO->save($user);
            $this->users[$user->id] = $user;
        }
    }

    /**
     * @Then /^I show get the user with "([^"]*)" "([^"]*)"$/
     * @Then /^the user must have "([^"]*)" equals "([^"]*)"$/
     * @param string $field
     * @param string $value
     */
    public function iShowGetTheUserWith(string $field, string $value)
    {
        foreach ($this->users as $user) {
            if ($user->$field != $value) {
                throw new LogicException("{$user->$field} different from $value");
            }
        }
    }

    /**
     * @When /^I disable the user "([^"]*)"$/
     * @param string $userName
     */
    public function iDisableTheUser(string $userName)
    {
        $user = $this->userBO->search(['name' => $userName])[0];
        $this->users[$user->id] = $this->userBO->disable($user->id);
    }
}
