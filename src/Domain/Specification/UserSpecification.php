<?php

namespace CocktailRater\Domain\Specification;

use CocktailRater\Domain\User;
use Assert\Assertion;
use CocktailRater\Domain\UserOwned;

final class UserSpecification implements Specification
{
    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /** @param UserOwned $candidate */
    public function isSatisfiedBy($candidate)
    {
        Assertion::isInstanceOf($candidate, UserOwned::class);

        return $candidate->isOwnedByUser($this->user);
    }
}
