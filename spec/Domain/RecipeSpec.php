<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\Recipe;

class RecipeSpec extends ObjectBehavior
{
    function it_provides_the_rating()
    {
        $this->beConstructedWith(
            'test name',
            new User('test user'),
            new Stars(4)
        );

        $this->getRating()->shouldBeLike(new Stars(4));
    }

    function it_matches_user_and_name()
    {
        $this->beConstructedWith(
            'test name',
            new User('test user'),
            new Stars(4)
        );

        $this->shouldHaveNameAndUser('test name', new User('test user'));
    }

    function it_does_not_match_user_and_name_if_only_name_matches()
    {
        $this->beConstructedWith(
            'test name',
            new User('test user'),
            new Stars(4)
        );

        $this->shouldNotHaveNameAndUser('test name', new User('bad user'));
    }

    function it_does_not_match_user_and_name_if_only_user_matches()
    {
        $this->beConstructedWith(
            'test name',
            new User('test user'),
            new Stars(4)
        );

        $this->shouldNotHaveNameAndUser('bad name', new User('test user'));
    }

    function it_is_higher_rated_than()
    {
        $user = new User('test user');

        $this->beConstructedWith('test name', $user, new Stars(3));

        $this->shouldBeHigherRatedThan(new Recipe('lower rated', $user, new Stars(2)));
    }
}
