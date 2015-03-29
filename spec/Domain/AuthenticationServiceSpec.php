<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\Exception\UsernameTakenException;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\ProspectiveUser;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthenticationServiceSpec extends ObjectBehavior
{
    function it_checks_for_duplicate_usernames()
    {
        $this->register(new ProspectiveUser(
            new Username('tom'),
            new Email('test1@gmail.com'),
            new Password('dummy_pass')
        ));

        $this->shouldThrow(new UsernameTakenException())
             ->duringRegister(new ProspectiveUser(
                new Username('tom'),
                new Email('test1@gmail.com'),
                new Password('dummy_pass')
            ));
    }
}
