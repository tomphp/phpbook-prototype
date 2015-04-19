<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\User;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProspectiveUserSpec extends ObjectBehavior
{
    const USERNAME = 'tom';
    const EMAIL    = 'tom@x2k.co.uk';

    function let()
    {
        $this->beConstructedThrough(
            'fromValues',
            [
                self::USERNAME,
                self::EMAIL,
                'topsecret',
            ]
        );
    }

    function it_returns_its_username()
    {
        $this->getUsername()->shouldBeLike(new Username(self::USERNAME));
        // @todo Other fields
    }

    function it_can_be_converted_to_a_user()
    {
        $this->convertToUser()->shouldBeLike(new User(new Username(self::USERNAME), new Email(self::EMAIL)));
    }
}
