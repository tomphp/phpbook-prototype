<?php

namespace tests\CocktailRater\Domain;

use CocktailRater\Domain\Email;
use CocktailRater\Domain\Exception\DuplicateEntryException;
use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Password;
use CocktailRater\Domain\Specification\AuthenticatedBySpecification;
use CocktailRater\Domain\User;
use CocktailRater\Domain\UserRepository;
use CocktailRater\Domain\Username;
use CocktailRater\FileSystemRepository\FileSystemUserRepository;
use PHPUnit_Framework_TestCase;

class UserRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var UserRepository */
    private $repository;

    protected function setUp()
    {
        $this->repository = new FileSystemUserRepository(__DIR__ . '/../../test-fsdb');

        $this->repository->clear();

        $this->repository->save(new User(
            new Username('fred'),
            new Email('fred@gmail.com')
        ));

        $this->repository->save(new User(
            new Username('ted'),
            new Email('ted@gmail.com')
        ));
    }

    /**
     * Detect duplication at repository level to allow for transactional safety.
     *
     * @test
     */
    function it_throws_for_duplicate_username()
    {
        $this->setExpectedException(
            DuplicateEntryException::class,
            "Duplicate entry value 'fred' in field 'username' in '" . get_class($this->repository) . "'"
        );

        $this->repository->save(new User(
            new Username('fred'),
            new Email('jane@gmail.com')
        ));
    }

    /**
     * Detect duplication at repository level to allow for transactional safety.
     *
     * @test
     */
    function it_throws_for_duplicate_email()
    {
        $this->setExpectedException(
            DuplicateEntryException::class,
            "Duplicate entry value 'fred@gmail.com' in field 'email' in '" . get_class($this->repository) . "'"
        );

        $this->repository->save(new User(
            new Username('jane'),
            new Email('fred@gmail.com')
        ));
    }

    /** @test */
    function it_fetches_all_recipes()
    {
        $this->assertCount(2, $this->repository->findAll());
    }

    /** @test */
    function it_throws_if_findOneBySpecification_finds_none()
    {
        $this->setExpectedException(
            EntityNotFoundException::class,
            // @todo describe specification
            'No users matching specification were found.'
        );

        $this->repository->findOneBySpecification(
            new AuthenticatedBySpecification(new Username('unknown'), new Password('xxx'))
        );
    }

    /** @test */
    /*
    function it_throws_if_findOneBySpecification_finds_many()
    {
        $this->setExpectedException(
            TooManyMatchingEntitiesException::class,
            // @todo describe specification
            'More than one matching user was found.'
        );

        $this->repository->findOneBySpecification(
            new UserSpecification(new User(new Username('user1')))
        );
    }
     */

    /** @test */
    function it_finds_one_by_creditials()
    {
        $specification = new AuthenticatedBySpecification(
            new Username('ted'),
            new Password('tedspassword')
        );

        $expected = array_values(array_filter(
            $this->repository->findAll(),
            function (User $user) use ($specification) {
                return $specification->isSatisfiedBy($user);
            }
        ))[0];

        $this->assertEquals($expected, $this->repository->findOneBySpecification($specification));
    }
}
