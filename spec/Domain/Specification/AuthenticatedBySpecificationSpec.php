<?php

namespace spec\CocktailRater\Domain\Specification;

use CocktailRater\Domain\Authenticated;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthenticatedBySpecificationSpec extends ObjectBehavior
{
    function it_is_not_satisfied_if_the_user_is_not_authenticated_by_the_credientials(Authenticated $user)
    {
        $username = new Username('username');
        $password = new Password('password');

        $this->beConstructedWith($username, $password);

        $user->isAuthenticatedBy($username, $password)->willReturn(false);

        $this->shouldNotBeSatisfiedBy($user);
    }

    function it_is_satisfied_if_the_user_is_authenticated_by_the_credientials(Authenticated $user)
    {
        $username = new Username('username');
        $password = new Password('password');

        $this->beConstructedWith($username, $password);

        $user->isAuthenticatedBy($username, $password)->willReturn(true);

        $this->shouldBeSatisfiedBy($user);
    }
}
