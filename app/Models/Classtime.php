<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classtime extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function byID($id)
    {
        return Classtime::where('id', '=', $id)->first();
    }

    public function university()
    {
        return University::byID($this->university_id);
    }

    public function usedInLessons()
    {
        return Lesson::where('classtime_id', '=', $this->id)->get();
    }
}
