<?php

declare(strict_types=1);

namespace DDD\Domain;

/**
 * RegisterType is used to referer constants values about kind of register.
 */
class RegisterType
{
    /** @var string REGISTER_COMPLETE */
    public const REGISTER_COMPLETE = 'register_complete';

    /** @var string ADD_PROJECT */
    public const ADD_PROJECT = 'add_project';

    /** @var string NO_REGISTER */
    public const NO_REGISTER = 'no_register';

    /** @var string ADMIN_ROLE */
    public const ADMIN_ROLE = 'Super Admin';

    /** @var string CLIENT_ROLE */
    public const CLIENT_ROLE = 'Client';

    /** @var string USER_ROLE */
    public const USER_ROLE = 'User';
}
