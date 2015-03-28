<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\ScenarioScope;
use CocktailRater\Domain\AuthenticationService;
use CocktailRater\Domain\Email;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\ProspectiveUser;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use PHPUnit_Framework_Assert as Assert;

class DomainContext implements Context, SnippetAcceptingContext
{
    /** @var CommonContext */
    private $commonContext;

    /** @var array */
    private $results;

    /** @var ProspectiveUser */
    private $prospectiveUser;

    /**
     * @BeforeScenario
     */
    public function before(ScenarioScope $scope)
    {
        $this->commonContext = $scope->getEnvironment()
                                     ->getContext(CommonContext::class);
    }

    /**
     * @Given I am a prospective user with username :username, email :email and password :password
     */
    public function iAmAProspectiveUserWithUsernameEmailAndPassword(Username $username, Email $email, Password $password)
    {
        $this->prospectiveUser = new ProspectiveUser($username, $email, $password);
    }

    /**
     * @When I view the recipe list
     */
    public function iViewTheRecipeList()
    {
        $this->results = $this->getRecipeList()->view();
    }

    /**
     * @When I fetch and view the recipe :name by user :user
     */
    public function iFetchAndViewTheRecipeByUser($name, User $user)
    {
        $recipe = $this->getRecipeList()->fetchByNameAndUser($name, $user);

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
    public function iShouldFindByUserWithStarsInTheResults($name, Username $username, Stars $stars)
    {
        $expected = [
            'name'                 => $name,
            'user'                 => ['name' => $username->getValue()],
            'stars'                => $stars->getValue(),
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
        Assert::assertEquals($this->getName(), $this->results['name']);
        Assert::assertEquals($this->getUser()->view(), $this->results['user']);
        Assert::assertEquals($this->getRating()->getValue(), $this->results['stars']);
        Assert::assertEquals($this->getMeasuredIngredientList()->view(), $this->results['measured_ingredients']);
        Assert::assertEquals($this->getMethod()->getValue(), $this->results['method']);
    }

    /**
     * @When I register with the authentication service
     */
    public function iRegisterWithTheAuthenticationService()
    {
        // @todo username required?
        $this->getAuthenticationService()->register($this->prospectiveUser);
    }

    /**
     * @Then I should should be able to log in to the site as user :username with password :password
     */
    public function iShouldShouldBeAbleToLogInToTheSiteAsUserWithPassword(Username $username, Password $password)
    {
        $this->getAuthenticationService()->logIn($username, $password);

        Assert::assertTrue($this->getAuthenticationService()->isLoggedIn());
    }

    /** @return RecipeList */
    public function getRecipeList()
    {
        return $this->commonContext->getRecipeList();
    }

    private function assertIsSorted(array $list)
    {
        $sorted = $list;
        rsort($sorted);

        Assert::assertEquals($sorted, $list);
    }

    /** @return MeasuredIngredientList */
    private function getMeasuredIngredientList()
    {
        return $this->commonContext->getMeasuredIngredientList();
    }

    /** @return Method */
    private function getMethod()
    {
        return $this->commonContext->getMethod();
    }

    /** @return string */
    private function getName()
    {
        return $this->commonContext->getName();
    }

    /** @return Stars */
    public function getRating()
    {
        return $this->commonContext->getRating();
    }

    /** @return User */
    private function getUser()
    {
        return $this->commonContext->getUser();
    }

    /** @return AuthenticationService */
    private function getAuthenticationService()
    {
        return $this->commonContext->getAuthenticationService();
    }
}
