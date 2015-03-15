<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\MeasuredIngredient;
use CocktailRater\Domain\Amount;
use CocktailRater\Domain\Ingredient;

class MeasuredIngredientListSpec extends ObjectBehavior
{
    function it_returns_view_data()
    {
        $this->beConstructedWith([
            new MeasuredIngredient(
                new Ingredient('test ingredient'),
                Amount::fromValues(5, 'ml') // @todo need quantity and units!
            )
        ]);

        $this->view()->shouldReturn([
            [
                'name'     => 'test ingredient',
                'quantity' => 5,
                'units'    => 'ml'
            ]
        ]);
    }
}
