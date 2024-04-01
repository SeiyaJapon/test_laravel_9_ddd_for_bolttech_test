<?php

declare(strict_types=1);

namespace DDD\Domain\Exceptions\User;

use DDD\Domain\Exceptions\Common\DomainException;

final class EmailAlreadyInUseException extends DomainException {
    public const MESSAGE_WITH_EMAIL = 'The email %s is alredy in use';
    public const MESSAGE_WITHOUT_EMAIL = 'The email already in use';

    public function __construct(string $email = '') {
        $message = self::MESSAGE_WITHOUT_EMAIL;

        if (! empty($email)) {
            $message = sprintf(self::MESSAGE_WITH_EMAIL, $email);
        }

        parent::__construct($message);
    }
}
