<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions;

use DDD\Domain\Exceptions\Common\DomainException;
use Throwable;

final class EmailNotValid extends DomainException
{
    private const MESSAGE = '%s is not a valid email';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE, $message), $code, $previous);
    }
}
