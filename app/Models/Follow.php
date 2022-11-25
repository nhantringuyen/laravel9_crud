<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Follow extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_follow','follow_id','user_id');
    }
}
