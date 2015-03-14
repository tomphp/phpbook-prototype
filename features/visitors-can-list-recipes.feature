Feature: A visitor can view a list of recipes
  In order to get me to keep returning to the site
  As a visitor
  I want to see highly rated cocktails

  Scenario: View an empty list of recipes
    Given the recipe list is empty
    Then I should find that there are no recipes in the recipe list

  Scenario: Viewing a list with 1 recipe
    Given a recipe for "Mojito" by user "tom" rated with 5 stars has been added to the recipe list
    Then I should be able to find recipe "Mojito" by user "tom" with 5 stars in the recipe list

  Scenario: View a list of several recipes
    Given a recipe for "Daquiri" rated with 4 stars has been added to the recipe list
    And a recipe for "Pina Colada" rated with 2 stars has been added to the recipe list
    And a recipe for "Mojito" rated with 5 stars has been added to the recipe list
    Then I should find the highest rated recipes at the top of the recipe list
