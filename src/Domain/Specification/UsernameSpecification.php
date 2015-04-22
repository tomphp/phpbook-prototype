<?php

namespace CocktailRater\Domain\Specification;

use Assert\Assertion;
use CocktailRater\Domain\NamedUser;
use CocktailRater\Domain\Username;

final class UsernameSpecification
{
    /** @var Username */
    private $username;

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    public function isSatisfiedBy($candidate)
    {
        Assertion::isInstanceOf($candidate, NamedUser::class);

        return $candidate->hasUsername($this->username);
    }
}
