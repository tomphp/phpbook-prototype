<?php

namespace CocktailRater\Domain\Exception;

use RuntimeException;

final class UsernameTakenException extends RuntimeException implements
    RegistrationException
{
}
