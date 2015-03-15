<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\ViewableUser;

class UserSpec extends ObjectBehavior
{
    function it_returns_view_data()
    {
        $this->beConstructedWith('test user');

        $this->view()->shouldReturn(['name' => 'test user']);
    }
}
