<?php

namespace tests\CocktailRater\Domain;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Exception\TooManyMatchingEntitiesException;
use CocktailRater\Domain\MeasuredIngredientList;
use CocktailRater\Domain\Method;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeName;
use CocktailRater\Domain\RecipeRepository;
use CocktailRater\Domain\Specification\AndSpecification;
use CocktailRater\Domain\Specification\RecipeNameSpecification;
use CocktailRater\Domain\Specification\UserSpecification;
use CocktailRater\Domain\Stars;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use CocktailRater\FileSystemRepository\FileSystemRecipeRepository;
use CocktailRater\FileSystemRepository\FileSystemUserRepository;
use PHPUnit_Framework_TestCase;

class RecipeRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var RecipeRepository */
    private $repository;

    /** @var User */
    private $user1;

    /** @var User */
    private $user2;

    protected function setUp()
    {
        $userRepository = new FileSystemUserRepository(__DIR__ . '/../../test-fsdb');

        $userRepository->clear();

        $this->user1 = new User(new Username('user1'), new Email('test1@email.com'));
        $this->user2 = new User(new Username('user2'), new Email('test2@email.com'));

        $userRepository->save($this->user1);
        $userRepository->save($this->user2);

        $this->repository = new FileSystemRecipeRepository(__DIR__ . '/../../test-fsdb', $userRepository);

        $this->repository->clear();

        $this->repository->save(Recipe::withNoId(
            new RecipeName('recipe 1'),
            $this->user1,
            new Stars(3),
            new MeasuredIngredientList([]),
            new Method('method 1')
        ));

        $this->repository->save(Recipe::withNoId(
            new RecipeName('recipe 2'),
            $this->user2,
            new Stars(4),
            new MeasuredIngredientList([]),
            new Method('method 2')
        ));

        $this->repository->save(Recipe::withNoId(
            new RecipeName('recipe 3'),
            $this->user1,
            new Stars(5),
            new MeasuredIngredientList([]),
            new Method('method 3')
        ));
    }

    /** @test */
    function it_fetches_all_recipes()
    {
        $this->assertCount(3, $this->repository->findAll());
    }

    /** @test */
    function it_throws_if_findOneBySpecification_finds_none()
    {
        $this->setExpectedException(
            EntityNotFoundException::class,
            // @todo describe specification
            'No recipes matching specification were found.'
        );

        $this->repository->findOneBySpecification(
            new RecipeNameSpecification(new RecipeName('recipe which doesn\'t exist'))
        );
    }

    /** @test */
    function it_throws_if_findOneBySpecification_finds_many()
    {
        $this->setExpectedException(
            TooManyMatchingEntitiesException::class,
            // @todo describe specification
            'More than one matching recipe was found.'
        );

        $this->repository->findOneBySpecification(
            new UserSpecification($this->user1)
        );
    }

    /** @test */
    function it_finds_one_by_name_and_user()
    {
        $specification = new AndSpecification(
            new RecipeNameSpecification(new RecipeName('recipe 1')),
            new UserSpecification($this->user1)
        );

        $expected = array_values(array_filter(
            $this->repository->findAll(),
            function (Recipe $recipe) use ($specification) {
                return $specification->isSatisfiedBy($recipe);
            }
        ))[0];

        $this->assertEquals($expected, $this->repository->findOneBySpecification($specification));
    }
}
