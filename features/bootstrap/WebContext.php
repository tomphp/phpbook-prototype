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

class WebContext implements Context, SnippetAcceptingContext
{
    /** @var CommonContext */
    private $commonContext;

    /** @var MinkContext */
    private $minkContext;

    /** @var array */
    private $prospectiveUser;

    /**
     * @BeforeScenario
     */
    public function before(ScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->commonContext = $environment->getContext(CommonContext::class);
        $this->minkContext = $environment->getContext(MinkContext::class);
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
        $this->minkContext->visit('/recipes');
    }

    /**
     * @When I fetch and view the recipe :name by user :username
     */
    public function iFetchAndViewTheRecipeByUser($name, $username)
    {
        $this->minkContext->visit('/recipes');

        $row = $this->findRecipeRow([
            'name'     => $name,
            'username' => $username->getValue()
        ]);

        $row->clickLink($name);
    }

    /**
     * @Then I should find that the results are empty
     */
    public function iShouldFindThatTheResultsAreEmpty()
    {
        $this->minkContext->assertPageContainsText('There are no cocktail recipes available');
    }

    /**
     * @Then I should find :name by user :username with :stars stars in the results
     */
    public function iShouldFindByUserWithStarsInTheResults($name, $username, $stars)
    {
        $page = $this->minkContext->getSession()->getPage();

        $row = $this->findRecipeRow([
            'name'     => $name,
            'username' => $username->getValue(),
            'stars'    => $stars->getValue()
        ]);

        Assert::assertNotNull($row);
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
        $this->minkContext->assertElementContainsText('.name', $this->getRecipeName()->getValue());
        $this->minkContext->assertElementContainsText('.user', $this->getUser()->view()['name']);
        $this->minkContext->assertElementContainsText('.rating', $this->getRating()->getValue());
        // @todo assertTableMatches()
        //$this->minkContext->assertElementContainsText('.ingredient', $this->getMeasuredIngredientList()->view());
        $this->minkContext->assertElementContainsText('.method', $this->getMethod()->getValue());
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

    /**
     * @Then I should should get a :errorName error
     */
    public function iShouldShouldGetAError($errorName)
    {
        $this->minkContext->assertPageContainsText('This username has already been taken');
    }

    /** @return RecipeList */
    private function getRecipeList()
    {
        return $this->commonContext->getRecipeList();
    }

    /**
     * @param string[] $search
     *
     * @return NodeElement|null
     */
    private function findRecipeRow(array $search)
    {

        $page = $this->minkContext->getSession()->getPage();
        $rows = $page->findAll('css', '#recipes tbody tr');

        $found = false;

        foreach ($rows as $row) {
            if ($this->recipeRowMatches($search, $row)) {
                $found = true;
                break;
            }
        }

        return $found ? $row : null;
    }

    /**
     * @param string[] $search
     *
     * @return boolean
     */
    private function recipeRowMatches(array $search, NodeElement $row)
    {
        $columnMap = [
            'name'     => 1,
            'username' => 2,
            'stars'    => 3
        ];

        $found = true;

        foreach ($search as $name => $value) {
            $actual = $row->find('css', ":nth-child({$columnMap[$name]})")
                          ->getText();

            if ($value != $actual) {
                $found = false;
            }
        }

        return $found;
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

    /** @return RecipeName */
    private function getRecipeName()
    {
        return $this->commonContext->getRecipeName();
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
