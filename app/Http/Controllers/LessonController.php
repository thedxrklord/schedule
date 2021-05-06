<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classtime;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private function isValidDate(string $date, string $format = 'd.m.Y'): bool
    {
        $dateObj = \DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) == $date;
    }

    public function create(Request $request)
    {
        $input = [];
        $input['subjectID'] = $request->subjectID;
        $input['classroomID'] = $request->classroomID;
        $input['classtimeID'] = $request->classtimeID;
        $input['groupID'] = $request->groupID;
        $input['teacherID'] = $request->teacherID;
        $input['typeID'] = $request->typeID;
        $input['date'] = $request->date;

        foreach ($input as $key => $value) {
            if (!isset($value))
                return response()->json(['error' => "Укажите `$key`"]);
            switch ($key) {
                case 'subjectID':
                    $subject = Subject::byID($value);
                    if (!$subject)
                        return response()->json(['error' => "Несуществующий предмет"]);
                    if (!$subject->university())
                        return response()->json(['error' => "У предмета не указан университет"]);
                    if (!$subject->university()->belongsToCurrentUser() && !$subject->university()->currentUserHasAccess())
                        return response()->json(['error' => "У вас нет доступа к данному университету"]);
                    break;
                case 'classroomID':
                    $classroom = Classroom::byID($value);
                    if (!$classroom)
                        return response()->json(['error' => "Несуществующая аудитория"]);
                    if (!$classroom->university())
                        return response()->json(['error' => "У аудитории не указан университет"]);
                    if (!$classroom->university()->belongsToCurrentUser() && !$subject->university()->currentUserHasAccess())
                        return response()->json(['error' => "У вас нет доступа к данному университету"]);
                    break;
                case 'classtimeID':
                    $classtime = Classtime::byID($value);
                    if (!$classtime)
                        return response()->json(['error' => "Несуществующее время"]);
                    if (!$classtime->university())
                        return response()->json(['error' => "У времени не указан университет"]);
                    if (!$classtime->university()->belongsToCurrentUser() && !$subject->university()->currentUserHasAccess())
                        return response()->json(['error' => "У вас нет доступа к данному университету"]);
                    break;
                case 'groupID':
                    $group = Group::byID($value);
                    if (!$group)
                        return response()->json(['error' => "Несуществующая группа"]);
                    if (!$group->university())
                        return response()->json(['error' => "У группы не указан университет"]);
                    if (!$group->university()->belongsToCurrentUser() && !$subject->university()->currentUserHasAccess())
                        return response()->json(['error' => "У вас нет доступа к данному университету"]);
                    break;
                case 'teacherID':
                    $teacher = Teacher::byID($value);
                    if (!$teacher)
                        return response()->json(['error' => "Несуществующий преподаватель"]);
                    if (!$teacher->university())
                        return response()->json(['error' => "У преподавателя не указан университет"]);
                    if (!$teacher->university()->belongsToCurrentUser() && !$subject->university()->currentUserHasAccess())
                        return response()->json(['error' => "У вас нет доступа к данному университету"]);
                    break;
                case 'typeID':
                    $type = Type::byID($value);
                    if (!$type)
                        return response()->json(['error' => "Несуществующий тип"]);
                    if (!$type->university())
                        return response()->json(['error' => "У типа не указан университет"]);
                    if (!$type->university()->belongsToCurrentUser() && !$subject->university()->currentUserHasAccess())
                        return response()->json(['error' => "У вас нет доступа к данному университету"]);
                    break;
                case 'date':
                    $date = $value;
                    if (!$this->isValidDate($date))
                        return response()->json(['error' => "Формат даты: d.m.Y"]);
            }
        }

        if (Lesson::where('group_id', '=', $group->id)->where('classtime_id', '=', $classtime->id)->where('date', '=', $date)->first())
            return response()->json(['error' => "У группы уже есть пара на это время"]);

        $lesson = new Lesson();
        $lesson->subject_id = $subject->id;
        $lesson->classroom_id = $classroom->id;
        $lesson->classtime_id = $classtime->id;
        $lesson->group_id = $group->id;
        $lesson->teacher_id = $teacher->id;
        $lesson->type_id = $type->id;
        $lesson->date = Carbon::parse($date)->format('Y-m-d');
        $lesson->save();

        return response()->json(['success' => "Пара успешно создана", 'lessonID' => $lesson->id]);
    }

    public function groupLessons($groupID)
    {
        $group = Group::byID($groupID);
        if (!$group)
            return response()->json(['error' => "Несуществующая группа"]);
        if (!$group->university()->belongsToCurrentUser()) {
            if (!$group->university()->public)
                return response()->json(['error' => "У вас нет доступа к данному университету"]);
        }

        return $group->lessons();
    }

    public function groupLessonsNormalShort($groupID)
    {
        $lessons = $this->groupLessons($groupID);
        $normalLessons = [];
        foreach ($lessons as $lesson) {
            $tempLesson = [];
            $tempLesson['id'] = $lesson->id;
            $tempLesson['subject'] = Subject::byID($lesson->subject_id)->short_name;
            $tempLesson['classroom'] = Classroom::byID($lesson->classroom_id)->name;
            $tempLesson['classtime']['start'] = Classtime::byID($lesson->classtime_id)->start;
            $tempLesson['classtime']['end'] = Classtime::byID($lesson->classtime_id)->end;
            $tempLesson['group'] = Group::byID($lesson->group_id)->short_name;
            $tempLesson['teacher'] = Teacher::byID($lesson->teacher_id)->short_name;
            $tempLesson['type'] = Type::byID($lesson->type_id)->short_name;
            $tempLesson['date'] = $lesson->date();
            $normalLessons[] = $tempLesson;
        }

        return $normalLessons;
    }

    public function groupLessonsNormalFull($groupID)
    {
        $lessons = $this->groupLessons($groupID);
        $normalLessons = [];
        foreach ($lessons as $lesson) {
            $tempLesson = [];
            $tempLesson['id'] = $lesson->id;
            $tempLesson['subject'] = Subject::byID($lesson->subject_id)->full_name;
            $tempLesson['classroom'] = Classroom::byID($lesson->classroom_id)->name;
            $tempLesson['classtime']['start'] = Classtime::byID($lesson->classtime_id)->start;
            $tempLesson['classtime']['end'] = Classtime::byID($lesson->classtime_id)->end;
            $tempLesson['group'] = Group::byID($lesson->group_id)->full_name;
            $tempLesson['teacher'] = Teacher::byID($lesson->teacher_id)->full_name;
            $tempLesson['type'] = Type::byID($lesson->type_id)->full_name;
            $tempLesson['date'] = $lesson->date();
            $normalLessons[] = $tempLesson;
        }

        return $normalLessons;
    }

    public function remove($lessonID)
    {
        $lesson = Lesson::byID($lessonID);
        if (!$lesson)
            return response()->json(['error' => "Указанной пары не существует"]);
        if (!$lesson->university()->belongsToCurrentUser() && !$lesson->university()->currentUserHasAccess())
            return response()->json(['error' => "У вас нет доступа к данному университету"]);
        $lesson->delete();

        return response()->json(['success' => "Пара успешно удалена"]);
    }
}
