<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class User
{
    /** @var string */
    private $name;

    /** @param string $name */
    public function __construct($name)
    {
        Assertion::string($name);

        $this->name = $name;
    }

    /** @return array */
    public function view()
    {
        return ['name' => $this->name];
    }
}
