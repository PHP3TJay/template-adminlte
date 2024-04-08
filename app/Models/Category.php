<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function coachingLogDetails()
    {
        return $this->hasMany(CoachingLogDetail::class, 'category_id');
    }
}
