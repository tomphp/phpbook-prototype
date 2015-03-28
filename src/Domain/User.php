<?php

namespace CocktailRater\Domain;

final class User
{
    /** @var Username */
    private $username;

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    /** @return array */
    public function view()
    {
        return ['name' => $this->username->getValue()];
    }
}
