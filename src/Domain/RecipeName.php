<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class RecipeName
{
    use SingleValue;

    protected function validate($value)
    {
        Assertion::string($value);
    }
}
