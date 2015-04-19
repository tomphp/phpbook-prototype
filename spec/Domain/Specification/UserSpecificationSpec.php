<?php

namespace spec\CocktailRater\Domain\Specification;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\User;
use CocktailRater\Domain\UserOwned;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpecificationSpec extends ObjectBehavior
{
    function it_is_not_satisifed_if_item_is_owned_by_a_different_user(UserOwned $item)
    {
        $user = new User(new Username('test_user'), new Email('test@email.com'));

        $this->beConstructedWith($user);

        $item->isOwnedByUser($user)->willReturn(false);

        $this->shouldNotBeSatisfiedBy($item);
    }

    function it_is_satisifed_if_item_is_owned_by_the_user(UserOwned $item)
    {
        $user = new User(new Username('test_user'), new Email('test@email.com'));

        $this->beConstructedWith($user);

        $item->isOwnedByUser($user)->willReturn(true);

        $this->shouldBeSatisfiedBy($item);
    }
}
