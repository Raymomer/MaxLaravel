<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\User;
use App\Exceptions\CommonException;



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

            throw new CommonException(400, $e->getMessage());
        }
        return $next($request);
    }
}
