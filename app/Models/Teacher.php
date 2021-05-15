<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function byID($id)
    {
        return Teacher::where('id', '=', $id)->first();
    }

    public function department()
    {
        return Department::byID($this->department_id);
    }

    public function usedInLessons()
    {
        return Lesson::where('teacher_id', '=', $this->id)->get();
    }

    public function university()
    {
        return $this->department()->faculty()->university();
    }

    public function lessons($dateFrom, $dateTo)
    {
        return Lesson::where('teacher_id', '=', $this->id)->where('date', '>=', $dateFrom)->where('date', '<=', $dateTo)->get();
    }
}
