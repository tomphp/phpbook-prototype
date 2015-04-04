<?php

namespace spec\CocktailRater\Domain\Specification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\NamedRecipe;

class RecipeNameSpecificationSpec extends ObjectBehavior
{
    function it_is_satisfied_if_the_names_match(NamedRecipe $recipe)
    {
        $this->beConstructedWith('recipe name');

        $recipe->getName()->willReturn('recipe name');

        $this->shouldBeSatisfiedBy($recipe);
    }

    function it_is_not_satisfied_if_the_names_dont_match(NamedRecipe $recipe)
    {
        $this->beConstructedWith('recipe name');

        $recipe->getName()->willReturn('different name');

        $this->shouldNotBeSatisfiedBy($recipe);
    }
}
