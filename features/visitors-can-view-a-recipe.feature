Feature: A visitor can view a recipe
  In order to drink exciting cocktails
  As a visitor
  I need to be able see a recipe and all it's measured ingredients

  Scenario: Viewing a recipe
    Given a list of measured ingredients:
      | name        | amount | units |
      | White Run   | 2      | fl oz |
      | Mint Leaves | 8      |       |
      | Lime        | 1      |       |
      | Sugar       | 2      | tsp   |
      | Soda        |        |       |
    And a method:
      """
      Instructions to make a Mojito.
      """
    And there's a recipe for "Mojito" by user "tom" with 5 stars, the measured ingredients and method added to the reciped list
    When I fetch and view the recipe "Mojito" by user "tom"
    Then I should be viewing the name, user, rating, measured ingredients and method of the recipe
