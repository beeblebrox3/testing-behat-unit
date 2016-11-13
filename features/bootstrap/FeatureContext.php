<?php

use App\Service\UserBO;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Illuminate\Database\Capsule\Manager;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $connection;

    private $userBO;

    private $users = [];

    private $lastInsertionError = false;

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

    /**
     *
     */
    public function __destruct()
    {
        $this->connection->rollBack();
    }

    /**
     * @param $field
     * @param $value
     * @return \Illuminate\Support\Collection
     */
    protected function usersBy($field, $value) {
        return $this->userBO->search([$field => $value]);
    }

    /**
     * @When /^I create an user with:$/
     * @When /^that exists the user:$/
     * @param TableNode $table
     */
    public function iCreateAnUserWith(TableNode $table)
    {
        $users = $table->getHash();
        $this->lastInsertionError = false;
        foreach ($users as $user) {
            try {
                $user = $this->userBO->save($user);
                $this->users[$user->id] = $user;
            } catch (\Exception $e) {
                $this->lastInsertionError = true;
                break;
            }
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
        $user = $this->usersBy('name', $userName)->first();
        $this->users[$user->id] = $this->userBO->disable($user->id);
    }

    /**
     * @When /^I enable the user "([^"]*)"$/
     * @param string $userName
     */
    public function iEnableTheUser(string $userName)
    {
        $user = $this->usersBy('name', $userName)->first();
        $this->users[$user->id] = $this->userBO->enable($user->id);
    }

    /**
     * @When /^I delete user "([^"]*)"$/
     * @param string $userName
     */
    public function iDeleteUser(string $userName)
    {
        $user = $this->usersBy('name', $userName)->first();
        $this->userBO->delete($user->id);
    }

    /**
     * @Then /^search for user "([^"]*)" should get not results$/
     * @param string $userName
     */
    public function searchForUserShouldGetNotResults(string $userName)
    {
        $users = $this->usersBy('name', $userName);
        if (!$users->isEmpty()) {
            throw new LogicException("USer $userName was found!");
        }
    }

    /**
     * @Then /^I should get an error$/
     */
    public function iShouldGetAnError()
    {
        if (!$this->lastInsertionError) {
            throw new LogicException("Last insert operation did not got an error!");
        }
    }
}
