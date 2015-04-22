<?php

namespace spec\CocktailRater\Domain\Specification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\Username;
use CocktailRater\Domain\NamedUser;

class UsernameSpecificationSpec extends ObjectBehavior
{
    const USERNAME = 'test_username';

    function let()
    {
        $this->beConstructedWith(new Username(self::USERNAME));
    }

    function it_is_satisified_if_username_matches(NamedUser $candidate)
    {
        $candidate->hasUsername(new Username(self::USERNAME))->willReturn(true);

        $this->shouldBeSatisfiedBy($candidate);
    }

    function it_is_not_satisified_if_username_does_not_match(NamedUser $candidate)
    {
        $candidate->hasUsername(new Username(self::USERNAME))->willReturn(false);

        $this->shouldNotBeSatisfiedBy($candidate);
    }
}
