<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\Quantity;
use CocktailRater\Domain\Units;

class AmountSpec extends ObjectBehavior
{
    function it_constructs_from_values()
    {
        $this->beConstructedThrough('fromValues', [10, 'ml']);

        $this->getQuantity()->shouldBeLike(new Quantity(10));
        $this->getUnits()->shouldBeLike(new Units('ml'));
    }
}
