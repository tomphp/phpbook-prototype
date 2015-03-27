<?php

namespace CocktailRater\Domain;

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
     * @param string $name
     *
     * @return Recipe
     *
     * @throws RecipeNotFoundException
     */
    public function fetchByNameAndUser($name, User $user)
    {
        $theRecipe = null;

        foreach ($this->repository->findAll() as $recipe) {
            if ($recipe->hasNameAndUser($name, $user)) {
                $theRecipe = $recipe;

                break;
            }
        }

        return $theRecipe;
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
