<?php

namespace spec\CocktailRater\Domain\Specification;

use CocktailRater\Domain\NamedRecipe;
use CocktailRater\Domain\RecipeName;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RecipeNameSpecificationSpec extends ObjectBehavior
{
    const RECIPE_NAME = 'recipe name';

    function let()
    {
        $this->beConstructedWith(new RecipeName(self::RECIPE_NAME));
    }

    function it_is_satisfied_if_the_names_match(NamedRecipe $recipe)
    {
        $recipe->hasNameMatching(new RecipeName(self::RECIPE_NAME))->willReturn(true);

        $this->shouldBeSatisfiedBy($recipe);
    }

    function it_is_not_satisfied_if_the_names_dont_match(NamedRecipe $recipe)
    {
        $recipe->hasNameMatching(new RecipeName(self::RECIPE_NAME))->willReturn(false);

        $this->shouldNotBeSatisfiedBy($recipe);
    }
}
