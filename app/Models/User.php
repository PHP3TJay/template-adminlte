<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'username',
        'email',
        'password',
        'photo',
        'account_status',
        'email_verified_at',
        'password_changed',
        'employee_id',
        'mypat_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_users')->withPivot('team_id');
    }

    public function team()
    {
        return $this->belongsToMany(Team::class, 'team_users');
    }

    public function roleUser()
    {
        return $this->hasMany(RoleUser::class);
    }
    
    // Relationship with CoachingLogs as a coach
    public function coachedCoachingLogs()
    {
        return $this->hasMany(CoachingLog::class, 'coach_id');
    }

    // Relationship with CoachingLogs as an agent
    public function agentCoachingLogDetails()
    {
        return $this->hasMany(CoachingLogDetail::class, 'agent_id');
    }

    public function hasRole($role)
    {
        return $this->role->contains('name', $role);
    }

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function loginHistory()
    {
        return $this->hasMany(LoginHistory::class, 'user_id');
    }

    public function getNameAttribute()
    {
        // Assuming you have a 'name' column in your users table
        return $this->attributes['name'];
    }
}
