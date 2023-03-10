<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'slug',
        'details',
        'image',
        'status',
        'view',
        'blog_category_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(BlogTag::class);
    }

    public function blogComments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getImagePathAttribute()
    {
        if ($this->image)
        {
            return $this->image;
        } else {
            return 'uploads/default/blog.png';
        }
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
            $model->user_id =  auth()->id();
            $model->status =  auth()->user()->is_admin() ? 1 : 0;
        });
    }

}
