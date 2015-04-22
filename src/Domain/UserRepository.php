<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Exception\DuplicateEntryException;
use CocktailRater\Domain\Specification\Specification;

interface UserRepository
{
    const USERNAME = 'username';
    const EMAIL    = 'email';

    /** @throws DuplicateEntryException */
    public function save(User $user);

    /** @return User */
    public function findById(UserId $id);

    /** @return User */
    public function findOneBySpecification(Specification $specification);
}
