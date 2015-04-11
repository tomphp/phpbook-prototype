<?php

namespace CocktailRater\Domain;

use CocktailRater\Domain\Exception\AuthenticationException;
use CocktailRater\Domain\Exception\DuplicateEntryException;
use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Exception\UsernameTakenException;
use CocktailRater\Domain\Specification\AuthenticatedBySpecification;

final class AuthenticationService
{
    /** @var UserRepository */
    private $repository;

    /** @var bool */
    private $loggedIn = false;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UsernameTakenException
     */
    public function register(ProspectiveUser $user)
    {
        try {
            $this->repository->save($user->convertToUser());
        } catch (DuplicateEntryException $e) {
            throw new UsernameTakenException();
        }
    }

    /**
     * @throws AuthenticationException
     */
    public function logIn(Username $username, Password $password)
    {
        try {
            $user = $this->repository->findOneBySpecification(
                new AuthenticatedBySpecification($username, $password)
            );

            $this->loggedIn = true;

        } catch (EntityNotFoundException $e) {
            throw new AuthenticationException(
                'Log in failed for username ' . $username->getValue()
            );
        }
    }

    /** @return bool */
    public function isLoggedIn()
    {
        return $this->loggedIn;
    }
}
