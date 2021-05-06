<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static function byID($id)
    {
        return Faculty::where('id', $id)->first();
    }

    public function university()
    {
        return University::where('id', '=', $this->university_id)->first();
    }

    public function departments()
    {
        return Department::where('faculty_id', '=', $this->id)->get();
    }
}
