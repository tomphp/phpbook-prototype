<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\Ingredient;
use CocktailRater\Domain\Amount;

class MeasuredIngredientSpec extends ObjectBehavior
{
    function it_returns_view_data()
    {
        $this->beConstructedWith(
            new Ingredient('test ingredient'),
            Amount::fromValues(10, 'ml')
        );

        $this->view()->shouldReturn([
            'name'    => 'test ingredient',
            'quantity' => 10,
            'units'   => 'ml'
        ]);
    }
}
