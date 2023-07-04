<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_author',
        'post_title',
        'post_excerpt',
        'post_content',
        'post_status'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        static::created(function ($post) {
            $post->post_name = $post->createSlug($post->post_title);
            $post->save();
        });
    }
    public function user(){
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest user'
        ]);
    }
    public function featured_image(): MorphOne
    {
        return $this->morphOne(Image::class, 'resource')->latest();
    }
    private function createSlug($title){
        if (static::wherePost_name($post_name = Str::slug($title))->exists()) {
            $max = static::wherePost_title($title)->latest('id')->skip(1)->value('post_name');

            if (is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function ($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }

            return "{$post_name}-2";
        }

        return $post_name;
    }
}
