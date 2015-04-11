<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Password;
use CocktailRater\Domain\UserId;
use CocktailRater\Domain\Username;
use CocktailRater\Domain\ViewableUser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{
    const USERNAME = 'test_user';

    function let()
    {
        $this->beConstructedWith(new Username(self::USERNAME));
    }

    function it_returns_view_data()
    {
        $this->view()->shouldReturn(['name' => self::USERNAME]);
    }

    function it_returns_data_for_storage()
    {
        $this->setId(new UserId('test_id'));

        $this->getForStorage()->shouldReturn([
            'id'   => 'test_id',
            'name' => self::USERNAME
        ]);
    }

    function it_can_be_constructed_from_storage_values()
    {
        $this->beConstructedThrough('fromStorageArray', [[
            'id'   => 'the_id',
            'name' => 'the_username'
        ]]);

        $this->getForStorage()->shouldReturn([
            'id'   => 'the_id',
            'name' => 'the_username'
        ]);
    }

    function it_will_be_authenticated_by_matching_credentials()
    {
        $this->shouldBeAuthenticatedBy(
            new Username(self::USERNAME),
            new Password('test_password')
        );
    }

    function it_will_not_be_authenticated_by_different_username()
    {
        $this->shouldNotBeAuthenticatedBy(
            new Username('wrong_user'),
            new Password('test_password')
        );
    }
}
