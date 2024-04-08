<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hostname',
        'mac_address',
        'ipconfig_data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function setLoginHistory($data){
        
    // }
}
