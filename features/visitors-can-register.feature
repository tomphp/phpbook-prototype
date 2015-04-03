# @todo Register over api
@no-api
Feature: Visitors can register
  In to rate and contribute fantastic cocktail recipes
  As a visitor
  I want to become a registered user

  Scenario: Register as a user
    Given I am a prospective user with username "tom", email "tom@example.com" and password "topsecret"
    When I register with the authentication service
    Then I should should be able to log in to the site as user "tom" with password "topsecret"

  Scenario: Username is already taken
    Given there is a user registered with the authenication service with username "tom" and email "tom@example.com"
    And I am a prospective user with username "tom", email "a.different@email.com" and password "topsecret"
    When I register with the authentication service
    Then I should should get a "username taken" error

  @todo
  Scenario: Email address is already taken
    Given there is a user registered with the authenication service with username "tom" and email "tom@example.com"
    And I am a prospective user with username "sam", email "tom@example.com" and password "topsecret"
    When I register with the authentication service
    Then I should should get an "email taken" error
