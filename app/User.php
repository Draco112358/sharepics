<?php

namespace App;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Actuallymab\LaravelComment\CanComment;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use CanComment;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];



    public function albums(){
        return $this->hasMany(Album::class, 'user_id', 'id');
    }

    public function photos(){
        return $this->hasManyThrough(Photo::class, Album::class);
    }

    public function getFullNameAttribute(){
        return $this->name;
    }

    public function isAdmin(){
        return $this->role === 'admin';
    }
}
