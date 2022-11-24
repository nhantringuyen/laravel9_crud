<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {

        return $this->belongsToMany(User::class,'user_follow','follow_id','user_id');
    }
}
