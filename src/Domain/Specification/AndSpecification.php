<?php

namespace CocktailRater\Domain\Specification;

final class AndSpecification implements Specification
{
    /** @var Specification */
    private $spec1;

    /** @var Specification */
    private $spec2;

    public function __construct(Specification $spec1, Specification $spec2)
    {
        $this->spec1 = $spec1;
        $this->spec2 = $spec2;
    }

    public function isSatisfiedBy($candidate)
    {
        return $this->spec1->isSatisfiedBy($candidate)
            && $this->spec2->isSatisfiedBy($candidate);
    }
}
