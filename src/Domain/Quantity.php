<?php

namespace CocktailRater\Domain;

final class Quantity
{
    /** @var number */
    private $quantity;

    /** @param number $value */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /** @return number */
    public function getValue()
    {
        return $this->value;
    }
}
