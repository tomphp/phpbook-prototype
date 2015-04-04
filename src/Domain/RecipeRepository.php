<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Specification\Specification;

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

    /** @return Recipe */
    public function findOneBySpecification(Specification $specification);
}
