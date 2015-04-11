<?php

namespace CocktailRater\FileSystemRepository;

use CocktailRater\Domain\Exception\DuplicateEntryException;
use CocktailRater\Domain\Specification\Specification;
use CocktailRater\Domain\User;
use CocktailRater\Domain\UserId;
use CocktailRater\Domain\UserRepository;
use CocktailRater\Domain\Username;

final class FileSystemUserRepository implements UserRepository
{
    /** @var FileSystemRepository */
    private $repository;

    /** @param string $dbPath */
    public function __construct($dbPath)
    {
        $this->repository = new FileSystemRepository(
            $dbPath,
            'user',
            User::class,
            UserId::class
        );
    }

    public function clear()
    {
        $this->repository->clear();
    }

    public function save(User $user)
    {
        $this->repository->save(
            $user,
            function ($entityData, array $entities) {
                $this->assertUsernameIsUnique($entityData, $entities);
            }
        );
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function findOneBySpecification(Specification $specification)
    {
        return $this->repository->findOneBySpecification($specification);
    }

    private function assertUsernameIsUnique($user, array $rows)
    {
        $matching = array_filter(
            $rows,
            function (array $row) use ($user) {
                return $row['username'] === $user['username'];
            }
        );

        if (!empty($matching)) {
            throw new DuplicateEntryException(self::USERNAME, $user['username'], __CLASS__);
        }
    }
}
