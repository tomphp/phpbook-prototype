<?php

namespace CocktailRater\Domain\Specification;

use Assert\Assertion;
use CocktailRater\Domain\Authenticated;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\Username;

final class AuthenticatedBySpecification implements Specification
{
    /** @var Username */
    private $username;

    /** @var Password */
    private $password;

    public function __construct(Username $username, Password $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /** @param Authenticated $candidate */
    public function isSatisfiedBy($candidate)
    {
        Assertion::isInstanceOf($candidate, Authenticated::class);

        return $candidate->isAuthenticatedBy($this->username, $this->password);
    }
}
