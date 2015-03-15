<?php

namespace CocktailRater\Domain;

final class MeasuredIngredient
{
    /** @var Ingredient */
    private $ingredient;

    /** @var Amount */
    private $amount;

    public function __construct(Ingredient $ingredient, Amount $amount)
    {
        $this->ingredient = $ingredient;
        $this->amount     = $amount;
    }

    /** @return array */
    public function view()
    {
        return [
            'name'     => $this->ingredient->getName(),
            'quantity' => $this->amount->getQuantity()->getValue(),
            'units'    => $this->amount->getUnits()->getValue()
        ];
    }
}
