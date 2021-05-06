<?php

namespace App\Http\Middleware;

use App\Models\Faculty;
use App\Models\University;
use Closure;
use Illuminate\Http\Request;

class FacultyMiddleware
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
        $faculty = Faculty::byID($request->facultyID);

        if (!$faculty) {
            return response()->json(['error' => 'Указанного факультета не существует']);
        }

        $university = $faculty->university();
        if (!$university) {
            return response()->json(['error' => 'У факультета не указан университет']);
        }

        if ($request->isMethod('POST')) {
            if (!$university->belongsToCurrentUser()) {
                return response()->json(['error' => 'Вы не имеете доступа к данному университету']);
            }
        }

        if ($request->isMethod('GET')) {
            if (auth()->user() && $university->belongsToCurrentUser() || $university->currentUserHasAccess())
                return $next($request);
            if (!$university->public) {
                return response()->json(['error' => 'Университет скрыт']);
            }
        }


        return $next($request);
    }
}
