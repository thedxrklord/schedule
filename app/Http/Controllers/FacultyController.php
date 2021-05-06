<?php

namespace App\Http\Controllers;

use App\Http\Middleware\UniversityMiddleware;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\University;
use Illuminate\Http\Request;

class FacultyController extends Controller
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
        $existsShort = Faculty::where('short_name', '=', $shortName)->where('university_id', '=', $universityID)->first();
        $existsFull = Faculty::where('full_name', '=', $fullName)->where('university_id', '=', $universityID)->first();

        if ($existsFull || $existsShort)
            return response()->json(['error' => 'Факультет с указанным именем или коротким именем уже существует']);

        // Create faculty
        $faculty = new Faculty();
        $faculty->short_name = $shortName;
        $faculty->full_name = $fullName;
        $faculty->university_id = $universityID;
        $faculty->save();

        return response()->json(['success' => 'Факультет успешно создан', 'facultyID' => $faculty->id]);
    }

    public function edit($universityID, $facultyID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);

        $faculty = Faculty::byID($facultyID);
        if (!$faculty)
            return response()->json(['error' => 'Указанного факультета не существует']);

        $faculty->short_name = request()->shortName ?? $faculty->short_name;
        $faculty->full_name = request()->fullname ?? $faculty->full_name;

        $faculty->save();

        return response()->json(['success' => 'Факультет успешно сохранён']);
    }

    public function remove($universityID, $facultyID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);

        $faculty = Faculty::byID($facultyID);
        if (!$faculty)
            return response()->json(['error' => 'Указанного факультета не существует']);
        $departments = $faculty->departments();
        $departmentController = new DepartmentController();

        foreach ($departments as $department)
            $departmentController->remove($facultyID, $department->id);

        $faculty->delete();

        return response()->json(['success' => 'Факультет успешно удалён']);
    }

    public function faculties()
    {
        return $this->university->faculties();
    }
}
