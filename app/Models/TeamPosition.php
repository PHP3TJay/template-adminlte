<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPosition extends Model
{
    use HasFactory;

    protected $table = 'team_positions';
    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'title',
        'hierarchy_level',
        'is_active',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}