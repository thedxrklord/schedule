<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\University;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private $university;

    public function __construct()
    {
        $this->middleware('university');
        $this->university = University::byID(request()->universityID);
    }

    public function create(Request $request, $universityID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        // Get request params
        $shortName = $request->shortName;
        $fullName = $request->fullName;

        // Validate request params
        if (!$shortName)
            return response()->json(['error' => 'Укажите `shortName`']);
        if (!$fullName)
            return response()->json(['error' => 'Укажите `fullName`']);

        // Check if it doesnt exists
        $existsShort = Subject::where('short_name', '=', $shortName)->where('university_id', '=', $universityID)->first();
        $existsFull = Subject::where('full_name', '=', $fullName)->where('university_id', '=', $universityID)->first();

        if ($existsFull || $existsShort)
            return response()->json(['error' => 'Предмет с указанным именем или коротким именем уже существует']);

        // Create faculty
        $subject = new Subject();
        $subject->short_name = $shortName;
        $subject->full_name = $fullName;
        $subject->university_id = $universityID;
        $subject->save();

        return response()->json(['success' => 'Предмет успешно создан', 'typeID' => $subject->id]);
    }

    public function edit($universityID, $subjectID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $subject = Subject::byID($subjectID);

        if (!$subject)
            return response()->json(['error' => 'Предмета с указанным id не существует']);

        $subject->short_name = request()->shortName ?? $subject->short_name;
        $subject->full_name = request()->fullname ?? $subject->full_name;

        $subject->save();

        return response()->json(['success' => 'Предмет успешно сохранён']);
    }

    public function remove($universityID, $subjectID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $subject = Subject::byID($subjectID);

        if (!$subject)
            return response()->json(['error' => 'Предмета с указанным id не существует']);

        $lessons = $subject->usedInLessons();
        foreach ($lessons as $lesson)
            $lesson->delete();

        $subject->delete();

        return response()->json(['success' => 'Предмет успешно удален']);
    }

    public function subjects(Request $request, $universityID)
    {
        return $this->university->subjects();
    }
}
