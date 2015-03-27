<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
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
use CocktailRater\FileSystemRepository\FileSystemRecipeRepository;

class CommonContext implements Context, SnippetAcceptingContext
{
    /** @var RecipeList */
    private $recipeList;

    /** @var MeasuredIngredientList */
    private $measuredIngredientList;

    /** @var Method */
    private $method;

    /** @var string */
    private $name;

    /** @var Stars */
    private $rating;

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
        $recipeRepository = new FileSystemRecipeRepository(__DIR__ . '/../../test-fsdb');
        $recipeRepository->clear();

        $this->recipeList = new RecipeList($recipeRepository);
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
        $aRecipe = Recipe::withNoId(
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
        $aRecipe = Recipe::withNoId(
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

    /** @return RecipeList */
    public function getRecipeList()
    {
        return $this->recipeList;
    }

    /** @return MeasuredIngredientList */
    public function getMeasuredIngredientList()
    {
        return $this->measuredIngredientList;
    }

    /** @return Method */
    public function getMethod()
    {
        return $this->method;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return Stars */
    public function getRating()
    {
        return $this->rating;
    }

    /** @return User */
    public function getUser()
    {
        return $this->user;
    }
}
