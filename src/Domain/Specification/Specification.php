<?php

namespace CocktailRater\Domain\Specification;

interface Specification
{
    /** @return bool */
    public function isSatisfiedBy($candidate);
}
