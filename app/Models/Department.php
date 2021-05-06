<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function byID($id)
    {
        return Department::where('id', '=', $id)->first();
    }

    public function teachers()
    {
        return Teacher::where('department_id', '=', $this->id)->get();
    }

    public function faculty()
    {
        return Faculty::byID($this->faculty_id);
    }

    public function groups()
    {
        return Group::where('department_id', '=', $this->id)->get();
    }

    public function university()
    {
        return Faculty::byID($this->faculty_id)->university();
    }
}
