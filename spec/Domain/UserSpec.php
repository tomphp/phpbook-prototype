<?php

namespace spec\CocktailRater\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\ViewableUser;
use CocktailRater\Domain\Username;
use CocktailRater\Domain\UserId;

class UserSpec extends ObjectBehavior
{
    function it_returns_view_data()
    {
        $this->beConstructedWith(new Username('test user'));

        $this->view()->shouldReturn(['name' => 'test user']);
    }

    function it_returns_data_for_storage()
    {
        $this->beConstructedWith(new Username('test user'));
        $this->setId(new UserId('test_id'));

        $this->getForStorage()->shouldReturn([
            'id'   => 'test_id',
            'name' => 'test user'
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
}
