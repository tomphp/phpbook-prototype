<?php

namespace CocktailRater\FileSystemRepository;

use CocktailRater\Domain\Exception\DuplicateEntryException;
use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Exception\TooManyMatchingEntitiesException;
use CocktailRater\Domain\Specification\Specification;
use CocktailRater\Domain\User;
use CocktailRater\Domain\UserId;
use CocktailRater\Domain\UserRepository;
use CocktailRater\Domain\Username;

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

    public function save(User $user)
    {
        $users = $this->getRows();

        $id = $user->getId();

        if (!$id) {
            $id = new UserId(uniqid('user_'));

            $user->setId($id);
        }

        $userData = $user->getForStorage();

        $this->assertUsernameIsUnique($userData['name'], $users);

        $users[$id->getValue()] = $userData;

        file_put_contents($this->tablePath, serialize($users));
    }

    public function findAll()
    {
        return array_map(function ($row) {
            return User::fromStorageArray($row);
        }, $this->getRows());
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

    /** @return array */
    private function getRows()
    {
        if (!file_exists($this->tablePath)) {
            return [];
        }

        return unserialize(file_get_contents($this->tablePath));
    }

    private function assertUsernameIsUnique($username, array $rows)
    {
        $matching = array_filter(
            $rows,
            function (array $row) use ($username) {
                return $row['name'] === $username;
            }
        );

        if (!empty($matching)) {
            throw new DuplicateEntryException(self::USERNAME, $username, __CLASS__);
        }
    }
}
