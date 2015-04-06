<?php

namespace CocktailRater\Domain\Specification;

use Assert\Assertion;
use CocktailRater\Domain\NamedRecipe;

final class RecipeNameSpecification implements Specification
{
    /** @var string */
    private $name;

    /** @param string $name */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /** @param NamedRecipe $candidate */
    public function isSatisfiedBy($candidate)
    {
        Assertion::isInstanceOf($candidate, NamedRecipe::class);

        return $candidate->hasNameMatching($this->name);
    }
}
