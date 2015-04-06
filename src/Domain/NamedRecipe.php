<?php

namespace CocktailRater\Domain;

interface NamedRecipe
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasNameMatching($name);
}
