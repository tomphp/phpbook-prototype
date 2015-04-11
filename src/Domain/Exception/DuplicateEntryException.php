<?php

namespace CocktailRater\Domain\Exception;

use RuntimeException;

final class DuplicateEntryException extends RuntimeException
{
    /** @var string */
    private $field;

    /**
     * @param string $field
     * @param mixed  $value
     * @param string $repository
     */
    public function __construct($field, $value, $repository)
    {
        parent::__construct(sprintf(
            "Duplicate entry value '%s' in field '%s' in '%s'",
            $value,
            $field,
            $repository
        ));

        $this->field = $field;
    }

    /** @return string */
    public function getField()
    {
        return $this->field;
    }
}
