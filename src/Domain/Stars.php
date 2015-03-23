<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class Stars
{
    /** @var number */
    private $value;

    /** @param number $value */
    public function __construct($value)
    {
        Assertion::numeric($value);

        $this->value = (int) $value;
    }

    /** @return number */
    public function getValue()
    {
        return $this->value;
    }

    /*
    public function isHigherRatedThan(self $other)
    {
        return $this->value > $other->value;
    }
     */
}
