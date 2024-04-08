<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id', 'date', 'coach_team_id', 'archive'
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    
    public function coachingLogDetails()
    {
        return $this->hasMany(CoachingLogDetail::class)->with('agent')->orderByDesc('id');
    }
}
