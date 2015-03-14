<?php

namespace CocktailRater\Domain;

class User
{
    /** @var string */
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}
