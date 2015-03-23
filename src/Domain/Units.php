<?php

namespace CocktailRater\Domain;

final class Units
{
    const FL_OZ = 'fl oz';
    const ML    = 'ml';
    const TSP   = 'tsp';
    const TBSP  = 'tbsp';
    const COUNT = '';

    /** @var string */
    private $value;

    /** @var string[] */
    private $count = [
        self::FL_OZ,
        self::ML,
        self::TSP,
        self::TBSP,
        self::COUNT
    ];

    /** @var string $value */
    public function __construct($value)
    {
        // @todo Error check and throw

        $this->value = $value;
    }

    /** @return string */
    public function getValue()
    {
        return $this->value;
    }
}
