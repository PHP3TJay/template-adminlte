<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'status'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->belongsTo(Roles::class);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'team_positions')
            ->withPivot('hierarchy_level')
            ->orderBy('hierarchy_level');
    }
}
