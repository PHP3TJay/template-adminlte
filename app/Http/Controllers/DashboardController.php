<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RoleUser;
use App\Models\TeamUser;
use App\Models\TeamPosition;
use App\Models\Role;
use App\Models\User;
use App\Models\Team;
use App\Models\CoachingLog;
use App\Models\CoachingLogDetail;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentRoleUser = RoleUser::where('user_id', $user->id)->first();
        $latestRole = Role::latest('id')->first();
        $teamUser = TeamUser::where('user_id', $user->id)->first();
        $team_id = $teamUser->team_id;
        $teamPosition = TeamPosition::where('team_id', $team_id)->orderBy('hierarchy_level', 'desc')->first();
        $lowest = false;
        if($teamPosition->id == $teamUser->team_position_id){
            $lowest = true;
        }




        $coachingLogsCount = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.follow_through', '!=', 1)
        ->where('coaching_log_details.status', 0)
        ->where('coaching_logs.coach_id', $user->id)
        ->count();

        $coachingLogsCompletedCount = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.follow_through', '!=', 1)
        ->where('coaching_log_details.status', '=', 2)
        ->where('coaching_logs.coach_id', $user->id)
        ->count();

        $coachingFollowThrough = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.follow_through', '=', 1)
        ->whereNotIn('coaching_log_details.status', [4, 2])
        ->where('coaching_logs.coach_id', $user->id)
        ->where('date_coached', '>=', date('Y-m-d'))
        ->count();

        $coachingFollowThroughCompleted = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.follow_through', '=', 1)
        ->where('coaching_log_details.status', '=', 2)
        ->where('coaching_logs.coach_id', $user->id)
        ->count();

        $coachingCanceled = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.status', '=', 4)
        ->where('coaching_logs.coach_id', $user->id)
        ->count();

        $coachingDeclined = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.status', '=', 3)
        ->where('coaching_logs.coach_id', $user->id)
        ->count();

        $mycoaching = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.agent_id', $user->id)
        ->where('coaching_log_details.status', '!=', 4)
        ->count();

        $accepted = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.follow_through', '!=', 1)
        ->where('coaching_log_details.status', 1)
        ->where('date_coached', '>=', date('Y-m-d'))
        ->where('coaching_logs.coach_id', $user->id)
        ->count();

        $due = CoachingLog::join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->where('coaching_log_details.status', 1)
        ->where('date_coached', '<', date('Y-m-d'))
        ->where('coaching_logs.coach_id', $user->id)
        ->count();

        $teams = Team::count();
        $users = User::count();
        
        return view('dashboard', 
                compact(
                    'currentRoleUser',
                    'coachingLogsCount',
                    'coachingFollowThrough',
                    'coachingCanceled',
                    'mycoaching', 
                    'latestRole', 
                    'coachingLogsCompletedCount',
                    'coachingFollowThroughCompleted',
                    'accepted',
                    'due',
                    'coachingDeclined',
                    'lowest',
                    'users',
                    'teams'
                )
            );
    }
}
