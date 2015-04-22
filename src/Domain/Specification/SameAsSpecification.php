<?php

namespace CocktailRater\Domain\Specification;

use Assert\Assertion;
use CocktailRater\Domain\Equality;

final class SameAsSpecification
{
    /** @var Equality */
    private $target;

    public function __construct(Equality $target)
    {
        $this->target = $target;
    }

    public function isSatisfiedBy($candidate)
    {
        Assertion::isInstanceOf($candidate, Equality::class);

        return $candidate->isSameAs($this->target);
    }
}
