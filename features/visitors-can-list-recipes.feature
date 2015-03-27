Feature: A visitor can view a list of recipes
  In order to get me to keep returning to the site
  As a visitor
  I want to see highly rated cocktails

  Scenario: View an empty list of recipes
    Given the recipe list is empty
    When I view the recipe list
    Then I should find that the results are empty

  # @todo - Improve business value
  Scenario: Viewing a list of recipes
    Given a recipe for "Mojito" by user "tom" rated with 5 stars has been added to the recipe list
    And a recipe for "Daquiri" by user "clare" rated with 4 stars has been added to the recipe list
    When I view the recipe list
    Then I should find "Mojito" by user "tom" with 5 stars in the results
    And I should find "Daquiri" by user "clare" with 4 stars in the results

  Scenario: View a list of several recipes
    Given a recipe for "Daquiri" rated with 4 stars has been added to the recipe list
    And a recipe for "Pina Colada" rated with 2 stars has been added to the recipe list
    And a recipe for "Mojito" rated with 5 stars has been added to the recipe list
    When I view the recipe list
    Then I should find the results have the highest rated recipes at the top of recipe list
