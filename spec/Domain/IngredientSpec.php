<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IngredientSpec extends ObjectBehavior
{
    function it_returns_its_name()
    {
        $this->beConstructedWith('test ingredient');

        $this->getName()->shouldReturn('test ingredient');
    }
}
