Feature: Visitors can log in and become users
  In order to add and rate recipes
  As a visitor
  I must be able to log in

  Scenario: Sucessfully logging in
    Given there is a user account with username "tom" and password "topsecret"
    When I login with user "tom" and password "topsecret"
    Then I should be logged in

  @todo
  Scenario: Attempting to log in with unknown user
    Given there is a user account with username "tom" and password "topsecret"
    When I login with user "unknown" and password "topsecret"
    Then I should not be logged in

  @todo
  Scenario: Attempting to log in with incorrect password
    Given there is a user account with username "tom" and password "topsecret"
    When I login with user "tom" and password "badpassword"
    Then I should not be logged in
