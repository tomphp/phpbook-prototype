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

        usort($this->recipes, function ($a, $b) {
            return $b->isHigherRatedThan($a);
        });
    }

    /**
     * @param string $name
     *
     * @return Recipe
     *
     * @throws RecipeNotFoundException
     */
    public function findByNameAndUser($name, User $user)
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

    public function findAll()
    {
        return $this->recipes;
    }
}
