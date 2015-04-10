<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Specification\Specification;

interface UserRepository
{
    public function save(User $user);

    /** @return User */
    public function findOneBySpecification(Specification $specification);
}
