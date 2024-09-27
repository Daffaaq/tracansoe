<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'content',
        'image_url',
        'description',
        'status_publish',
        'date_publish',
        'time_publish',
        'user_id',
        'category_blog_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
        // Automatically generate slug from the category name
        static::saving(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the blog belongs to.
     */
    public function category()
    {
        return $this->belongsTo(CategoryBlog::class, 'category_blog_id');
    }

    /**
     * Scope to filter published blogs.
     */
    public function scopePublished($query)
    {
        return $query->where('status_publish', 'published');
    }
}
