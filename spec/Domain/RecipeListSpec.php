<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RecipeListSpec extends ObjectBehavior
{
    public function it_finds_user_by_name()
    {
        $user = new User('test user');
        $recipe = new Recipe('test recipe', $user, new Stars(5));

        $this->add($recipe);

        $this->findByNameAndUser('test recipe', $user)->shouldReturn($recipe);
    }

    public function it_sorts_the_recipes_by_rating_when_finding_all()
    {
        $user = new User('test user');
        $recipe1 = new Recipe('test recipe 1', $user, new Stars(4));
        $recipe2 = new Recipe('test recipe 2', $user, new Stars(3));
        $recipe3 = new Recipe('test recipe 3', $user, new Stars(5));

        $this->add($recipe1);
        $this->add($recipe2);
        $this->add($recipe3);

        $this->findAll()->shouldReturn([$recipe3, $recipe1, $recipe2]);
    }
}
