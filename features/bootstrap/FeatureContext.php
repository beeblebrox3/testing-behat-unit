<?php
require dirname(dirname(__DIR__)) . "/paths.php";
require dirname(dirname(__DIR__)) . "/app/database.php";

use App\Service\UserBO;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Illuminate\Database\Capsule\Manager;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $connection;

    private $userBO;

    private $searchResult;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->connection = Manager::connection();
        $this->userBO = new UserBO($this->connection);
        $this->connection->beginTransaction();
    }

    public function __destruct()
    {
        $this->connection->rollBack();
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
            $this->userBO->save($user);
        }
    }

    /**
     * @Given /^search an user with "([^"]*)" "([^"]*)"$/
     * @param string $arg1
     * @param string $arg2
     */
    public function searchAnUserByWith(string $arg1, string $arg2)
    {
        $this->searchResult = $this->userBO->search([$arg1 => $arg2]);
    }

    /**
     * @Then /^I show get the user with "([^"]*)" "([^"]*)"$/
     * @Then /^the user must have "([^"]*)" equals "([^"]*)"$/
     * @param string $field
     * @param string $value
     */
    public function iShowGetTheUserWith(string $field, string $value)
    {
        foreach ($this->searchResult as $result) {
            if ($result->$field != $value) {
                throw new LogicException("{$result->$field} different from $value");
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
        $this->userBO->disable($user->id);
    }

    /**
     * @Then /^I user "([^"]*)" should be disabled$/
     * @param string $userName
     */
    public function iUserShouldBeDisabled(string $userName)
    {
        $user = $this->userBO->search(['name' => $userName])[0];
        if ($user->active != 0) {
            throw new LogicException("USer $userName is not disabled");
        }
    }
}
