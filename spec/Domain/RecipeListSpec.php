<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;

class RecipeListSpec extends ObjectBehavior
{
    /** @var Recipe */
    private $recipe1;

    function let()
    {
        $user        = new User('test user');
        $method      = new Method('test method');
        $ingredients = new MeasuredIngredientList([]);

        $recipe1 = new Recipe('test recipe 1', $user, new Stars(4), $ingredients, $method);
        $recipe2 = new Recipe('test recipe 2', $user, new Stars(3), $ingredients, $method);
        $recipe3 = new Recipe('test recipe 3', $user, new Stars(5), $ingredients, $method);

        $this->recipe1 = $recipe1;

        $this->add($recipe1);
        $this->add($recipe2);
        $this->add($recipe3);
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

    public function it_fetches_user_by_name()
    {
        $this->fetchByNameAndUser('test recipe 1', new User('test user'))
             ->shouldReturn($this->recipe1);
    }
}
