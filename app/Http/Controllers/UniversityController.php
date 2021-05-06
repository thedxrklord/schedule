<?php

namespace App\Http\Controllers;

use App\Models\SharedAccess;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function createUniversity(Request $request)
    {
        if (sizeof(auth()->user()->createdUniversities()) >= 3)
            return redirect()->back()->with(['error' => 'Запрещено создавать более 3х университетов на одного пользователя']);
        if ($request->isMethod('get')) {
            return view('university.create');
        } else {
            $universityExists = University::where('short_name', '=', $request->university_short_name)->first();
            if ($universityExists)
                return redirect()->back()->with(['error' => 'Университет с таким названием уже существует']);

            $university = new University();
            $university->short_name = $request->university_short_name;
            $university->full_name = $request->university_full_name;
            $university->description = $request->university_description;
            $image = University::createImage($request->university_image);
            $university->image = $image;
            $university->public = isset($request->university_public) && $request->university_public == 'on';
            $university->creator_id = auth()->user()->id;
            $university->save();

            return redirect('home');
        }
    }

    public function university($universityID)
    {
        $university = University::byID($universityID);

        if (!$university)
            return response()->json(['error' => 'Университета с указанным ID не существует']);
        if ($university->public)
            return $university;
  
        if (!auth()->user() || !$university->belongsToCurrentUser()) {
            if (!$university->public)
                return response()->json(['error' => 'Университет скрыт']);
        }

        return $university;
    }

    public function shared($universityID)
    {
        $university = University::byID($universityID);
        if (!$university || !$university->belongsToCurrentUser())
            abort(404);
        if (request()->isMethod('GET'))
            return view('university.shared', compact('university'));
        else {
            $user = User::where('email', '=', request()->userEmail)->first();
            if (!$user)
                return redirect()->back()->with(['error' => 'User with this email does not exists']);
            $access = new SharedAccess();
            $access->user_id = $user->id;
            $access->university_id = $university->id;
            $access->save();

            return redirect()->back();
        }
    }

    public function removeShared($universityID, $sharedEmail)
    {
        $university = University::byID($universityID);
        if (!$university)
            return redirect()->back()->with(['error' => 'Не найден университет с указанным id']);
        if (!$university->belongsToCurrentUser())
            return redirect()->back()->with(['error' => 'Вы не имееете доступа к данному университету']);
        $user = User::byEmail($sharedEmail);
        if (!$user)
            return redirect()->back()->with(['error' => 'Указанного пользователя не существует']);
        $shared = SharedAccess::where('university_id', '=', $universityID)->where('user_id', '=', $user->id)->first();
        if (!$shared)
            return redirect()->back()->with(['error' => 'У данного пользователя нет доступа к вашему университету']);

        $shared->delete();

        return redirect()->back()->with(['success' => 'Пользователь больше не имеет доступа к вашему университету']);
    }

    public function publicUniversities()
    {
        return University::where('public', '=', true)->get();
    }

    public function userUniversities()
    {
        return auth()->user()->universities();
    }

    public function universities()
    {
        $universities = auth()->user()->universities();

        return view('university.list', compact('universities'));
    }
}
