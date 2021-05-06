<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function universities()
    {
        $universities = [];
        foreach (SharedAccess::where('user_id', '=', $this->id)->get() as $shared) {
            $universities[] = University::byID($shared->university_id);
        }

        foreach (University::where('creator_id', '=', $this->id)->get() as $university) {
            $universities[] = $university;
        }

        return $universities;
    }

    public function createdUniversities()
    {
        return University::where('creator_id', '=', $this->id)->get();
    }

    public static function byID($id)
    {
        return User::where('id', '=', $id)->first();
    }

    public static function byEmail($email)
    {
        return User::where('email', '=', $email)->first();
    }
}
