<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeId;
use CocktailRater\Domain\RecipeName;
use CocktailRater\Domain\RecipeRepository;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RecipeListSpec extends ObjectBehavior
{
    /** @var Recipe */
    private $recipe1;

    function let(RecipeRepository $repository)
    {
        $this->beConstructedWith($repository);

        $user        = new User(new Username('test user'));
        $method      = new Method('test method');
        $ingredients = new MeasuredIngredientList([]);

        $recipe1 = new Recipe(new RecipeName('test recipe 1'), $user, new Stars(4), $ingredients, $method);
        $recipe2 = new Recipe(new RecipeName('test recipe 2'), $user, new Stars(3), $ingredients, $method);
        $recipe3 = new Recipe(new RecipeName('test recipe 3'), $user, new Stars(5), $ingredients, $method);

        $this->recipe1 = $recipe1;

        $repository->findAll()->willReturn([$recipe1, $recipe2, $recipe3]);
    }

    public function it_adds_a_recipe_to_the_list($repository)
    {
        $user        = new User(new Username('test user'));
        $method      = new Method('test method');
        $ingredients = new MeasuredIngredientList([]);

        $recipe = new Recipe(new RecipeName('test recipe 1'), $user, new Stars(4), $ingredients, $method);

        $repository->save($recipe)->shouldBeCalled();

        $this->add($recipe);
    }

    public function it_sorts_the_recipes_by_rating_when_viewing()
    {
        $results = [
            [
                'name'                 => 'test recipe 3',
                'user'                 => ['name'          => 'test user'],
                'stars'                => 5,
                'measured_ingredients' => [],
                'method'               => 'test method'
            ],
            [
                'name'                 => 'test recipe 1',
                'user'                 => ['name'          => 'test user'],
                'stars'                => 4,
                'measured_ingredients' => [],
                'method'               => 'test method'
            ],
            [
                'name'                 => 'test recipe 2',
                'user'                 => ['name'          => 'test user'],
                'stars'                => 3,
                'measured_ingredients' => [],
                'method'               => 'test method'
            ],
        ];

        $this->view()->shouldReturn($results);
    }

    public function it_fetches_by_user_and_name()
    {
        $this->fetchByNameAndUser(new RecipeName('test recipe 1'), new User(new Username('test user')))
             ->shouldReturn($this->recipe1);
    }

    public function it_fetches_by_id($repository)
    {
        $repository->findById(new RecipeId('test_id'))->willReturn($this->recipe1);

        $this->fetchById(new RecipeId('test_id'))->shouldReturn($this->recipe1);
    }
}
