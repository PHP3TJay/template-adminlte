<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachingLogDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'coaching_log_id','agent_id', 'agent_team_id' ,'date_coached', 'next_date_coached', 'category_id', 'objective', 'goal',
        'reality', 'option', 'will', 'qa_score', 'status', 'follow_through', 'channel','reason','follow_coaching_log_parent','follow_through_count'
    ];

    public function coachingLog()
    {
        return $this->belongsTo(CoachingLog::class);
    }

    public function agent() 
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function agentTeam()
    {
        return $this->belongsTo(Team::class, 'agent_team_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
