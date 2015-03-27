<?php

namespace CocktailRater\Domain;

interface RecipeRepository
{
    public function save(Recipe $recipe);

    /** @return Recipe[] */
    public function findAll();
}
