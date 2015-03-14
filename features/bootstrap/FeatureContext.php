<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\MeasuredIngredient;
use CocktailRater\Domain\Method;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->recipeList = new RecipeList();
    }

    /**
     * @Given the recipe list is empty
     */
    public function theRecipeListIsEmpty()
    {
        // empty is a reserved word!
        $this->recipeList->emptyList();
    }

    /**
     * @Then I should find that there are no recipes in the recipe list
     */
    public function iShouldFindThatThereAreNoRecipesInTheRecipeList()
    {
        PHPUnit_Framework_Assert::assertEmpty($this->recipeList->findAll());
    }

    /**
     * @Given a recipe for :recipeName by user :username rated with :stars stars has been added to the recipe list
     */
    public function aRecipeForByUserRatedWithStarsHasBeenAddedToTheRecipeList($recipeName, $username, $stars)
    {
        $aRecipe = new Recipe($recipeName, new User($username), new Stars($stars));

        $this->recipeList->add($aRecipe);
    }

    /**
     * @Then I should be able to find recipe :recipeName by user :username with :stars stars in the recipe list
     */
    public function iShouldBeAbleToFindRecipeByUserWithStarsInTheRecipeList($recipeName, $username, $stars)
    {
        $recipe = $this->recipeList->findByNameAndUser($recipeName, new User($username));

        PHPUnit_Framework_Assert::assertEquals(new Stars($stars), $recipe->getRating());
    }

    /**
     * @Given a recipe for :recipeName rated with :stars stars has been added to the recipe list
     */
    public function aRecipeForRatedWithStarsHasBeenAddedToTheRecipeList($recipeName, $stars)
    {
        $aRecipe = new Recipe($recipeName, new User('dummy_user'), new Stars($stars));

        $this->recipeList->add($aRecipe);
    }

    /**
     * @Then I should find the highest rated recipes at the top of the recipe list
     */
    public function iShouldFindTheHighestRatedRecipesAtTheTopOfTheRecipeList()
    {
        $recipes = $this->recipeList->findAll();

        $ratingValues = array_map(function (Recipe $recipe) {
            return $recipe->getRating()->getValue();
        }, $recipes);

        $this->assertValuesAreSortedDescending($ratingValues);
    }

    /** @return boolean */
    private function assertValuesAreSortedDescending(array $values)
    {
        $sorted = $values;
        rsort($sorted);

        PHPUnit_Framework_Assert::assertEquals($sorted, $values);
    }

    /**
     * @Given a list of measured ingredients:
     */
    public function aListOfMeasuredIngredients(TableNode $table)
    {
        $aListOfMeasuredIngredients = [];

        foreach ($table->getColumnsHash() as $ingredient) {
            $aListOfMeasuredIngredients[] = new MeasuredIngredient(
                new Ingredient($ingredient['name']),
                new Amount($ingredients['amount'], $ingredients['units'])
            );
        }

        $this->measuredIngredientsList = $aListOfMeasuredIngredients;
    }

    /**
     * @Given a method:
     */
    public function aMethod(PyStringNode $string)
    {
        $this->method = new Method((string) $string);
    }

    /**
     * @Given there's a recipe for :name by user :username with :stars stars, the measured ingredients and method added to the reciped list
     */
    public function thereSARecipeForByUserWithStarsTheMeasuredIngredientsAndMethodAddedToTheRecipedList($name, $username, $stars)
    {
        $this->name = $name;
        $this->user = new User($username);
        $this->rating = new Stars($stars);

        $aRecipe = new Recipe(
            $this->name,
            $this->user,
            $this->rating,
            $this->measuredIngredientsList,
            $this->method
        );

        $this->recipeList->add($aRecipe);
    }

    /**
     * @When I fetch the recipe :recipeName by user :username
     */
    public function iFetchTheRecipeByUser($recipeName, $username)
    {
        $this->theRecipe = $this->recipeList->findByNameAndUser($recipeName, new User($username));
    }

    /**
     * @Then the recipe should have name, user, rating, measured ingredients and method
     */
    public function theRecipeShouldHaveNameUserRatingMeasuredIngredientsAndMethod()
    {
        PHPUnit_Framework_Assert::assertEquals($this->name, $this->theRecipe->getName());
        PHPUnit_Framework_Assert::assertEquals($this->user, $this->theRecipe->getUser());
        PHPUnit_Framework_Assert::assertEquals($this->rating, $this->theRecipe->getRating());
        PHPUnit_Framework_Assert::assertEquals($this->measuredIngredientsList, $this->theRecipe->getMeasauredIngredients());
        PHPUnit_Framework_Assert::assertEquals($this->method, $this->theRecipe->getMethod());
    }
}
