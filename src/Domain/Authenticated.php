<?php

namespace CocktailRater\Domain;

interface Authenticated
{
    /** @return bool */
    public function isAuthenticatedBy(Username $username, Password $password);
}
