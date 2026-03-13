<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_CONCLUDED = 'concluded';

    protected $fillable = [
        'name',
        'status',
        'expected_at',
        'started_at',
        'concluded_at',
    ];

    protected $casts = [
        'expected_at' => 'datetime',
        'started_at' => 'datetime',
        'concluded_at' => 'datetime',
    ];

    /**
     * Scope for active event.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get the current active event.
     */
    public static function current()
    {
        return self::where('status', self::STATUS_ACTIVE)->first();
    }

    /**
     * Contestants belonging to the event.
     */
    public function contestants()
    {
        return $this->hasMany(Contestant::class);
    }

    /**
     * Criteria belonging to the event.
     */
    public function criteria()
    {
        return $this->hasMany(Criterion::class);
    }

    /**
     * Get calculated rankings for this event.
     */
    public function rankings()
    {
        $judgeCount = User::where('role', User::ROLE_JUDGE)->count();
        $criteria = $this->criteria;
        $criteriaCount = $criteria->count();
        $totalPossibleItems = $judgeCount * $criteriaCount;
        
        $formula = Setting::getValue('tabulation_formula', 'normal');
        $totalWeight = $criteria->sum('weight');

        return $this->contestants()->with(['scores'])
            ->get()
            ->map(function ($contestant) use ($totalPossibleItems, $formula, $criteria, $totalWeight) {
                $scores = $contestant->scores;
                $actualScoresCount = $scores->count();
                
                $totalScoreSum = $scores->sum('score');
                $distinctJudges = $scores->pluck('judge_id')->unique()->count();
                
                if ($formula === 'weighted' && $totalWeight > 0) {
                    // For weighted average, we calculate the weighted average per judge and then average those
                    $judgeScores = $scores->groupBy('judge_id')->map(function ($judgeGroup) use ($criteria, $totalWeight) {
                        $weightedSum = 0;
                        foreach ($judgeGroup as $score) {
                            $criterion = $criteria->firstWhere('id', $score->criterion_id);
                            if ($criterion) {
                                $weightedSum += ($score->score * $criterion->weight);
                            }
                        }
                        return $weightedSum / $totalWeight;
                    });
                    
                    $averageScore = $judgeScores->count() > 0 ? $judgeScores->avg() : 0;
                } else {
                    // Normal average: total divided by number of judges
                    $averageScore = $distinctJudges > 0 ? $totalScoreSum / $distinctJudges : 0;
                }
                
                $progress = $totalPossibleItems > 0 ? ($actualScoresCount / $totalPossibleItems) * 100 : 0;

                return (object) [
                    'id' => $contestant->id,
                    'name' => $contestant->name,
                    'number' => $contestant->number,
                    'image_path' => $contestant->image_path,
                    'average_score' => $averageScore,
                    'total_score' => $totalScoreSum,
                    'progress' => $progress,
                    'is_complete' => $actualScoresCount >= $totalPossibleItems && $totalPossibleItems > 0,
                ];
            })
            ->sortByDesc('average_score')
            ->values()
            ->map(function ($contestant, $index) {
                $contestant->rank = $index + 1;
                return $contestant;
            });
    }
}
