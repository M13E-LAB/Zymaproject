<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action_type',
        'points',
        'description',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'points' => 'integer',
    ];

    /**
     * Get the user that owns the point transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the action types and their point values.
     */
    public static function getActionPoints()
    {
        return [
            'register' => 10,
            'daily_login' => 5,
            'upload_receipt' => 15,
            'share_price' => 5,
            'price_verified' => 2,
            'share_deal' => 10,
            'like_received' => 1,
            'comment_received' => 2,
            'profile_completed' => 25,
        ];
    }

    /**
     * Award points for a specific action.
     */
    public static function awardPoints($userId, $actionType, $description = null, $metadata = null)
    {
        $actionPoints = self::getActionPoints();
        
        if (!isset($actionPoints[$actionType])) {
            return null;
        }
        
        $points = $actionPoints[$actionType];
        $user = User::find($userId);
        
        if (!$user) {
            return null;
        }
        
        return $user->awardPoints($points, $actionType, $description, $metadata);
    }
}
