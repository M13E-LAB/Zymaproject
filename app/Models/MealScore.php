<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'health_score',
        'visual_score',
        'diversity_score',
        'total_score',
        'is_ai_scored',
        'ai_analysis',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'health_score' => 'integer',
        'visual_score' => 'integer',
        'diversity_score' => 'integer',
        'total_score' => 'integer',
        'is_ai_scored' => 'boolean',
        'ai_analysis' => 'array',
    ];

    /**
     * Get the post that owns the score.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Calculate the total score from the component scores.
     */
    public function calculateTotalScore()
    {
        $this->total_score = round(
            ($this->health_score * 0.5) + 
            ($this->visual_score * 0.3) + 
            ($this->diversity_score * 0.2)
        );
        
        return $this->total_score;
    }

    /**
     * Get a qualitative rating based on the total score.
     */
    public function getRatingAttribute()
    {
        if ($this->total_score >= 90) {
            return 'excellent';
        } elseif ($this->total_score >= 75) {
            return 'très bien';
        } elseif ($this->total_score >= 60) {
            return 'bien';
        } elseif ($this->total_score >= 40) {
            return 'moyen';
        } else {
            return 'à améliorer';
        }
    }
} 