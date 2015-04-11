<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Exception\DuplicateEntryException;
use CocktailRater\Domain\Specification\Specification;

interface UserRepository
{
    const USERNAME = 'username';

    /** @throws DuplicateEntryException */
    public function save(User $user);

    /** @return User */
    public function findOneBySpecification(Specification $specification);
}
