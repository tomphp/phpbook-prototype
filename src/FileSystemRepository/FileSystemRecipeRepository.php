<?php

namespace CocktailRater\FileSystemRepository;

use CocktailRater\Domain\Recipe;
use CocktailRater\Domain\RecipeId;
use CocktailRater\Domain\RecipeRepository;
use CocktailRater\Domain\Specification\Specification;

final class FileSystemRecipeRepository implements RecipeRepository
{
    /** @var string */
    private $tablePath;

    /** @param string $dbPath */
    public function __construct($dbPath)
    {
        $this->tablePath = $dbPath . DIRECTORY_SEPARATOR . 'recipes.db';
    }

    public function clear()
    {
        file_put_contents($this->tablePath, serialize([]));
    }

    public function save(Recipe $recipe)
    {
        $recipes = $this->getRows();

        $id = $recipe->getId();

        if (!$id) {
            $id = new RecipeId(uniqid('recipe_'));

            $recipe->setId($id);
        }

        $recipes[$id->getValue()] = $recipe->getForStorage();

        file_put_contents($this->tablePath, serialize($recipes));
    }

    public function findById(RecipeId $id)
    {
        return Recipe::fromStorageArray($this->getRows()[$id->getValue()]);
    }

    public function findAll()
    {
        return array_map(function ($row) {
            return Recipe::fromStorageArray($row);
        }, $this->getRows());
    }

    public function findOneBySpecification(Specification $specification)
    {
    }

    /** @return array */
    private function getRows()
    {
        if (!file_exists($this->tablePath)) {
            return [];
        }

        return unserialize(file_get_contents($this->tablePath));
    }
}
