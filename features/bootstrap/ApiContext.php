<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\ScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
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
use Behat\Mink\Element\NodeElement;
use GuzzleHttp\Client;

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
        $this->getFromApi($this->url . 'recipes');

    }


    /**
     * @When I fetch and view the recipe :name by user :username
     */
    public function iFetchAndViewTheRecipeByUser($name, $username)
    {
        $this->getFromApi($this->url . 'recipes');

        $found = false;

        foreach ($this->response['_embedded']['recipes'] as $recipe) {
            if ($name === $recipe['name']
                && $username->getValue() === $recipe['_embedded']['user']['name']
            ) {
                $found = true;
                break;
            }
        }

        Assert::assertTrue($found);

        $this->getFromApi($recipe['_links']['self']['href']);
    }

    /**
     * @Then I should find that the results are empty
     */
    public function iShouldFindThatTheResultsAreEmpty()
    {
        Assert::assertEquals(0, $this->response['count']);

        Assert::assertEmpty($this->response['_embedded']['recipes']);
    }

    /**
     * @Then I should find :name by user :username with :stars stars in the results
     */
    public function iShouldFindByUserWithStarsInTheResults($name, $username, $stars)
    {
        $found = false;

        foreach ($this->response['_embedded']['recipes'] as $recipe) {
            if ($name === $recipe['name']
                && $username->getValue() === $recipe['_embedded']['user']['name']
                && $stars->getValue() === $recipe['stars']
            ) {
                $found = true;
                break;
            }
        }

        Assert::assertTrue($found);
    }

    /**
     * @Then I should find the results have the highest rated recipes at the top of recipe list
     */
    public function iShouldFindTheResultsHaveTheHighestRatedRecipesAtTheTopOfRecipeList()
    {
        $page = $this->minkContext->getSession()->getPage();

        $rows = $page->findAll('css', '#recipes tbody tr');

        $ratings = [];

        foreach ($rows as $row) {
            $ratings[] = $row->find('css', ':nth-child(3)')->getText();
        }

        $this->assertIsSorted($ratings);
    }

    /**
     * @Then I should be viewing the name, user, rating, measured ingredients and method of the recipe
     */
    public function iShouldBeViewingTheNameUserRatingMeasuredIngredientsAndMethodOfTheRecipe()
    {
        Assert::assertEquals($this->getName(), $this->response['name']);
        Assert::assertEquals($this->getUser()->view()['name'], $this->response['_embedded']['user']['name']);
        Assert::assertEquals($this->getRating()->getValue(), $this->response['stars']);
        // @todo assertTableMatches()
        //$this->minkContext->assertElementContainsText('.ingredient', $this->getMeasuredIngredientList()->view());
        Assert::assertEquals($this->getMethod()->getValue(), $this->response['method']);
    }

    /**
     * @When I register with the authentication service
     */
    public function iRegisterWithTheAuthenticationService()
    {
        $this->minkContext->visit('/register');

        $this->minkContext->fillField('username', $this->prospectiveUser['username']);
        $this->minkContext->fillField('email', $this->prospectiveUser['email']);
        $this->minkContext->fillField('password', $this->prospectiveUser['password']);

        $this->minkContext->pressButton('Register');

        // @todo verify completion message?
    }

    /**
     * @Then I should should be able to log in to the site as user :username with password :password
     */
    public function iShouldShouldBeAbleToLogInToTheSiteAsUserWithPassword(Username $username, Password $password)
    {
        $this->minkContext->visit('/login');

        $this->minkContext->fillField('username', $this->prospectiveUser['username']);
        $this->minkContext->fillField('password', $this->prospectiveUser['password']);

        $this->minkContext->pressButton('Log In');

        $this->minkContext->assertPageContainsText('Login Successful');
    }

    /** @return RecipeList */
    public function getRecipeList()
    {
        return $this->commonContext->getRecipeList();
    }

    /**
     * @param string $url
     *
     * @return array
     */
    private function getFromApi($url)
    {
        $client = new Client();
        $response = $client->get($url);

        Assert::assertEquals(
            'application/hal+json',
            $response->getHeader('content-type'),
            'Incorrect content type'
        );

        $this->response = $response->json();

        Assert::assertEquals($url, $this->response['_links']['self']['href']);
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
