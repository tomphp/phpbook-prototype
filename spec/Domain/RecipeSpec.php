<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;

class RecipeSpec extends ObjectBehavior
{
    const NAME     = 'test name';
    const USERNAME = 'test_user';
    const STARS    = 4;
    const METHOD   = 'test method';

    function let()
    {
        $this->beConstructedWith(
            self::NAME,
            new User(self::USERNAME),
            new Stars(self::STARS),
            new MeasuredIngredientList([]),
            new Method(self::METHOD)
        );
    }

    function it_returns_view_data()
    {
        $this->view()->shouldReturn([
            'name'                 => self::NAME,
            'user'                 => ['name' => self::USERNAME],
            'stars'                => self::STARS,
            'measured_ingredients' => [],
            'method'               => self::METHOD
        ]);
    }

    function it_matches_user_and_name()
    {
        $this->shouldHaveNameAndUser(self::NAME, new User(self::USERNAME));
    }

    function it_does_not_match_user_and_name_if_only_name_matches()
    {
        $this->shouldNotHaveNameAndUser(self::NAME, new User('bad user'));
    }

    function it_does_not_match_user_and_name_if_only_user_matches()
    {
        $this->shouldNotHaveNameAndUser('bad name', new User(self::USERNAME));
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
