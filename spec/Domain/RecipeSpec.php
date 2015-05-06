<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeId;
use CocktailRater\Domain\RecipeName;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\Email;
use CocktailRater\Domain\UserId;

class RecipeSpec extends ObjectBehavior
{
    const NAME     = 'test name';
    const USER_ID  = 'test_user_id';
    const USERNAME = 'test_user';
    const EMAIL    = 'test@email.com';
    const STARS    = 4;
    const METHOD   = 'test method';

    function let()
    {
        $user = new User(new Username(self::USERNAME), new Email('test@email.com'));
        $user->setId(new UserId(self::USER_ID));

        $this->beConstructedThrough('withNoId', [
            new RecipeName(self::NAME),
            $user,
            new Stars(self::STARS),
            new MeasuredIngredientList([]),
            new Method(self::METHOD)
        ]);
    }

    function it_has_a_null_id_if_not_been_stored()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_can_have_its_id_set()
    {
        $this->setId(new RecipeId('test-id'));

        $this->getId()->shouldBeLike(new RecipeId('test-id'));
    }

    function it_returns_data_for_storage()
    {
        $this->setId(new RecipeId('test-id'));

        $this->getForStorage()->shouldReturn([
            'id'                   => 'test-id',
            'name'                 => self::NAME,
            'user'                 => self::USER_ID,
            'stars'                => self::STARS,
            'measured_ingredients' => [],
            'method'               => self::METHOD
        ]);
    }

    function it_returns_view_data()
    {
        $this->view()->shouldReturn([
            'name'                 => self::NAME,
            'user'                 => ['username' => self::USERNAME, 'email' => self::EMAIL],
            'stars'                => self::STARS,
            'measured_ingredients' => [],
            'method'               => self::METHOD
        ]);
    }

    function it_can_be_created_from_a_storage_array()
    {
        $user = new User(
            new Username('stored_username'),
            new Email('stored@email.com')
        );
        $user->setId(new UserId('user_id'));

        $this->beConstructedThrough('fromStorageArray', [[
            'id'                   => 'stored_id',
            'name'                 => 'stored_name',
            'stars'                => 2,
            'measured_ingredients' => [[
                'name'     => 'stored ingredient',
                'quantity' => 25,
                'units'    => 'ml'
            ]],
            'method'               => 'stored method'
        ], $user]);

        $this->getId()->shouldBeLike(new RecipeId('stored_id'));

        $this->view()->shouldReturn([
            'id'                   => 'stored_id',
            'name'                 => 'stored_name',
            'user'                 => $user->view(),
            'stars'                => 2,
            'measured_ingredients' => [[
                'name'     => 'stored ingredient',
                'quantity' => 25,
                'units'    => 'ml'
            ]],
            'method'               => 'stored method'
        ]);
    }

    function it_is_not_owned_by_a_different_user()
    {
        $user = new User(new Username(self::USERNAME), new Email('test@email.com'));
        $user->setId(new UserId('different_user_id'));

        $this->shouldNotBeOwnedByUser($user);
    }

    function it_is_owned_by_the_user()
    {
        $user = new User(new Username(self::USERNAME), new Email('test@email.com'));
        $user->setId(new UserId(self::USER_ID));

        $this->shouldBeOwnedByUser($user);
    }

    function it_checks_if_name_matches()
    {
        $this->shouldHaveNameMatching(new RecipeName(self::NAME));
        $this->shouldNotHaveNameMatching(new RecipeName('a different name'));
    }

    /*
    function it_is_higher_rated_than()
    {
        $user = new User('test user');

        $this->beConstructedWith('test name', $user, new Stars(3));

        $this->shouldBeHigherRatedThan(new Recipe('lower rated', $user, new Stars(2)));
    }
     */
}
