<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class BasicAuth
{
    public function handle($request, $next)
    {
        return Auth::onceBasic() ?: $next($request);
    }

}
