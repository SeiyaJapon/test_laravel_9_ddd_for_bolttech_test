<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions;

use Throwable;

final class InvalidUuid extends \Exception
{
    private const MESSAGE = '%s is not a valid UUID';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $message), $code, $previous);
    }
}
