<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;
use Throwable;

final class UnableToBuildUserOldException extends DomainException {
    public const MESSAGE = 'Unable to build user old';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?? self::MESSAGE;

        parent::__construct($message, $code, $previous);
    }
}
