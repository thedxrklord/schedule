<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'date' => 'date:d.m.Y',
    ];

    public function date()
    {
        return Carbon::parse($this->date)->format('d.m.Y');
    }

    public static function byID($id)
    {
        return Lesson::where('id', '=', $id)->first();
    }

    public function group()
    {
        return Group::where('id', '=', $this->group_id)->first();
    }

    public function university()
    {
        return $this->group()->department()->faculty()->university();
    }
}
