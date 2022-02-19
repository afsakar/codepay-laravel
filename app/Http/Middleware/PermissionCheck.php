<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class PermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $route
     * @param $action
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $route, $action)
    {

        $user = auth()->user();

        if ($user->role_id === 1) {
            return $next($request);
        } else {
            $perms = $user->role()->first()->permissions;
            $permissions = json_decode($perms, true);

            if (isset($permissions[$route][$action]) && $permissions[$route][$action] == true) {
                return $next($request);
            } else {
                return abort(403);
            }
        }

    }
}
