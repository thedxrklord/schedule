<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function byID($id)
    {
        return Group::where('id', '=', $id)->first();
    }

    public function department()
    {
        return Department::byID($this->department_id);
    }

    public function university()
    {
        return $this->department()->faculty()->university();
    }

    public function lessons($dateFrom, $dateTo)
    {
        return Lesson::where('group_id', '=', $this->id)->where('date', '>=', $dateFrom)->where('date', '<=', $dateTo)->get();
    }
}
