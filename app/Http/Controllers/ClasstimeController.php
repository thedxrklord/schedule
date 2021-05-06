<?php

namespace App\Http\Controllers;

use App\Models\Classtime;
use App\Models\University;
use Illuminate\Http\Request;

class ClasstimeController extends Controller
{
    private $university;

    public function __construct()
    {
        $this->middleware('university');
        $this->university = University::byID(request()->universityID);
    }

    private function isValidDate(string $date, string $format = 'H:i:s'): bool
    {
        $dateObj = \DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) == $date;
    }

    public function create(Request $request, $universityID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        // Get request params
        $timeStart = $request->timeStart;
        $timeEnd = $request->timeEnd;

        // Validate request params
        if (!$timeStart)
            return response()->json(['error' => 'Укажите `timeStart`']);
        if (!$timeEnd)
            return response()->json(['error' => 'Укажите `timeEnd`']);
        if (!$this->isValidDate($timeStart) || !$this->isValidDate($timeEnd))
            return response()->json(['error' => 'Время должно иметь формат H:i:s`']);
        if ($timeStart > $timeEnd)
            return response()->json(['error' => '`timeStart` должен быть < `timeEnd`']);
        // Check if it doesnt exists
        $exists = Classtime::where('start', '=', $timeStart)->where('end', '=', $timeEnd)->where('university_id', '=', $universityID)->first();
        if ($exists)
            return response()->json(['error' => 'Такое время уже существует для данного университета']);

        $classtime = new Classtime();
        $classtime->start = $timeStart;
        $classtime->end = $timeEnd;
        $classtime->university_id = $universityID;
        $classtime->save();

        return response()->json(['success' => 'Время успешно создано', 'classtimeID' => $classtime->id]);
    }

    public function edit($universityID, $classtimeID)
    {
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $classtime = Classtime::byID($classtimeID);

        if (!$classtime)
            return response()->json(['error' => 'Времени с указанным id не существует']);

        $timeStart = request()->timeStart;
        $timeEnd = request()->timeEnd;

        // Validate request params
        if (!$timeStart)
            return response()->json(['error' => 'Укажите `timeStart`']);
        if (!$timeEnd)
            return response()->json(['error' => 'Укажите `timeEnd`']);
        if (!$this->isValidDate($timeStart) || !$this->isValidDate($timeEnd))
            return response()->json(['error' => 'Время должно иметь формат H:i:s`']);
        if ($timeStart > $timeEnd)
            return response()->json(['error' => '`timeStart` должен быть < `timeEnd`']);
        // Check if it doesnt exists
        $exists = Classtime::where('start', '=', $timeStart)->where('end', '=', $timeEnd)->where('university_id', '=', $universityID)->first();
        if ($exists)
            return response()->json(['error' => 'Такое время уже существует для данного университета']);

        $classtime->start = $timeStart;
        $classtime->end = $timeEnd;
        $classtime->save();

        return response()->json(['success' => 'Время успешно сохранено']);
    }

    public function remove($universityID, $classtimeID)
    {
        // Shared Access can't create
        if ($this->university->currentUserHasAccess())
            return response()->json(['error' => 'Пользователи с "Shared Access" не могут изменять архитектуру университета']);
        $classtime = Classtime::byID($classtimeID);

        if (!$classtime)
            return response()->json(['error' => 'Времени с указанным id не существует']);

        $lessons = $classtime->usedInLessons();
        foreach ($lessons as $lesson)
            $lesson->delete();

        $classtime->delete();

        return response()->json(['success' => 'Время успешно удалено']);
    }

    public function classtimes(Request $request, $universityID)
    {
        return $this->university->classtimes();
    }
}
