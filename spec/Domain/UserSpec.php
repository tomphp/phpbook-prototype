<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\UserId;
use CocktailRater\Domain\Username;
use CocktailRater\Domain\ViewableUser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use CocktailRater\Domain\User;

class UserSpec extends ObjectBehavior
{
    const USERNAME = 'test_user';
    const EMAIL    = 'test@email.com';

    function let()
    {
        $this->beConstructedWith(
            new Username(self::USERNAME),
            new Email(self::EMAIL)
        );
    }

    function it_returns_view_data()
    {
        $this->view()->shouldReturn([
            'username' => self::USERNAME,
            'email'    => self::EMAIL,
        ]);
    }

    function it_returns_data_for_storage()
    {
        $this->setId(new UserId('test_id'));

        $this->getForStorage()->shouldReturn([
            'id'       => 'test_id',
            'username' => self::USERNAME,
            'email'    => self::EMAIL,
        ]);
    }

    function it_can_be_constructed_from_storage_values()
    {
        $this->beConstructedThrough('fromStorageArray', [[
            'id'       => 'the_id',
            'username' => 'the_username',
            'email'    => 'static@email.com',
        ]]);

        $this->getForStorage()->shouldReturn([
            'id'       => 'the_id',
            'username' => 'the_username',
            'email'    => 'static@email.com',
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

    function it_is_the_same_as_a_user_with_the_same_id()
    {
        $id = new UserId('test_id');

        $this->setId($id);

        $other = new User(
            new Username(self::USERNAME),
            new Email(self::EMAIL)
        );
        $other->setId($id);

        $this->shouldBeSameAs($other);
    }

    function it_is_not_the_same_as_a_user_with_a_different_id()
    {
        $this->setId(new UserId('test_id'));

        $other = new User(
            new Username(self::USERNAME),
            new Email(self::EMAIL)
        );
        $other->setId(new UserId('different_id'));

        $this->shouldNotBeSameAs($other);
    }
}
