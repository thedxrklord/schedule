<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function byID($id)
    {
        return Subject::where('id', '=', $id)->first();
    }

    public function usedInLessons()
    {
        return Lesson::where('subject_id', '=', $this->id)->get();
    }

    public function university()
    {
        return University::byID($this->university_id);
    }
}
