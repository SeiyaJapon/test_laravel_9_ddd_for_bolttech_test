<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use DDD\Domain\Exceptions\User\UserHasNoPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     * @throws UserHasNoPermissions
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->isAdmin()) {
            return $next($request);
        } elseif ($request->hasAny(['id', 'email'])) {
            $user = $this->getUser($request);

            if (Auth::id() === optional($user)->id) {
                return $next($request);
            }
        }

        throw new UserHasNoPermissions();
    }

    private function getUser(Request $request)
    {
        if ($request->has('id')) {
            return User::find($request->id);
        } elseif ($request->has(['email', 'pro_id'])) {
            return User::where('email', $request->email)
                        ->whereHas('project', function ($query) use ($request) {
                            $query->where('pro_id', $request->pro_id);
                        })
                        ->first();
        }
    }
}
