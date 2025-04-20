<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'username',
        'avatar',
        'location',
        'favorite_stores',
        'preferences',
        'bio',
        'level',
        'points',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'location' => 'array',
        'favorite_stores' => 'array',
        'preferences' => 'array',
    ];

    /**
     * Get the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the price reports submitted by the user.
     */
    public function priceReports()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Get the badges earned by the user.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withTimestamp('earned_at')
            ->withPivot('metadata');
    }

    /**
     * Get the user's point transactions.
     */
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Get the receipts uploaded by the user.
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * Get the user's level title.
     */
    public function getLevelTitleAttribute()
    {
        $points = $this->points ?? 0;
        
        if ($points >= 2001) {
            return 'Maître';
        } elseif ($points >= 501) {
            return 'Expert';
        } elseif ($points >= 101) {
            return 'Éclaireur';
        } else {
            return 'Débutant';
        }
    }

    /**
     * Get the points needed for the next level.
     */
    public function getNextLevelPointsAttribute()
    {
        $points = $this->points ?? 0;
        
        if ($points < 101) {
            return 101;
        } elseif ($points < 501) {
            return 501;
        } elseif ($points < 2001) {
            return 2001;
        } else {
            return null; // Max level
        }
    }

    /**
     * Get the progress percentage to the next level.
     */
    public function getLevelProgressAttribute()
    {
        $points = $this->points ?? 0;
        $nextLevel = $this->next_level_points;
        
        if ($nextLevel === null) {
            return 100;
        }
        
        if ($points < 101) {
            return ($points / 101) * 100;
        } elseif ($points < 501) {
            return (($points - 101) / (501 - 101)) * 100;
        } elseif ($points < 2001) {
            return (($points - 501) / (2001 - 501)) * 100;
        }
        
        return 100;
    }

    /**
     * Award points to the user.
     *
     * @param int $points
     * @param string $actionType
     * @param string|null $description
     * @param array|null $metadata
     * @return PointTransaction
     */
    public function awardPoints($points, $actionType, $description = null, $metadata = null)
    {
        // Update user's total points
        $this->increment('points', $points);

        // Create a point transaction record
        return $this->pointTransactions()->create([
            'action_type' => $actionType,
            'points' => $points,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Check if the user has a specific badge.
     *
     * @param string $badgeSlug
     * @return bool
     */
    public function hasBadge($badgeSlug)
    {
        return $this->badges()->where('slug', $badgeSlug)->exists();
    }
}
