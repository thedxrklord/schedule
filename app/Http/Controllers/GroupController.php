<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private $university;
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
        $existsShort = Group::where('short_name', '=', $shortName)->where('department_id', '=', $departmentID)->first();
        $existsFull = Group::where('full_name', '=', $fullName)->where('department_id', '=', $departmentID)->first();

        if ($existsFull || $existsShort)
            return response()->json(['error' => 'Группа с таким именем уже существует на данной кафедре']);

        // Create faculty
        $group = new Group();
        $group->short_name = $shortName;
        $group->full_name = $fullName;
        $group->department_id = $departmentID;
        $group->save();

        return response()->json(['success' => 'Группа успешно создана', 'groupID' => $group->id]);
    }

    public function edit($departmentID, $groupID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);

        $group = Group::byID($groupID);
        if (!$group)
            return response()->json(['error' => 'Группы с указанным ID не существует']);

        if (isset(request()->newDepartmentID)) {
            $department = Department::byID(request()->newDepartmentID);
            if (!$department)
                return response()->json(['error' => 'Указанной кафедры не существует']);
            if (!$department->university()->belongsToCurrentUser())
                return response()->json(['error' => 'Вы не имеете доступа к университету указанной кафедры']);
        }

        $group->short_name = request()->shortName ?? $group->short_name;
        $group->full_name = request()->fullName ?? $group->full_name;

        if (isset($department))
            $group->department_id = $department->id;

        $group->save();

        return response()->json(['success' => 'Группа успешно сохранена']);
    }

    public function remove($departmentID, $groupID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);

        $group = Group::byID($groupID);
        if (!$group)
            return response()->json(['error' => 'Группы с указанным ID не существует']);
        // Delete group lessons
        $lessons = $group->lessons();
        foreach ($lessons as $lesson)
            $lesson->delete();

        $group->delete();

        return response()->json(['success' => 'Группа успешно удалена']);
    }

    public function groups()
    {
        return $this->department->groups();
    }
}
