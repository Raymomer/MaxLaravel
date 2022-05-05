<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

use App\Exceptions\Handler;
use App\User;
use Symfony\Component\HttpKernel\Profiler\Profile;

class EnsureTokenIsValid
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

        try {
            $user = new User();
            $user->where('token', $request->input('token'))->FirstOrfail();
        } catch (\Exception $e) {

            return  report($e);
        }
        return $next($request);
    }
}
