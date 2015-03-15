<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use CocktailRater\Domain\Amount;
use CocktailRater\Domain\Ingredient;
use CocktailRater\Domain\MeasuredIngredient;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Quantity;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\Units;
use CocktailRater\Domain\User;
use PHPUnit_Framework_Assert as Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var RecipeList */
    private $recipeList;

    /** @var MeasuredIngredientList */
    private $measuredIngredientList;

    /** @var Method */
    private $method;

    /** @var string */
    private $name;

    /** @var User */
    private $user;

    /** @var array */
    private $results;

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
     * @Given a recipe for :recipeName by user :username rated with :stars stars has been added to the recipe list
     */
    public function aRecipeForByUserRatedWithStarsHasBeenAddedToTheRecipeList($recipeName, $username, $stars)
    {
        $aRecipe = new Recipe(
            $recipeName,
            new User($username),
            new Stars($stars),
            new MeasuredIngredientList([]),
            new Method('')
        );

        $this->recipeList->add($aRecipe);
    }

    /**
     * @Given a recipe for :recipeName rated with :stars stars has been added to the recipe list
     */
    public function aRecipeForRatedWithStarsHasBeenAddedToTheRecipeList($recipeName, $stars)
    {
        $aRecipe = new Recipe(
            $recipeName,
            new User('dummy_user'),
            new Stars($stars),
            new MeasuredIngredientList([]),
            new Method('')
        );

        $this->recipeList->add($aRecipe);
    }

    /**
     * @Given a list of measured ingredients:
     */
    public function aListOfMeasuredIngredients(TableNode $table)
    {
        $measuredIngredients = array_map(function ($ingredient) {
            return new MeasuredIngredient(
                new Ingredient($ingredient['name']),
                new Amount(
                    new Quantity($ingredient['amount']),
                    new Units($ingredient['units'])
                )
            );
        }, $table->getColumnsHash());

        $this->measuredIngredientList = new MeasuredIngredientList(
            $measuredIngredients
        );
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
            $this->measuredIngredientList,
            $this->method
        );

        $this->recipeList->add($aRecipe);
    }

    /**
     * @When I view the recipe list
     */
    public function iViewTheRecipeList()
    {
        $this->results = $this->recipeList->view();
    }

    /**
     * @When I fetch and view the recipe :name by user :username
     */
    public function iFetchAndViewTheRecipeByUser($name, $username)
    {
        $recipe = $this->recipeList->fetchByNameAndUser($name, new User($username));

        $this->results = $recipe->view();
    }

    /**
     * @Then I should find that the results are empty
     */
    public function iShouldFindThatTheResultsAreEmpty()
    {
        Assert::assertEmpty($this->results);
    }

    /**
     * @Then I should find :name by user :username with :stars stars in the results
     */
    public function iShouldFindByUserWithStarsInTheResults($name, $username, $stars)
    {
        $expected = [
            'name'                 => $name,
            'user'                 => ['name' => $username],
            'stars'                => $stars,
            // @todo This is dummy data, just check for the correct data
            'measured_ingredients' => [],
            'method'               => '',
        ];

        Assert::assertContains($expected, $this->results);
    }

    /**
     * @Then I should find the results have the highest rated recipes at the top of recipe list
     */
    public function iShouldFindTheResultsHaveTheHighestRatedRecipesAtTheTopOfRecipeList()
    {
        $this->assertIsSorted(array_map(
            function ($data) {
                return $data['stars'];
            },
            $this->results
        ));
    }

    /**
     * @Then I should be viewing the name, user, rating, measured ingredients and method of the recipe
     */
    public function iShouldBeViewingTheNameUserRatingMeasuredIngredientsAndMethodOfTheRecipe()
    {
        Assert::assertEquals($this->name, $this->results['name']);
        Assert::assertEquals($this->user->view(), $this->results['user']);
        Assert::assertEquals($this->rating->getValue(), $this->results['stars']);
        Assert::assertEquals($this->measuredIngredientList->view(), $this->results['measured_ingredients']);
        Assert::assertEquals($this->method->getValue(), $this->results['method']);
    }

    private function assertIsSorted(array $list)
    {
        $sorted = $list;
        rsort($sorted);

        return $sorted === $list;
    }
}
