<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\Username;

class ProspectiveUserSpec extends ObjectBehavior
{
    function it_can_be_constructed_from_values()
    {
        $this->beConstructedThrough(
            'fromValues',
            [
                'tom',
                'tom@x2k.co.uk',
                'topsecret',
            ]
        );

        $this->getUsername()->shouldBeLike(new Username('tom'));
    }
}
