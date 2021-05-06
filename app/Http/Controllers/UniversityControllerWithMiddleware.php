<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityControllerWithMiddleware extends Controller
{
    private $university;

    public function __construct()
    {
        $this->middleware('university')->except('remove');
        $this->university = University::byID(request()->universityID);
    }

    public function universityDepartments()
    {
        return $this->university->departments();
    }

    public function universityTeachers()
    {
        return $this->university->teachers();
    }

    public function universityGroups()
    {
        return $this->university->groups();
    }

    public function edit($universityID)
    {
        request()->universityID = $universityID;
        $university = University::byID($universityID);

        if (!$university)
            return response()->json(['error' => 'Не найден университет с указанным ID']);
        if (!$university->belongsToCurrentUser())
            return response()->json(['error' => 'У вас нет доступа к университету']);

        $university->short_name = request()->shortName ?? $university->short_name;
        $university->full_name = request()->fullName ?? $university->full_name;
        $university->description = request()->description ?? $university->description;
        $university->public = request()->public ?? $university->public;

        $university->save();

        return response()->json(['error' => 'Университет успешно сохранён']);
    }

    public function remove($universityID)
    {
        request()->universityID = $universityID;
        $university = University::byID($universityID);

        if (!$university)
            return response()->json(['error' => 'Не найден университет с указанным ID']);
        if (!$university->belongsToCurrentUser())
            return response()->json(['error' => 'У вас нет доступа к университету']);

        $faculties = $university->faculties();
        $classrooms = $university->classrooms();
        $classtimes = $university->classtimes();
        $subjects = $university->subjects();
        $types = $university->types();

        $facultyController = new FacultyController();
        $classroomController = new ClassroomController();
        $classtimesController = new ClasstimeController();
        $subjectsController = new SubjectController();
        $typesController = new TypeController();

        foreach ($faculties as $faculty)
            $facultyController->remove($universityID, $faculty->id);
        foreach ($classtimes as $classtime)
            $classtimesController->remove($universityID, $classtime->id);
        foreach ($classrooms as $classroom)
            $classroomController->remove($universityID, $classroom->id);
        foreach ($subjects as $subject)
            $subjectsController->remove($universityID, $subject->id);
        foreach ($types as $type)
            $typesController->remove($universityID, $type->id);
        foreach ($university->sharedAccessInstance() as $shared)
            $shared->delete();

        $university->delete();

        return response()->json(['success' => 'Университет успешно удалён']);
    }
}
