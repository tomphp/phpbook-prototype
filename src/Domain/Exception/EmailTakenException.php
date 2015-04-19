<?php

namespace CocktailRater\Domain\Exception;

use RuntimeException;

final class EmailTakenException extends RuntimeException implements
    RegistrationException
{
}
