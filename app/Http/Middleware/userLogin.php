<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class userLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if(empty(Session::has('logSession'))){
        //     return redirect('/login_register')->with('flash_message_error', 'You need to login to access your account. If you have not an account, you can create a account bellow!');
        // }
        return $next($request);
    }
}
