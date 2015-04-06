<?php

namespace CocktailRater\FileSystemRepository;

use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Exception\TooManyMatchingEntitiesException;
use CocktailRater\Domain\Specification\Specification;
use CocktailRater\Domain\UserRepository;
use CocktailRater\Domain\User;

final class FileSystemUserRepository implements UserRepository
{
    /** @var string */
    private $tablePath;

    /** @param string $dbPath */
    public function __construct($dbPath)
    {
        $this->tablePath = $dbPath . DIRECTORY_SEPARATOR . 'users.db';
    }

    public function clear()
    {
        file_put_contents($this->tablePath, serialize([]));
    }

    public function findAll()
    {
        /*
        return array_map(function ($row) {
            return Recipe::fromStorageArray($row);
        }, $this->getRows());
         */
    }

    public function findOneBySpecification(Specification $specification)
    {
        $users = array_values(array_filter(
            $this->findAll(),
            function (User $user) use ($specification) {
                return $specification->isSatisfiedBy($user);
            }
        ));

        if (empty($users)) {
            // @todo exception factory
            throw new EntityNotFoundException(
                'No users matching specification were found.'
            );
        }

        if (count($users) > 1) {
            // @todo exception factory
            throw new TooManyMatchingEntitiesException(
                'More than one matching user was found.'
            );
        }

        return $users[0];
    }
}
