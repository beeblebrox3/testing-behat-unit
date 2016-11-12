Feature: User manipulation

  Scenario: Create a new user
    When I create an user with:
      | name | email                 | active |
      | Luis | luish.faria@gmail.com | 1      |
    And search an user with "email" "luish.faria@gmail.com"
    Then I show get the user with "name" "Luis"
    And the user must have "active" equals "1"

  Scenario: Disable an user
    Given that exists the user:
      | name | email                 | active |
      | Luis | luish.faria@gmail.com | 1      |
    When I disable the user "Luis"
    Then I user "Luis" should be disabled
