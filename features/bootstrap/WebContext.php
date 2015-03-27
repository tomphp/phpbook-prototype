<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\ScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeList;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use PHPUnit_Framework_Assert as Assert;

class WebContext implements Context, SnippetAcceptingContext
{
    /** @var CommonContext */
    private $commonContext;

    /** @var MinkContext */
    private $minkContext;

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
     * @When I view the recipe list
     */
    public function iViewTheRecipeList()
    {
        $this->minkContext->visit('/list-recipes');
    }

    /**
     * @When I fetch and view the recipe :name by user :username
     */
    public function iFetchAndViewTheRecipeByUser($name, $username)
    {
        $slug = urlencode($username) . '/' . urlencode($name);

        $this->minkContext->visit("/view-recipe/$slug");
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

        $rows = $page->findAll('css', '#recipes tbody tr');

        $found = false;

        foreach ($rows as $row) {
            $recipeName     = $row->find('css', ':nth-child(1)')->getText();
            $recipeUsername = $row->find('css', ':nth-child(2)')->getText();
            $recipeRating    = $row->find('css', ':nth-child(3)')->getText();

            if ($name === $recipeName && $username === $recipeUsername && "$stars stars" === $recipeRating) {
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
        $this->minkContext->assertElementContainsText('.name', $this->getName());
        $this->minkContext->assertElementContainsText('.user', $this->getUser()->view()['name']);
        $this->minkContext->assertElementContainsText('.rating', $this->getRating()->getValue());
        // @todo assertTableMatches()
        //$this->minkContext->assertElementContainsText('.ingredient', $this->getMeasuredIngredientList()->view());
        $this->minkContext->assertElementContainsText('.method', $this->getMethod()->getValue());
    }

    private function assertIsSorted(array $list)
    {
        $sorted = $list;
        rsort($sorted);

        Assert::assertEquals($sorted, $list);
    }

//     /**
//      * @Given I am a prospective user with username :username, email :email and password :password
//      */
//     public function iAmAProspectiveUserWithUsernameEmailAndPassword($username, $email, $password)
//     {
//         $this->user = new ProspectiveUser(
//             new Username($username),
//             new Email($email),
//             new Password($password)
//         );
//     }
//
//     /**
//      * @When I register user :username the authentication service
//      */
//     public function iRegisterUserTheAuthenticationService($username)
//     {
//         // @todo username required?
//         $this->authenticationService($this->user);
//     }
//
//     /**
//      * @Then I should should be able to log in to the site as user :username
//      */
//     public function iShouldShouldBeAbleToLogInToTheSiteAsUser($username, $email)
//     {
//         $this->authenticationService->logIn(new Username($username), new Email($email));
//
//         Assert::assertTrue($this->authenticationService->isLoggedIn());
//     }

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
