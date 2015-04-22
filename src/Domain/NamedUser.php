<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Username;

interface NamedUser
{
    /** @return false */
    public function hasUsername(Username $username);
}
