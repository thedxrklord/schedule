<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\University;
use Illuminate\Http\Request;

class ClassroomController extends Controller
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
        $name = $request->name;

        // Validate request params
        if (!$name)
            return response()->json(['error' => 'Укажите `name`']);

        // Check if it doesnt exists
        $exists = Classroom::where('name', '=', $name)->where('university_id', '=', $universityID)->first();

        if ($exists)
            return response()->json(['error' => 'Аудитория с указанным именем уже существует']);

        // Create faculty
        $classroom = new Classroom();
        $classroom->name = $name;
        $classroom->university_id = $universityID;
        $classroom->save();

        return response()->json(['success' => 'Аудитория успешно создана', 'classroomID' => $classroom->id]);
    }

    public function edit($universityID, $classroomID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $classroom = Classroom::byID($classroomID);

        if (!$classroom)
            return response()->json(['error' => 'Аудитории с указанным id не существует']);

        $classroom->name = request()->name ?? $classroom->name;

        $classroom->save();

        return response()->json(['success' => 'Аудитория успешно сохранена']);
    }

    public function remove($universityID, $classroomID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $classroom = Classroom::byID($classroomID);

        if (!$classroom)
            return response()->json(['error' => 'Аудитории с указанным id не существует']);

        $lessons = $classroom->usedInLessons();
        foreach ($lessons as $lesson)
            $lesson->delete();

        $classroom->delete();

        return response()->json(['success' => 'Аудитория успешно удалена']);
    }

    public function classrooms(Request $request, $universityID)
    {
        return $this->university->classrooms();
    }
}
