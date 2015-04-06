<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Specification\Specification;

interface UserRepository
{
    /** @return User */
    public function findOneBySpecification(Specification $specification);
}
