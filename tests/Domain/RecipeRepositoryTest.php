<?php

namespace tests\CocktailRater\Domain;

use PHPUnit_Framework_TestCase;
use CocktailRater\Domain\RecipeRepository;
use CocktailRater\FileSystemRepository\FileSystemRecipeRepository;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Specification\AndSpecification;
use CocktailRater\Domain\Specification\RecipeNameSpecification;
use CocktailRater\Domain\Specification\UserSpecification;

class RecipeRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var RecipeRepository */
    private $repository;

    protected function setUp()
    {
        $this->repository = new FileSystemRecipeRepository(__DIR__ . '/../../test-fsdb');

        $this->repository->clear();

        $this->repository->save(Recipe::withNoId(
            'recipe 1',
            new User(new Username('user1')),
            new Stars(3),
            new MeasuredIngredientList([]),
            new Method('method 1')
        ));

        $this->repository->save(Recipe::withNoId(
            'recipe 2',
            new User(new Username('user2')),
            new Stars(4),
            new MeasuredIngredientList([]),
            new Method('method 2')
        ));
    }

    /** @test */
    function it_fetches_all_recipes()
    {
        $this->assertCount(2, $this->repository->findAll());
    }

    /** @test */
    function it_finds_one_by_name_and_user()
    {
        $specification = new AndSpecification(
            new RecipeNameSpecification('recipe 1'),
            new UserSpecification(new User(new Username('user1')))
        );

        $expected = array_values(array_filter(
            $this->repository->findAll(),
            function (Recipe $recipe) use ($specification) {
                return $specification->isSatisfiedBy($recipe);
            }
        ))[0];

        $this->assertEquals($expected, $this->repository->findBySpecification($specification));
    }
}
