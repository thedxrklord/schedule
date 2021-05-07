<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public $university;
    private $department;

    public function __construct()
    {
        $this->middleware('department');
        $this->department = Department::byID(request()->departmentID);
        if ($this->department)
            $this->university = $this->department->university();
    }

    public function create(Request $request, $departmentID)
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
        $existsShort = Teacher::where('short_name', '=', $shortName)->where('department_id', '=', $departmentID)->first();
        $existsFull = Teacher::where('full_name', '=', $fullName)->where('department_id', '=', $departmentID)->first();

        if ($existsFull || $existsShort)
            return response()->json(['error' => 'Преподаватель с таким именем уже существует на данной кафедре']);

        // Create faculty
        $teacher = new Teacher();
        $teacher->short_name = $shortName;
        $teacher->full_name = $fullName;
        $teacher->department_id = $departmentID;
        $teacher->save();

        return response()->json(['success' => 'Преподаватель успешно создан', 'teacherID' => $teacher->id]);
    }

    public function edit($departmentID, $teacherID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $teacher = Teacher::byID($teacherID);

        if (!$teacher)
            return response()->json(['error' => 'Преподавателя с указанным id не существует']);

       if (isset(request()->newDepartmentID)) {
           $department = Department::byID(request()->newDepartmentID);
           if (!$department)
               return response()->json(['error' => 'Указанной кафедры не существует']);
           if (!$department->university()->belongsToCurrentUser())
               return response()->json(['error' => 'Вы не имеете доступа к университету указанной кафедры']);
       }

       $teacher->short_name = request()->shortName ?? $teacher->short_name;
       $teacher->full_name = request()->fullName ?? $teacher->full_name;
       if (isset($department))
           $teacher->department_id = $department->id;

       $teacher->save();

       return response()->json(['success' => 'Преподаватель успешно сохранён']);
    }

    public function remove($departmentID, $teacherID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $teacher = Teacher::byID($teacherID);

        if (!$teacher)
            return response()->json(['error' => 'Преподавателя с указанным id не существует']);

        $lessons = $teacher->usedInLessons();
        foreach ($lessons as $lesson)
            $lesson->delete();

        $teacher->delete();

        return response()->json(['success' => 'Преподаватель успешно удален']);
    }

    public function teachers()
    {
        return $this->department->teachers();
    }
}
