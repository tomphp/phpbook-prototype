<?php

namespace CocktailRater\Domain;

final class AuthenticationService
{
    public function register(ProspectiveUser $user)
    {
    }

    public function logIn(Username $username, Password $password)
    {
    }

    /** @return boolean */
    public function isLoggedIn()
    {
        return true;
    }
}
