<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeId;
use CocktailRater\Domain\RecipeName;
use CocktailRater\Domain\RecipeRepository;
use CocktailRater\Domain\Specification\AndSpecification;
use CocktailRater\Domain\Specification\RecipeNameSpecification;
use CocktailRater\Domain\Specification\UserSpecification;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RecipeListSpec extends ObjectBehavior
{
    const USERNAME = 'test_user';
    const EMAIL    = 'test@email.com';

    /** @var Recipe */
    private $recipe1;

    function let(RecipeRepository $repository)
    {
        $this->beConstructedWith($repository);

        $user        = new User(new Username(self::USERNAME), new Email(self::EMAIL));
        $method      = new Method('test method');
        $ingredients = new MeasuredIngredientList([]);

        // @todo Can an abstraction be used???
        $recipe1 = new Recipe(new RecipeName('test recipe 1'), $user, new Stars(4), $ingredients, $method);
        $recipe2 = new Recipe(new RecipeName('test recipe 2'), $user, new Stars(3), $ingredients, $method);
        $recipe3 = new Recipe(new RecipeName('test recipe 3'), $user, new Stars(5), $ingredients, $method);

        $this->recipe1 = $recipe1;

        $repository->findAll()->willReturn([$recipe1, $recipe2, $recipe3]);
    }

    public function it_adds_a_recipe_to_the_list($repository)
    {
        $user        = new User(new Username(self::USERNAME), new Email(self::EMAIL));
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
                'user'                 => ['username' => self::USERNAME, 'email' => self::EMAIL],
                'stars'                => 5,
                'measured_ingredients' => [],
                'method'               => 'test method'
            ],
            [
                'name'                 => 'test recipe 1',
                'user'                 => ['username' => self::USERNAME, 'email' => self::EMAIL],
                'stars'                => 4,
                'measured_ingredients' => [],
                'method'               => 'test method'
            ],
            [
                'name'                 => 'test recipe 2',
                'user'                 => ['username' => self::USERNAME, 'email' => self::EMAIL],
                'stars'                => 3,
                'measured_ingredients' => [],
                'method'               => 'test method'
            ],
        ];

        $this->view()->shouldReturn($results);
    }

    public function it_fetches_by_user_and_name($repository)
    {
        $user = new User(new Username(self::USERNAME), new Email(self::EMAIL));

        $specification = new AndSpecification(
            new RecipeNameSpecification(new RecipeName('test recipe')),
            new UserSpecification($user)
        );

        $repository->findOneBySpecification($specification)->willReturn($this->recipe1);

        $this->fetchByNameAndUser(new RecipeName('test recipe'), $user)
             ->shouldReturn($this->recipe1);
    }

    public function it_fetches_by_id($repository)
    {
        $repository->findById(new RecipeId('test_id'))->willReturn($this->recipe1);

        $this->fetchById(new RecipeId('test_id'))->shouldReturn($this->recipe1);
    }
}
