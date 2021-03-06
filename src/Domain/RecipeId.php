<?php

namespace CocktailRater\Domain;

final class RecipeId
{
    /** @var string */
    private $value;

    /** @param string $value */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /** @return string */
    public function getValue()
    {
        return $this->value;
    }
}
