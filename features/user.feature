Feature: User manipulation

  Scenario: Create a new user
    When I create an user with:
      | name | email                 | active |
      | Luis | luish.faria@gmail.com | 1      |
    Then the user must have "active" equals "1"
    And the user must have "name" equals "Luis"
    And the user must have "email" equals "luish.faria@gmail.com"

  Scenario: Disable an user
    Given that exists the user:
      | name | email                 | active |
      | Luis | luish.faria@gmail.com | 1      |
    When I disable the user "Luis"
    Then the user must have "active" equals "0"

  Scenario: Enable an user
    Given that exists the user:
      | name | email                 | active |
      | Luis | luish.faria@gmail.com | 0      |
    When I enable the user "Luis"
    Then the user must have "active" equals "1"

  Scenario: Delete an user
    Given that exists the user:
      | name | email                 | active |
      | Luis | luish.faria@gmail.com | 0      |
    When I delete user "Luis"
    Then search for user "Luis" should get not results

  Scenario: Try to create an user with duplicated email address
    Given that exists the user:
      | name | email                 | active |
      | Luis | luish.faria@gmail.com | 0      |
    When I create an user with:
      | name   | email                 | active |
      | Luis 2 | luish.faria@gmail.com | 1      |
    Then I should get an error