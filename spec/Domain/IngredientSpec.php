<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IngredientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CocktailRater\Domain\Ingredient');
    }
}
