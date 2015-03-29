<?php

namespace CocktailRater\Domain;

final class ProspectiveUser
{
    /** @var Username */
    private $username;

    public function __construct(
        Username $username,
        Email $email,
        Password $password
    ) {
        $this->username = $username;
    }

    /** @return Username */
    public function getUsername()
    {
        return $this->username;
    }
}
