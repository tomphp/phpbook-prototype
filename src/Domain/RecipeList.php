<?php

namespace CocktailRater\Domain;

final class RecipeList
{
    /** @var Recipe */
    private $recipes = [];

    public function emptyList()
    {
    }

    public function add(Recipe $recipe)
    {
        $this->recipes[] = $recipe;
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

        foreach ($this->recipes as $recipe) {
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
        }, $this->recipes);

        // @todo sort objects instead
        usort($recipes, function ($a, $b) {
            return $a['stars'] < $b['stars'];
        });

        return $recipes;
    }
}
