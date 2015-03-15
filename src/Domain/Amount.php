<?php

namespace CocktailRater\Domain;

final class Amount
{
    /** @var Quantity */
    private $quantity;

    /** @var Units */
    private $units;

    /**
     * @param number $quantity
     * @param string $units
     *
     * @return self
     */
    public static function fromValues($quantity, $units)
    {
        return new self(new Quantity($quantity), new Units($units));
    }

    public function __construct(Quantity $quantity, Units $units)
    {
        $this->quantity = $quantity;
        $this->units = $units;
    }

    /** @return Quantity */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /** @return Units */
    public function getUnits()
    {
        return $this->units;
    }
}
