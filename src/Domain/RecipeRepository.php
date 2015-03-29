<?php

namespace CocktailRater\Domain;

interface RecipeRepository
{
    public function save(Recipe $recipe);

    /**
     * @return Recipe
     *
     * @todo throw?
     */
    public function findById(RecipeId $id);

    /** @return Recipe[] */
    public function findAll();
}
