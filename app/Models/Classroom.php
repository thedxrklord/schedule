<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function byID($id)
    {
        return Classroom::where('id', '=', $id)->first();
    }

    public function usedInLessons()
    {
        return Lesson::where('classroom_id', '=', $this->id)->get();
    }

    public function university()
    {
        return University::byID($this->university_id);
    }

    public function lessons()
    {
        return Lesson::where('classroom_id', '=', $this->id)->get();
    }
}
