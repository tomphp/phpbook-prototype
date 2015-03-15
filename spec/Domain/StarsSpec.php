<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\Stars;

class StarsSpec extends ObjectBehavior
{
    function it_returns_its_value()
    {
        $this->beConstructedWith(3);

        $this->getValue()->shouldReturn(3);
    }

    /*
    function it_checks_if_is_higher_rated_than_another_instance()
    {
        $this->beConstructedWith(3);

        $this->shouldBeHigherRatedThan(new Stars(2));
        $this->shouldNotBeHigherRatedThan(new Stars(5));
    }
     */
}
