<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Specification\Specification;
use CocktailRater\Domain\Exception\EntityNotFoundException;

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

    /**
     * @return Recipe
     *
     * @throws EntityNotFoundException
     */
    public function findOneBySpecification(Specification $specification);
}
