<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MeasuredIngredientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CocktailRater\Domain\MeasuredIngredient');
    }
}
