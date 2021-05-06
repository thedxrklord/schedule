<?php

namespace App\Http\Middleware;

use App\Models\University;
use Closure;
use Illuminate\Http\Request;

class UniversityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $university = University::byID($request->universityID);
        if (!$university) {
            return response()->json(['error' => 'Указанного университета не существует']);
        }

        if ($request->isMethod('POST')) {
            if (!$university->belongsToCurrentUser()) {
                return response()->json(['error' => 'Вы не имеете доступа к данному университету']);
            }
        }

        if ($request->isMethod('GET')) {
            if (auth()->user() && ($university->belongsToCurrentUser() || $university->currentUserHasAccess()))
                return $next($request);
            if (!$university->public) {
                return response()->json(['error' => 'Университет скрыт']);
            }
        }

        return $next($request);
    }
}
