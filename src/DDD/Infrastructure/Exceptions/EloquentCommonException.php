<?php

declare(strict_types=1);

namespace DDD\Infrastructure\Exceptions;

use Exception;

final class EloquentCommonException extends Exception {
    public const MESSAGE = 'Eloquent Exception. Check Errors on your data.';

    public function __construct(string $message)
    {
        parent::__construct($message ?? self::MESSAGE);
    }
}
