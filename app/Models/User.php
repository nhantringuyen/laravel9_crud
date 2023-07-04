<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\MorphOne;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
//    protected $appends = ['is_admin'];

    protected $guarded = [
        'utype'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the administrator flag for the user.
     *
     * @return  bool
     */
//    public function getIsAdminAttribute()
//    {
////        dd($this->attributes);
//        return $this->attributes['utype'] == 'ADM';
//    }

    public function image() : MorphOne {
        return $this->morphOne(Image::class, 'resource');
    }
    public function posts(){
        return $this->hasMany(Post::class);
    }
    public function follows(){
        return $this->belongsToMany(Follow::class,'user_follow','user_id','follow_id')->withTimestamps();
    }
}
