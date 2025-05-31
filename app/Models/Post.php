<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_code',
        'product_name',
        'store_name',
        'location',
        'price',
        'regular_price',
        'description',
        'image',
        'post_type',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'location' => 'array',
        'price' => 'float',
        'regular_price' => 'float',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the likes for the post.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Get the meal score for the post (if it's a meal post).
     */
    public function mealScore()
    {
        return $this->hasOne(MealScore::class);
    }

    /**
     * Calculate the savings amount.
     */
    public function getSavingsAttribute()
    {
        if ($this->regular_price && $this->price) {
            return $this->regular_price - $this->price;
        }
        
        return 0;
    }

    /**
     * Calculate the savings percentage.
     */
    public function getSavingsPercentageAttribute()
    {
        if ($this->regular_price && $this->price && $this->regular_price > 0) {
            return round(($this->regular_price - $this->price) / $this->regular_price * 100);
        }
        
        return 0;
    }

    /**
     * Check if the post is expired.
     */
    public function getIsExpiredAttribute()
    {
        if ($this->expires_at) {
            return $this->expires_at->isPast();
        }
        
        return false;
    }

    /**
     * Scope a query to only include non-expired posts.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope a query to only include posts of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('post_type', $type);
    }

    /**
     * Scope a query to only include posts near a specific location.
     */
    public function scopeNearby($query, $lat, $lng, $radius = 10) // radius in km
    {
        // This is a simplified implementation
        // In a real app, we would use spatial queries
        // For now, we'll just return all posts
        return $query;
    }

    /**
     * Like the post by a user.
     */
    public function likeBy($userId)
    {
        $like = $this->likes()->where('user_id', $userId)->first();
        
        if (!$like) {
            $this->likes()->create(['user_id' => $userId]);
            $this->increment('likes_count');
            return true;
        }
        
        return false;
    }

    /**
     * Unlike the post by a user.
     */
    public function unlikeBy($userId)
    {
        $like = $this->likes()->where('user_id', $userId)->first();
        
        if ($like) {
            $like->delete();
            $this->decrement('likes_count');
            return true;
        }
        
        return false;
    }
}
