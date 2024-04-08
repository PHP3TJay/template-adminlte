<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'mac_address',
        'account_attempted',
        'ipconfig_data',
    ];

}
