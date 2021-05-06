<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\University;
use Illuminate\Http\Request;

class TypeController extends Controller
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
        $existsShort = Type::where('short_name', '=', $shortName)->where('university_id', '=', $universityID)->first();
        $existsFull = Type::where('full_name', '=', $fullName)->where('university_id', '=', $universityID)->first();

        if ($existsFull || $existsShort)
            return response()->json(['error' => 'Тип с указанным именем или коротким именем уже существует']);

        // Create faculty
        $type = new Type();
        $type->short_name = $shortName;
        $type->full_name = $fullName;
        $type->university_id = $universityID;
        $type->save();

        return response()->json(['success' => 'Тип успешно создан', 'typeID' => $type->id]);
    }

    public function edit($universityID, $typeID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $type = Type::byID($typeID);

        if (!$type)
            return response()->json(['error' => 'Типа с указанным id не существует']);

        $type->short_name = request()->shortName ?? $type->short_name;
        $type->full_name = request()->fullName ?? $type->full_name;

        $type->save();

        return response()->json(['success' => 'Тип успешно сохранён']);
    }

    public function remove($universityID, $typeID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $type = Type::byID($typeID);

        if (!$type)
            return response()->json(['error' => 'Типа с указанным id не существует']);

        $lessons = $type->usedInLessons();
        foreach ($lessons as $lesson)
            $lesson->delete();

        $type->delete();

        return response()->json(['success' => 'Тип успешно удален']);
    }

    public function types(Request $request, $universityID)
    {
        return $this->university->types();
    }
}
