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
            $profile = $user->where('token', $request->input('token'))->FirstOrfail();
            dd($profile);
        } catch (\Exception $e) {

            return  report($e);
        }





        // $profile = UserController::userProfileExist('token', $request->input('token'));

        // if (count($profile) == 0) {
        //     // return UserController::error(400, $message = "Token is not verify.");
        //     // Handler::rend
        //     return 0;
        // }

        return $next($request);
    }
}
