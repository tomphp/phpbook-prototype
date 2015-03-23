<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class Quantity
{
    /** @var number */
    private $quantity;

    /** @param number $value */
    public function __construct($value)
    {
        // @todo
        //Assertion::numeric($value);

        $this->value = $value;
    }

    /** @return number */
    public function getValue()
    {
        return $this->value;
    }
}
