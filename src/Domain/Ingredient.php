<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class Ingredient
{
    /** @var string */
    private $name;

    /** @param string $name */
    public function __construct($name)
    {
        Assertion::string($name);

        $this->name = $name;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }
}
