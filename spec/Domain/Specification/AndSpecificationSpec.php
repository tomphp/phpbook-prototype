<?php

namespace spec\CocktailRater\Domain\Specification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\Specification\Specification;
use stdClass;

class AndSpecificationSpec extends ObjectBehavior
{
    function let(Specification $s1, Specification $s2)
    {
        $this->beConstructedWith($s1, $s2);
    }

    function it_is_satified_if_both_specifications_are_satisfied($s1, $s2, stdClass $candidate)
    {
        $s1->isSatisfiedBy($candidate)->willReturn(true);
        $s2->isSatisfiedBy($candidate)->willReturn(true);

        $this->shouldBeSatisfiedBy($candidate);
    }

    function it_is_not_satified_if_both_specifications_are_not_satisfied($s1, $s2, stdClass $candidate)
    {
        $s1->isSatisfiedBy($candidate)->willReturn(false);
        $s2->isSatisfiedBy($candidate)->willReturn(false);

        $this->shouldNotBeSatisfiedBy($candidate);
    }

    function it_is_not_satified_if_either_specifications_are_not_satisfied($s1, $s2, stdClass $candidate)
    {
        $s1->isSatisfiedBy($candidate)->willReturn(true);
        $s2->isSatisfiedBy($candidate)->willReturn(false);

        $this->shouldNotBeSatisfiedBy($candidate);
    }
}
