<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_positions')
            ->withPivot('hierarchy_level')
            ->orderBy('hierarchy_level');
    }
}
