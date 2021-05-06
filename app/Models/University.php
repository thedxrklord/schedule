<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class University extends Model
{
    use HasFactory;

    public static function createImage($upload)
    {
        $filename = uniqid() . '.' . $upload->getClientOriginalExtension();
        $upload->move(public_path('images'), $filename);

        return asset('images/' . $filename);
    }

    public function creator()
    {
        return User::where('id', '=', $this->creator_id)->first();
    }

    public function creatorInstance()
    {
        return User::where('id', '=', $this->creator_id);
    }

    public function faculties()
    {
        return Faculty::where('university_id', '=', $this->id)->get();
    }

    public function facultiesInstance()
    {
        return Faculty::where('university_id', '=', $this->id);
    }

    public function belongsToCurrentUser()
    {
        if (!auth()->user())
            return false;
        return $this->creator_id == auth()->user()->id;
    }

    public function currentUserHasAccess()
    {
        if (!auth()->user())
            return false;
        $shared = SharedAccess::where('university_id', '=', $this->id)->where('user_id', '=', auth()->user()->id)->first();
        if (!$shared)
            return false;
        return true;
    }

    public static function byID($id)
    {
        return University::where('id', $id)->first();
    }

    public function departments()
    {
        $faculties = $this->faculties();
        $departments = [];
        foreach ($faculties as $faculty)
            foreach ($faculty->departments() as $department)
                $departments[] = $department;

        return $departments;
    }

    public function types()
    {
        return Type::where('university_id', '=', $this->id)->get();
    }

    public function classrooms()
    {
        return Classroom::where('university_id', '=', $this->id)->get();
    }

    public function subjects()
    {
        return Subject::where('university_id', '=', $this->id)->get();
    }

    public function sharedUsers()
    {
        $shared = SharedAccess::where('university_id', '=', $this->id)->get();
        $users = [];
        foreach ($shared as $share) {
            $users[] = User::where('id', '=', $share->user_id)->first();
        }

        return $users;
    }

    public function sharedAccessInstance()
    {
        return SharedAccess::where('university_id', '=', $this->id)->get();
    }

    public function teachers()
    {
        $departments = $this->departments();
        $teachers = [];

        foreach ($departments as $department)
            foreach ($department->teachers() as $teacher)
                $teachers[] = $teacher;

        return $teachers;
    }

    public function groups()
    {
        $departments = $this->departments();
        $groups = [];

        foreach ($departments as $department)
            foreach ($department->groups() as $group)
                $groups[] = $group;

        return $groups;
    }

    public function classtimes()
    {
        return Classtime::where('university_id', '=', $this->id)->get();
    }
}
