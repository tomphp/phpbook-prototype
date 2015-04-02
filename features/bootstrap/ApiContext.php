<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\ScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Element\NodeElement;
use CocktailRater\Domain\Email;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use PHPUnit_Framework_Assert as Assert;
use TomPHP\HalClient\Client;

class ApiContext implements Context, SnippetAcceptingContext
{
    /** @var CommonContext */
    private $commonContext;

    /** @var string */
    private $url;

    /** @var array */
    private $prospectiveUser;

    /** @var array */
    private $response;

    /** @param string $url */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @BeforeScenario
     */
    public function before(ScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->commonContext = $environment->getContext(CommonContext::class);
    }

    /**
     * @Given I am a prospective user with username :username, email :email and password :password
     */
    public function iAmAProspectiveUserWithUsernameEmailAndPassword(Username $username, Email $email, Password $password)
    {
        $this->prospectiveUser = [
            'username' => $username->getValue(),
            'email'    => $email->getValue(),
            'password' => $password->getValue()
        ];
    }

    /**
     * @When I view the recipe list
     */
    public function iViewTheRecipeList()
    {
        $this->response = Client::create()->get($this->url)->recipes->get();
    }

    /**
     * @When I fetch and view the recipe :name by user :username
     */
    public function iFetchAndViewTheRecipeByUser($name, Username $username)
    {
        $recipes = Client::create()->get($this->url)->recipes->get()->recipes;

        $this->response = $recipes->findMatching([
            'name' => $name,
            'user' => ['name' => $username->getValue()]
        ])[0]->self->get();
    }

    /**
     * @Then I should find that the results are empty
     */
    public function iShouldFindThatTheResultsAreEmpty()
    {
        Assert::assertEquals(0, $this->response->count->getValue());

        Assert::assertEmpty($this->response->recipes);
    }

    /**
     * @Then I should find :name by user :username with :stars stars in the results
     */
    public function iShouldFindByUserWithStarsInTheResults($name, Username $username, Stars $stars)
    {
        $recipes = $this->response->recipes->findMatching([
            'name'  => $name,
            'user'  => ['name' => $username->getValue()],
            'stars' => $stars->getValue()
        ]);

        Assert::assertCount(1, $recipes);
    }

    /**
     * @Then I should be viewing the name, user, rating, measured ingredients and method of the recipe
     */
    public function iShouldBeViewingTheNameUserRatingMeasuredIngredientsAndMethodOfTheRecipe()
    {
        Assert::assertEquals($this->getName(), $this->response->name->getValue());
        Assert::assertEquals($this->getUser()->view()['name'], $this->response->user->name->getValue());
        Assert::assertEquals($this->getRating()->getValue(), $this->response->stars->getValue());
        // @todo assertTableMatches()
        //$this->minkContext->assertElementContainsText('.ingredient', $this->getMeasuredIngredientList()->view());
        Assert::assertEquals($this->getMethod()->getValue(), $this->response->method->getValue());
    }

    /**
     * @When I register with the authentication service
     */
    /*
    public function iRegisterWithTheAuthenticationService()
    {
        $this->minkContext->visit('/register');

        $this->minkContext->fillField('username', $this->prospectiveUser['username']);
        $this->minkContext->fillField('email', $this->prospectiveUser['email']);
        $this->minkContext->fillField('password', $this->prospectiveUser['password']);

        $this->minkContext->pressButton('Register');

        // @todo verify completion message?
    }
     */

    /**
     * @Then I should should be able to log in to the site as user :username with password :password
     */
    /*
    public function iShouldShouldBeAbleToLogInToTheSiteAsUserWithPassword(Username $username, Password $password)
    {
        $this->minkContext->visit('/login');

        $this->minkContext->fillField('username', $this->prospectiveUser['username']);
        $this->minkContext->fillField('password', $this->prospectiveUser['password']);

        $this->minkContext->pressButton('Log In');

        $this->minkContext->assertPageContainsText('Login Successful');
    }
     */

    /** @return RecipeList */
    public function getRecipeList()
    {
        return $this->commonContext->getRecipeList();
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
}
