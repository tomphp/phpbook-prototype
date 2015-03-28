<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\ViewableUser;
use CocktailRater\Domain\Username;

class UserSpec extends ObjectBehavior
{
    function it_returns_view_data()
    {
        $this->beConstructedWith(new Username('test user'));

        $this->view()->shouldReturn(['name' => 'test user']);
    }
}
