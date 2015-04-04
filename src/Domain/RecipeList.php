<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Specification\RecipeNameSpecification;
use CocktailRater\Domain\Specification\UserSpecification;
use CocktailRater\Domain\Specification\AndSpecification;

final class RecipeList
{
    /** @var RecipeRepository */
    private $repository;

    public function __construct(RecipeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function emptyList()
    {
    }

    public function add(Recipe $recipe)
    {
        $this->repository->save($recipe);
    }

    /**
     * @return Recipe
     *
     * @todo throw
     * @throws RecipeNotFoundException
     */
    public function fetchById(RecipeId $id)
    {
        return $this->repository->findById($id);
    }

    /**
     * @return Recipe
     *
     * @todo throw
     * @throws RecipeNotFoundException
     */
    public function fetchByNameAndUser(RecipeName $name, User $user)
    {
        $specification = new AndSpecification(
            new RecipeNameSpecification('test recipe'),
            new UserSpecification($user)
        );

        return $this->repository->findOneBySpecification($specification);
    }

    /** @return array */
    public function view()
    {
        $recipes = array_map(function (Recipe $recipe) {
            return $recipe->view();
        }, $this->repository->findAll());

        // @todo sort objects instead
        usort($recipes, function ($a, $b) {
            return $a['stars'] < $b['stars'];
        });

        return $recipes;
    }
}
