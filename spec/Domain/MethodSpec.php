<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MethodSpec extends ObjectBehavior
{
    function it_provides_values()
    {
        $this->beConstructedWith('test method');

        $this->getValue()->shouldReturn('test method');
    }
}
