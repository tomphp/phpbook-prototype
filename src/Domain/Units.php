<?php

namespace CocktailRater\Domain;

final class Units
{
    /** @var string */
    private $value;

    /** @var string $value */
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
