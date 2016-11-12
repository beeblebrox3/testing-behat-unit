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
