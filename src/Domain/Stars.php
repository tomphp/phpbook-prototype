<?php

namespace CocktailRater\Domain;

class Stars
{
    /** @var number */
    private $value;

    public function __construct($value)
    {
        $this->value = (int) $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isHigherRatedThan(self $other)
    {
        return $this->value > $other->value;
    }
}
