<?php

namespace CocktailRater\Domain\Specification;

use Assert\Assertion;
use CocktailRater\Domain\NamedRecipe;
use CocktailRater\Domain\RecipeName;

final class RecipeNameSpecification implements Specification
{
    /** @var RecipeName */
    private $name;

    public function __construct(RecipeName $name)
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
