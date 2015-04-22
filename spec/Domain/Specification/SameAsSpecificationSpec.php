<?php

namespace spec\CocktailRater\Domain\Specification;

use CocktailRater\Domain\Equality;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SameAsSpecificationSpec extends ObjectBehavior
{
    function it_is_satisfied_by_something_which_is_the_same(Equality $target, Equality $candidate)
    {
        $this->beConstructedWith($target);

        $candidate->isSameAs($target)->willReturn(true);

        $this->shouldBeSatisfiedBy($candidate);
    }

    function it_is_not_satisfied_by_something_which_is_not_the_same(Equality $target, Equality $candidate)
    {
        $this->beConstructedWith($target);

        $candidate->isSameAs($target)->willReturn(false);

        $this->shouldNotBeSatisfiedBy($candidate);
    }
}
