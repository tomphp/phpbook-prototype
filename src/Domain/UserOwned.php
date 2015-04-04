<?php

namespace CocktailRater\Domain;

interface UserOwned
{
    /** @return bool */
    public function isOwnedByUser(User $user);
}
