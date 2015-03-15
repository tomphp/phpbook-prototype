<?php

namespace CocktailRater\Domain;

final class User
{
    /** @var string */
    private $name;

    /** @param string $name */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /** @return array */
    public function view()
    {
        return ['name' => $this->name];
    }
}
