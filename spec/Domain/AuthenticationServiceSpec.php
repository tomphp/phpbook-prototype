<?php

namespace spec\CocktailRater\Domain;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\Exception\AuthenticationException;
use CocktailRater\Domain\Exception\DuplicateEntryException;
use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Exception\UsernameTakenException;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\ProspectiveUser;
use CocktailRater\Domain\Specification\AuthenticatedBySpecification;
use CocktailRater\Domain\User;
use CocktailRater\Domain\UserRepository;
use CocktailRater\Domain\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthenticationServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_stores_registering_user_to_the_repository($repository)
    {
        $prospectiveUser = new ProspectiveUser(
            new Username('tom'),
            new Email('test1@gmail.com'),
            new Password('dummy_pass')
        );

        $repository->save($prospectiveUser->convertToUser())
                   ->shouldBeCalled();

        $this->register($prospectiveUser);
    }

    function it_checks_for_duplicate_usernames($repository)
    {
        $repository->save(Argument::any())->willThrow(
            new DuplicateEntryException(UserRepository::USERNAME, 'tom', UserRepository::class)
        );

        $this->shouldThrow(new UsernameTakenException())
             ->duringRegister(new ProspectiveUser(
                new Username('tom'),
                new Email('test1@gmail.com'),
                new Password('dummy_pass')
            ));
    }

    function it_is_not_logged_in_by_default()
    {
        $this->shouldNotBeLoggedIn();
    }

    function it_throws_if_login_fails($repository)
    {
        $username = new Username('test_user');
        $password = new Password('test_password');

        $repository->findOneBySpecification(Argument::any())
                   ->willThrow(new EntityNotFoundException());

        $this->shouldThrow(new AuthenticationException(
            'Log in failed for username test_user'
        ))->duringLogIn($username, $password);
    }

    function it_successfully_logs_in($repository)
    {
        $username = new Username('test_user');
        $password = new Password('test_password');
        $user = new User($username);

        $specification = new AuthenticatedBySpecification($username, $password);

        $repository->findOneBySpecification($specification)
                   ->willReturn($user);

        $this->logIn($username, $password);

        $this->shouldBeLoggedIn();
    }
}
