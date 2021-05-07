<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\University;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public $university;
    private $faculty;

    public function __construct()
    {
        $this->middleware('faculty');
        $this->faculty = Faculty::byID(request()->facultyID);
        if ($this->faculty)
            $this->university = $this->faculty->university();
    }

    public function create(Request $request, $facultyID)
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
        $exists = Department::where('short_name', '=', $shortName)->where('full_name', '=', $fullName)->where('faculty_id', '=', $facultyID)->first();

        if ($exists)
            return response()->json(['error' => 'Кафедра с указанным именем и коротким именем уже существует на данном факультете']);

        // Create faculty
        $department = new Department();
        $department->short_name = $shortName;
        $department->full_name = $fullName;
        $department->faculty_id = $facultyID;
        $department->save();

        return response()->json(['success' => 'Кафедра успешно создана', 'departmentID' => $department->id]);
    }

    public function edit($facultyID, $departmentID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $department = Department::byID($departmentID);
        if (!$department)
            return response()->json(['error' => 'Кафедры с указанным id не существует']);
        if (isset(request()->newFacultyID)) {
            $faculty = Faculty::byID(request()->newFacultyID);
            if (!$faculty)
                return response()->json(['error' => 'Указанного факультета не существует']);
            if (!$faculty->university->belongsToCurrentUser())
                return response()->json(['error' => 'Вы не имеете доступа к университету указанного факультета']);
        }

        $department->short_name = request()->shortName ?? $department->short_name;
        $department->full_name = request()->fullname ?? $department->full_name;
        if (isset($faculty))
            $department->faculty_id = request()->newFacultyID;

        $department->save();

        return response()->json(['success' => 'Кафедра успешно сохранена']);
    }

    public function remove($facultyID, $departmentID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $department = Department::byID($departmentID);
        if (!$department)
            return response()->json(['error' => 'Кафедры с указанным id не существует']);

        $teachers = $department->teachers();
        $groups = $department->groups();
        $teacherController = new TeacherController();
        $teacherController->university = $this->university;
        $groupController = new GroupController();
        $groupController->university = $this->university;


        foreach ($teachers as $teacher)
            $teacherController->remove($departmentID, $teacher->id);
        foreach ($groups as $group)
            $groupController->remove($departmentID, $group->id);

        $department->delete();

        return response()->json(['success' => 'Кафедра успешно удалена']);
    }

    public function departments()
    {
        return $this->faculty->departments();
    }
}
