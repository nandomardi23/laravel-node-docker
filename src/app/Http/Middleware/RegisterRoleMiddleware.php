<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class RegisterRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $roles = Role::get();

            foreach ($roles as $ru) {
                Gate::define($ru->name, function() use ($ru) {
                    $getLoggedinAccountRole = RoleUser::join('roles', 'roles.id', '=', 'role_users.role_id')
                        ->where('role_users.user_id', auth()->user()->id)->first();

                    if ($ru->name == $getLoggedinAccountRole->name) {
                        return true;
                        // break;
                    } else {
                        // continue;
                        return false;
                    }
                });
            }
        return $next($request);
    }
}
