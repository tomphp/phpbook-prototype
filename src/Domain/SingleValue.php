<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

trait SingleValue
{
    /** @var scalar */
    protected $value;

    /** @param scalar $value */
    public function __construct($value)
    {
        Assertion::scalar($value);

        $this->validate($value);

        $this->value = $this->normalise($value);
    }

    /** @return scalar */
    public function getValue()
    {
        return $this->value;
    }

    protected function validate()
    {
    }

    /** @param scalar $value
     *
     * @return scalar
     */
    protected function normalise($value)
    {
        return $value;
    }
}
