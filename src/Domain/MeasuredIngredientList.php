<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class MeasuredIngredientList
{
    /** @var MeasuredIngredient[] */
    private $ingredients;

    /** @var MeasuredIngredient[] $ingredients */
    public function __construct(array $ingredients)
    {
        Assertion::allIsInstanceOf($ingredients, MeasuredIngredient::class);

        $this->ingredients = $ingredients;
    }

    /** @return array */
    public function view()
    {
        return array_map(function (MeasuredIngredient $ingredient) {
            return $ingredient->view();
        }, $this->ingredients);
    }
}
