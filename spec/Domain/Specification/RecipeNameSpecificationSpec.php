<?php

namespace spec\CocktailRater\Domain\Specification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\NamedRecipe;

class RecipeNameSpecificationSpec extends ObjectBehavior
{
    const RECIPE_NAME = 'recipe name';

    function let()
    {
        $this->beConstructedWith(self::RECIPE_NAME);
    }

    function it_is_satisfied_if_the_names_match(NamedRecipe $recipe)
    {
        $recipe->hasNameMatching(self::RECIPE_NAME)->willReturn(true);

        $this->shouldBeSatisfiedBy($recipe);
    }

    function it_is_not_satisfied_if_the_names_dont_match(NamedRecipe $recipe)
    {
        $recipe->hasNameMatching(self::RECIPE_NAME)->willReturn(false);

        $this->shouldNotBeSatisfiedBy($recipe);
    }
}
