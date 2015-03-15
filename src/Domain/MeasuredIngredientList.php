<?php

namespace CocktailRater\Domain;

final class MeasuredIngredientList
{
    /** @var MeasuredIngredient[] */
    private $ingredients;

    /** @var MeasuredIngredient[] $ingredients */
    public function __construct(array $ingredients)
    {
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
