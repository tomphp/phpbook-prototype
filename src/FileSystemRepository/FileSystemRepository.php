<?php

namespace CocktailRater\FileSystemRepository;

use CocktailRater\Domain\Specification\Specification;
use CocktailRater\Domain\Exception\EntityNotFoundException;
use CocktailRater\Domain\Exception\TooManyMatchingEntitiesException;

final class FileSystemRepository
{
    /** @var string */
    private $tablePath;

    /** @var string */
    private $name;

    /** @var string */
    private $entityClass;

    /** @var string */
    private $idClass;

    /**
     * @param string $dbPath
     * @param string $name
     * @param string $entityClass
     * @param string $idClass
     */
    public function __construct($dbPath, $name, $entityClass, $idClass)
    {
        $this->name        = $name;
        $this->entityClass = $entityClass;
        $this->idClass     = $idClass;

        $this->tablePath = $dbPath . DIRECTORY_SEPARATOR . "{$name}s.db";
    }

    public function clear()
    {
        file_put_contents($this->tablePath, serialize([]));
    }

    public function save($entity, callable $validator = null)
    {
        $entities = $this->getRows();

        $id = $entity->getId();

        if (!$id) {
            $idClass = $this->idClass;
            $id = new $idClass(uniqid("{$this->name}_"));

            $entity->setId($id);
        }

        $entityData = $entity->getForStorage();

        if ($validator) {
            $validator($entityData, $entities);
        }

        $entities[$id->getValue()] = $entityData;

        file_put_contents($this->tablePath, serialize($entities));
    }

    public function findById($id)
    {
        return call_user_func(
            [$this->entityClass, 'fromStorageArray'],
            $this->getRows()[$id->getValue()]
        );
    }


    public function findAll()
    {
        return array_map(function ($row) {
            return call_user_func([$this->entityClass, 'fromStorageArray'], $row);
        }, $this->getRows());
    }

    public function findOneBySpecification(Specification $specification)
    {
        $entities = array_values(array_filter(
            $this->findAll(),
            function ($entity) use ($specification) {
                return $specification->isSatisfiedBy($entity);
            }
        ));

        if (empty($entities)) {
            // @todo exception factory
            throw new EntityNotFoundException(
                "No {$this->name}s matching specification were found."
            );
        }

        if (count($entities) > 1) {
            // @todo exception factory
            throw new TooManyMatchingEntitiesException(
                "More than one matching {$this->name} was found."
            );
        }

        return $entities[0];
    }

    /** @return array */
    private function getRows()
    {
        if (!file_exists($this->tablePath)) {
            return [];
        }

        return unserialize(file_get_contents($this->tablePath));
    }
}
