<?php

namespace App\Http\Middleware;

use App\Role;
use App\User;
use Auth;
use Closure;
use Illuminate\Support\Facades\Gate;

/**
 * Class AuthGates
 * @package App\Http\Middleware
 */
class AuthGates
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!app()->runningInConsole() && $user) {
            $roles            = Role::with('permissions')->get();
            $permissionsArray = [];

            foreach ($roles as $role) {
                foreach ($role->permissions as $permissions) {
                    $permissionsArray[$permissions->title][] = $role->id;
                }
            }

            foreach ($permissionsArray as $title => $roles) {
                Gate::define($title, function (User $user) use ($roles) {
                    return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                });
            }
        }

        return $next($request);
    }
}
