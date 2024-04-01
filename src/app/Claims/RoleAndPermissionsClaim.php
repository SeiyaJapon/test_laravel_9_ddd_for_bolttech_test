<?php

namespace App\Claims;

use CorBosman\Passport\AccessToken;
use Illuminate\Support\Facades\Auth;

/**
 * RoleAndPermissionsClaim class let add role and permissions claims to JWT tokens.
 */
class RoleAndPermissionsClaim
{
    public function handle(AccessToken $token, $next)
    {
        $token->addClaim('role', Auth::user()->roles->first()->name);
        $token->addClaim('permissions', Auth::user()->permissions);

        return $next($token);
    }
}
