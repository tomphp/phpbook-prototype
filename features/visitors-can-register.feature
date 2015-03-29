Feature: Visitors can register
  In to rate and contribute fantastic cocktail recipes
  As a visitor
  I want to become a registered user

  # @todo Register over api
  @no-api
  Scenario: Register as a user
    Given I am a prospective user with username "tom", email "tom@example.com" and password "topsecret"
    When I register with the authentication service
    Then I should should be able to log in to the site as user "tom" with password "topsecret"

  @todo
  Scenario: Username is already taken
    Given there is a registered user with username "tom" and email "tom@example.com"
    Given I am a prospective user with username "tom", email "a.different@email.com"
    When I register with the authentication service expecting an error
    Then I should should get a duplicate username error

  @todo
  Scenario: Email address is already taken
    Given there is a registered user with username "tom" and email "tom@example.com"
    Given I am a prospective user with username "tom", email "a.different@email.com"
    When I register with the authentication service expecting an error
    Then I should should get a duplicate username error
