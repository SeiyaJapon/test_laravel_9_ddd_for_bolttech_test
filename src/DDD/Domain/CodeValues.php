<?php

declare(strict_types=1);

namespace DDD\Domain;

/**
 * CodeValues is used to referer constants values about response values.
 * Is used on DDD beacuse on Domain layer we need this values but, for the
 * commandment and requires of Domain Driver Design, it is not possible to
 * referer a class like Illuminate\Http\Response, because is on infrastructure
 * layer.
 */
class CodeValues
{
    public const SUCCESS_CODE = 200;
    public const CREATED_CODE = 201;
    public const ACCEPTED_CODE = 202;
    public const BAD_REQUEST = 400;
    public const NOT_FOUND = 404;
}
