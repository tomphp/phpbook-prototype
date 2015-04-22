<?php

namespace CocktailRater\FileSystemRepository;

use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Exception\TooManyMatchingEntitiesException;
use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeId;
use CocktailRater\Domain\RecipeRepository;
use CocktailRater\Domain\Specification\Specification;
use CocktailRater\Domain\UserId;
use CocktailRater\Domain\UserRepository;

final class FileSystemRecipeRepository implements RecipeRepository
{
    /** @var FileSystemRepository */
    private $repository;

    /** @var UserRepository */
    private $userRepository;

    /** @param string $dbPath */
    public function __construct($dbPath, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        $this->repository = new FileSystemRepository(
            $dbPath,
            'recipe',
            Recipe::class,
            RecipeId::class,
            function (array $data) {
                $user = $this->userRepository->findById(new UserId($data['user']));

                return Recipe::fromStorageArray($data, $user);
            }
        );
    }

    public function clear()
    {
        $this->repository->clear();
    }

    public function save(Recipe $recipe)
    {
        $this->repository->save($recipe);
    }

    public function findById(RecipeId $id)
    {
        return $this->repository->findById($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function findOneBySpecification(Specification $specification)
    {
        return $this->repository->findOneBySpecification($specification);
    }
}
