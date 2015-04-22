<?php

namespace CocktailRater\Domain;

interface Equality
{
    /** @return bool */
    public function isSameAs($other);
}
