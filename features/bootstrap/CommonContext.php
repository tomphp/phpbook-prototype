<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use CocktailRater\Domain\Amount;
use CocktailRater\Domain\AuthenticationService;
use CocktailRater\Domain\Email;
use CocktailRater\Domain\Ingredient;
use CocktailRater\Domain\MeasuredIngredient;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\ProspectiveUser;
use CocktailRater\Domain\Quantity;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\RecipeName;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\Units;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use CocktailRater\FileSystemRepository\FileSystemRecipeRepository;
use CocktailRater\FileSystemRepository\FileSystemUserRepository;

class CommonContext implements Context, SnippetAcceptingContext
{
    /** @var RecipeList */
    private $recipeList;

    /** @var MeasuredIngredientList */
    private $measuredIngredientList;

    /** @var Method */
    private $method;

    /** @var RecipeName */
    private $recipeName;

    /** @var Stars */
    private $rating;

    /** @var User */
    private $user;

    /** @var array */
    private $results;

    /** @var AuthenticationService */
    private $authenticationService;

    /**
     * @BeforeScenario
     */
    public function before()
    {
        $recipeRepository = new FileSystemRecipeRepository(__DIR__ . '/../../test-fsdb');
        $recipeRepository->clear();

        $userRepository = new FileSystemUserRepository(__DIR__ . '/../../test-fsdb');
        $userRepository->clear();

        $this->recipeList = new RecipeList($recipeRepository);

        $this->authenticationService = new AuthenticationService($userRepository);
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
     * @Given a recipe for :recipeName by user :user rated with :stars stars has been added to the recipe list
     */
    public function aRecipeForByUserRatedWithStarsHasBeenAddedToTheRecipeList($recipeName, User $user, Stars $stars)
    {
        $aRecipe = Recipe::withNoId(
            $recipeName,
            $user,
            $stars,
            new MeasuredIngredientList([]),
            new Method('')
        );

        $this->recipeList->add($aRecipe);
    }

    /**
     * @Given a recipe for :recipeName rated with :stars stars has been added to the recipe list
     */
    public function aRecipeForRatedWithStarsHasBeenAddedToTheRecipeList($recipeName, Stars $stars)
    {
        $aRecipe = Recipe::withNoId(
            $recipeName,
            new User(new Username('dummy_user')),
            $stars,
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
     * @Given there's a recipe for :recipeName by user :user with :stars stars, the measured ingredients and method added to the reciped list
     */
    public function thereSARecipeForByUserWithStarsTheMeasuredIngredientsAndMethodAddedToTheRecipedList(RecipeName $recipeName, User $user, Stars $stars)
    {
        $this->recipeName   = $recipeName;
        $this->user   = $user;
        $this->rating = $stars;

        $aRecipe = new Recipe(
            $this->recipeName,
            $this->user,
            $this->rating,
            $this->measuredIngredientList,
            $this->method
        );

        $this->recipeList->add($aRecipe);
    }


    /**
     * @Given there is a user registered with the authenication service with username :username and email :email
     */
    public function thereIsAUserRegisteredWithTheAuthenicationServiceWithUsernameAndEmail(Username $username, Email $email)
    {
        $this->authenticationService->register(new ProspectiveUser(
            $username,
            $email,
            new Password('dummy_password')
        ));
    }

    /**
     * @Transform :stars
     *
     * @return Stars
     */
    public function castToStars($stars)
    {
        return new Stars($stars);
    }

    /**
     * @Transform :recipeName
     *
     * @return RecipeName
     */
    public function castToRecipeName($recipeName)
    {
        return new RecipeName($recipeName);
    }

    /**
     * @Transform :user
     *
     * @return User
     */
    public function castToUser($username)
    {
        return new User(new Username($username));
    }

    /**
     * @Transform :username
     *
     * @return Username
     */
    public function castToUsername($username)
    {
        return new Username($username);
    }

    /**
     * @Transform :email
     *
     * @return Email
     */
    public function castToEmail($email)
    {
        return new Email($email);
    }

    /**
     * @Transform :password
     *
     * @return Password
     */
    public function castToPassword($password)
    {
        return new Password($password);
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

    /** @return RecipeName */
    public function getRecipeName()
    {
        return $this->recipeName;
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

    /** @return AuthenticationService */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }
}
