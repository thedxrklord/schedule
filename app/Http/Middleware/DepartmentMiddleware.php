<?php

namespace App\Http\Middleware;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\University;
use Closure;
use Illuminate\Http\Request;

class DepartmentMiddleware
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
        $department = Department::byID($request->departmentID);

        if (!$department) {
            return response()->json(['error' => 'Указанной кафедры не существует']);
        }

        $university = $department->university();
        if (!$university) {
            return response()->json(['error' => 'У кафедры не указан университет']);
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
