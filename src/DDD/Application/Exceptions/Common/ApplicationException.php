<?php

declare(strict_types=1);

namespace DDD\Application\Exceptions\Common;

use Exception;

class ApplicationException extends Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
