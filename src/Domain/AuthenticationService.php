<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Exception\UsernameTakenException;

final class AuthenticationService
{
    /** @var Username */
    private $usernames = [];

    /**
     * @throws UsernameTakenException
     */
    public function register(ProspectiveUser $user)
    {
        if (in_array($user->getUsername(), $this->usernames)) {
            throw new UsernameTakenException();
        }

        $this->usernames[] = $user->getUsername();
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
