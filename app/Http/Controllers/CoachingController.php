<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CoachingLog;
use App\Models\CoachingLogDetail;
use App\Models\User;
use App\Models\TeamUser;
use App\Models\TeamPosition;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use App\Mail\CoachingCreationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Log;

class CoachingController extends Controller
{
    public function index(){
        $usersWithTeam = $this->getUsersWithTeam();
        $categories = Category::where('status', 1)->get();
        $currentUser = auth()->user();
        $currentRoleUser = RoleUser::where('user_id', $currentUser->id)->first();
        $teamUser = TeamUser::where('user_id', $currentUser->id)->first();
        $team_id = $teamUser->team_id;
        $teamPosition = TeamPosition::where('team_id', $team_id)->orderBy('hierarchy_level', 'desc')->first();
        $lowest = false;
        if($teamPosition->id == $teamUser->team_position_id){
            $lowest = true;
        }
        
        return view('coaching', compact('categories', 'usersWithTeam', 'currentRoleUser','lowest'));
    }

    public function coaching2() {
        $currentUser = auth()->user();
        $currentRoleUser = RoleUser::where('user_id', $currentUser->id)->first();
        $teamUser = TeamUser::where('user_id', $currentUser->id)->first();
        $team_id = $teamUser->team_id;
        $teamPosition = TeamPosition::where('team_id', $team_id)->orderBy('hierarchy_level', 'desc')->first();
        $lowest = false;
        if($teamPosition->id == $teamUser->team_position_id){
            $lowest = true;
        }
        return view('coaching2', compact('currentRoleUser', 'lowest'));
    }

    public function getUsersWithTeam(){
        $currentUser = auth()->user();
    
        // Get all team users for the current user
        $currentTeamUsers = TeamUser::where('user_id', $currentUser->id)->get();
        $teamData = [];

        foreach ($currentTeamUsers as $currentTeamUser) {
            $currentTeamPosition = TeamPosition::find($currentTeamUser->team_position_id);
            if ($currentTeamPosition->team_id == $currentTeamUser->team_id) {
                $teamData[] = [
                    'team_id' => $currentTeamUser->team_id,
                    'hierarchy_level' => $currentTeamPosition->hierarchy_level
                ];
            }
        }

        $usersQuery = TeamUser::select('users.id', DB::raw('MAX(users.firstname) as firstname'), DB::raw('MAX(users.lastname) as lastname'), DB::raw('MAX(teams.name) as team_name'), DB::raw('MAX(teams.id)  as team_id'))
            ->join('users', 'users.id', '=', 'team_users.user_id')
            ->join('teams', 'team_users.team_id', '=', 'teams.id')
            ->join('team_positions', 'team_positions.id', '=', 'team_users.team_position_id')
            ->where('team_users.user_id', '!=', $currentUser->id);

        $usersQuery->where(function ($query) use ($teamData) {
            foreach ($teamData as $team) {
                $query->orWhere(function ($subQuery) use ($team) {
                    $subQuery->where('team_users.team_id', $team['team_id'])
                        ->where('team_positions.hierarchy_level', '>', $team['hierarchy_level']);
                });
            }
        });

        $users = $usersQuery->groupBy('users.id')->get();
        
        return $users;
    }
    
    public function getCoachingData2_old(){
        $coachingLogs = CoachingLog::with([
            'coach:firstname,lastname,middlename,id',
            'coachingLogDetails.agent:firstname,lastname,middlename,id',
            'coachingLogDetails',
            'coachingLogDetails.category'
        ])->get();

        //return view( ['user'=>$user, 'roles'=>$roles, 'teams'=>$teams, 'modules'=> $modules, 'permissions' => $permissions]);
        return response()->json(['success' => true, 'coaching' => $coachingLogs], 200);
    }

    public function getCoachingData(Request $request, $type = null)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');
        

        $coachingLogsQuery = CoachingLog::select(
            'coaching_logs.id as coaching_log_id',
            'coaching_logs.date as coaching_log_date',
            'coaching_logs.coach_team_id as coaching_log_team_id',
            'coaching_logs.archive as coaching_log_archive',
            'coaching_log_details.id as coaching_log_details_id',
            'coaching_log_details.agent_id',
            'coaching_log_details.date_coached',
            'coaching_log_details.next_date_coached',
            'coaching_log_details.goal',
            'coaching_log_details.reality',
            'coaching_log_details.option',
            'coaching_log_details.will',
            'coaching_log_details.status',
            'coaching_log_details.follow_through',
            'categories.name as category_name',
            'u_agent.firstname as agent_firstname',
            'u_agent.lastname as agent_lastname'
        )
        ->join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->join('users as u_agent', 'coaching_log_details.agent_id', '=', 'u_agent.id')
        ->join ('categories', 'categories.id' , '=', 'coaching_log_details.category_id');
        
        if ($type == 'log') {
            $coachingLogsQuery->where('coaching_log_details.follow_through', '!=', 1)
                            ->where('coaching_log_details.status', 0);
        } elseif ($type == 'follow-through') {
            $coachingLogsQuery->where('coaching_log_details.follow_through', '=', 1)
                            ->whereNotIn('coaching_log_details.status', [4, 2])
                            ->where('date_coached', '>=', date('Y-m-d'));
        } elseif ($type == 'canceled') {
            $coachingLogsQuery->where('coaching_log_details.status', '=', 4); 
        } elseif ($type == 'declined') {
            $coachingLogsQuery->where('coaching_log_details.status', '=', 3); 
        } elseif ($type == 'completed') {
            $coachingLogsQuery->where('coaching_log_details.follow_through', '!=', 1)
                            ->where('coaching_log_details.status', '=', 2); 
        } elseif ($type == 'completed-follow-through') {
            $coachingLogsQuery->where('coaching_log_details.follow_through', '=', 1)
                            ->where('coaching_log_details.status', '=', 2); 
        } elseif ($type == 'due') {
            $coachingLogsQuery->where('coaching_log_details.status', 1)
                            ->where('date_coached', '<', date('Y-m-d'));
        } elseif ($type == 'accepted') {
            $coachingLogsQuery->where('coaching_log_details.status', '=', 1)
                            ->where('date_coached', '>=', date('Y-m-d'));
        }
        
        $user = Auth::user();
        $currentRoleUser = RoleUser::where('user_id', $user->id)->first();

        if (!in_array($currentRoleUser->role_id, [1, 2])) {
            $coachId = $user->id;
            $coachingLogsQuery->where('coaching_logs.coach_id', $coachId);
        }
        
        

        $orderColumnMapping = [
            0 => 'coaching_log_details.id desc', 
        ];

        $searchableColumnsMapping = [
            
        ];

        //$coachingLogsQuery->orderBy($orderColumnMapping[$orderColumn], $orderDir);
        $coachingLogsQuery->orderByRaw($orderColumnMapping[$orderColumn]);

        
        $search = $request->input('search.value');
        if ($search) {
            $coachingLogsQuery->where(function ($query) use ($searchableColumnsMapping, $search) {
                foreach ($searchableColumnsMapping as $index => $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        $recordsTotal = $coachingLogsQuery->count();
        $coachingLogs = $coachingLogsQuery->skip($start)->take($length)->get();
        $data = [];

        foreach ($coachingLogs as $coachingLog) {
        
            $coachingLogDate = new \DateTime($coachingLog['coaching_log_date']);
            $week = $coachingLogDate->format('W');
        
            $agentName = $coachingLog['agent_firstname'] . " " . $coachingLog['agent_lastname'];
        
            $data[] = [
                'id' => $coachingLog['coaching_log_details_id'],
                'week' => $week,
                'agent' => $agentName,
                'category' => $coachingLog['category_name'],
                'status' => $coachingLog['status'],
                'date_coached' => $coachingLog['date_coached'],
                'next_date_coached' => $coachingLog['next_date_coached']
            ];

            if($type == 'due' || $type == 'accepted')
                $data[count($data) - 1]['follow_through'] = $coachingLog['follow_through'];
            
                  
            
            if ($type == 'canceled') {
                unset($data['next_date_coached']);
            }
        }

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ];

        return response()->json($response);
    }

    private function getCoachingTypeFromUrl()
    {
        $routeName = Route::currentRouteName();

        // Map route names to coaching types
        $coachingTypeMap = [
            'coaching-log' => 'log',
            'coaching-follow-through' => 'follow-through',
            'coaching-canceled' => 'canceled',
        ];

        return $coachingTypeMap[$routeName] ?? null;
    }

    public function getCoachingData2(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');

        $user = Auth::user();
        $agent_id = $user->id;
        $coachingLogsQuery = CoachingLog::select(
            'coaching_logs.id as coaching_log_id',
            'coaching_logs.date as coaching_log_date',
            'coaching_logs.coach_team_id as coaching_log_team_id',
            'coaching_logs.archive as coaching_log_archive',
            'coaching_log_details.id as coaching_log_details_id',
            'coaching_log_details.agent_id',
            'coaching_log_details.date_coached',
            'coaching_log_details.next_date_coached',
            'coaching_log_details.goal',
            'coaching_log_details.reality',
            'coaching_log_details.option',
            'coaching_log_details.will',
            'coaching_log_details.status',
            'u_coach.firstname as coach_firstname',
            'u_coach.lastname as coach_lastname',
            'categories.name as category_name'
        )
        ->join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
        ->join('users as u_coach', 'coaching_logs.coach_id', '=', 'u_coach.id')
        ->join ('categories', 'categories.id' , '=', 'coaching_log_details.category_id')
        ->where('coaching_log_details.agent_id', $agent_id)
        ->where('coaching_log_details.status', '!=' , 4)
        ->whereNotNull([
            'coaching_log_details.goal', 
            'coaching_log_details.reality', 
            'coaching_log_details.option'
        ]);

        
        

        $orderColumnMapping = [
            0 => 'coaching_log_details.id desc', 
        ];

        $searchableColumnsMapping = [
            
        ];

        //$coachingLogsQuery->orderBy($orderColumnMapping[$orderColumn], $orderDir);
        $coachingLogsQuery->orderByRaw($orderColumnMapping[$orderColumn]);

        
        $search = $request->input('search.value');
        if ($search) {
            $coachingLogsQuery->where(function ($query) use ($searchableColumnsMapping, $search) {
                foreach ($searchableColumnsMapping as $index => $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        $recordsTotal = $coachingLogsQuery->count();

        $coachingLogs = $coachingLogsQuery->skip($start)->take($length)->get();

        $data = [];

        foreach ($coachingLogs as $coachingLog) {
            $coachName = $coachingLog['coach_firstname'] . " " . $coachingLog['coach_lastname'];
        
            $coachingLogDate = new \DateTime($coachingLog['coaching_log_date']);
            $week = $coachingLogDate->format('W');
        
            $data[] = [
                'id' => $coachingLog['coaching_log_details_id'],
                'week' => $week,
                'coach' => $coachName,
                'category' => $coachingLog['category_name'],
                'status' => $coachingLog['status'],
                'date_coached' => $coachingLog['date_coached'],
                'next_date_coached' => $coachingLog['next_date_coached']
            ];
        }

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required',
            'category_id' => 'required',
            'date_coached' => 'required|date',
            'goal' => 'required',
            'reality' => 'required',
            'option' => 'required',
            'channel' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        try {
            $coach_id = Auth::user()->id;
            $coach_team_id = Auth::user()->team->first()->id;

            $coachingLog = CoachingLog::create([
                'coach_id' => $coach_id,
                'date' => $request->input('date_coached'),
                'coach_team_id' => $coach_team_id, 
                'archive' => 0,
            ]);

            $coachingLog->coachingLogDetails()->create([
                'agent_id' => $request->input('agent_id'),
                'agent_team_id' => $request->input('team_id'),
                'date_coached' => $request->input('date_coached'),
                'category_id' => $request->input('category_id'),
                'goal' => $request->input('goal'),
                'reality' => $request->input('reality'),
                'option' => $request->input('option'),
                'channel' => $request->input('channel'),
                'status' => '0',
            ]);

            $coaching = CoachingLog::select(
                'coaching_logs.id as coaching_log_id',
                'coaching_log_details.date_coached',
                'coaching_log_details.next_date_coached',
                'coaching_log_details.goal',
                'coaching_log_details.reality',
                'coaching_log_details.option',
                'coaching_log_details.will',
                'categories.name as category_name',
                'u_coach.firstname as coach_firstname',
                'u_coach.lastname as coach_lastname',
                'u_agent.firstname as agent_firstname',
                'u_agent.lastname as agent_lastname',
                'u_agent.email as agent_email'
            )
            ->join('coaching_log_details', 'coaching_logs.id', '=', 'coaching_log_details.coaching_log_id')
            ->join ('categories', 'categories.id' , '=', 'coaching_log_details.category_id')
            ->join('users as u_coach', 'coaching_logs.coach_id', '=', 'u_coach.id')
            ->join('users as u_agent', 'coaching_log_details.agent_id', '=', 'u_agent.id')
            ->where('coaching_log_details.coaching_log_id', $coachingLog->id)->first();
            
            Mail::to($coaching->agent_email)->send(new CoachingCreationEmail($coaching));

            return response()->json(['success' => 'Coaching Log created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getCoachingLogDetailById($id)
    {
        $coachingLogDetail = CoachingLogDetail::find($id);
        $coachingLog = CoachingLog::find($coachingLogDetail->coaching_log_id);
        $getAgent = User::find($coachingLogDetail->agent_id);
        $agent_name = $getAgent->lastname . ", " . $getAgent->firstname;
        $categories = Category::where('status', 1)->get();
        return view('modals.view_log_details', ['coachingLogDetail'=>$coachingLogDetail, 'coachingLog' => $coachingLog, 'categories' => $categories, 'agent_name' => $agent_name]);
        //return response()->json(['success' => true, 'coachingLogDetail' => $coachingLogDetail], 200);
    }

    public function saveChanges(Request $request)
    {
        try  {
            $coachingLogDetail = CoachingLogDetail::find($request->coaching_log_detail_id);
            if($coachingLogDetail) {
                $coachingLogDetail->category_id = $request->category_id ;
                $coachingLogDetail->date_coached = $request->date_coached ;
                $coachingLogDetail->goal = $request->goal ;
                $coachingLogDetail->reality = $request->reality ;
                $coachingLogDetail->option = $request->option ;
                $coachingLogDetail->channel = $request->channel ;

                if($coachingLogDetail->save())
                    return response()->json(['success' => true, 'coachingLogDetail' => $coachingLogDetail], 200);
                else 
                    return response()->json(['error' => "Unable to save changes for now"], 200);
            }
        } catch (ValidationException $e) {  
            
            return response()->json(['error' => $e->errors()], 200);
        }
    }   

    public function cancelCoaching($id)
    {
        try  {
            $coachingLogDetail = CoachingLogDetail::find($id);
            if($coachingLogDetail) {
                $coachingLogDetail->status = 4 ;
                if($coachingLogDetail->save())
                    return response()->json(['success' => true, 'message' => 'Coaching Log has been canceled'], 200);
                else 
                    return response()->json(['error' => "Unable to cancel coaching for now"], 200);
            }
        } catch (ValidationException $e) {  
            
            return response()->json(['error' => $e->errors()], 200);
        }
    }   

    public function acceptCoaching(Request $request)
    {
        try  {
            $coachingLogDetail = CoachingLogDetail::find($request->coaching_log_detail_id);
            if($coachingLogDetail) {
                $coachingLogDetail->will = $request->will ;
                $coachingLogDetail->status = 1 ;
                if($coachingLogDetail->save())
                    return response()->json(['success' => true, 'coachingLogDetail' => $coachingLogDetail], 200);
                else 
                    return response()->json(['error' => "Unable to accept coaching for now"], 200);
            }
        } catch (ValidationException $e) {  
            
            return response()->json(['error' => $e->errors()], 200);
        }
    }

    public function declineCoaching($id, $reason)
    {
        try  {
            $coachingLogDetail = CoachingLogDetail::find($id);
            if($coachingLogDetail) {
                $coachingLogDetail->status = 3 ;
                $coachingLogDetail->reason = $reason;
                if($coachingLogDetail->save())
                    return response()->json(['success' => true, 'message' => 'Coaching Log has been declined'], 200);
                else 
                    return response()->json(['error' => "Unable to decline coaching for now"], 200);
            }
        } catch (ValidationException $e) {  
            
            return response()->json(['error' => $e->errors()], 200);
        }
    }   

    public function completeCoaching(Request $request)
    {
        try  {
            $coachingLogDetail = CoachingLogDetail::find($request->coaching_log_detail_id);
            if($coachingLogDetail) {
                $coachingLogDetail->category_id = $request->category_id ;
                $coachingLogDetail->date_coached = $request->date_coached ;
                $coachingLogDetail->goal = $request->goal ;
                $coachingLogDetail->reality = $request->reality ;
                $coachingLogDetail->option = $request->option ;
                $coachingLogDetail->status = 2 ;

                if($request->checkbox_next_date === "true")
                {
                    if (is_null($request->next_date_coached)) {
                        return response()->json(['error' => "Please set the Next Coaching Date if you put a check on the checkbox, if not, Please uncheck it"], 200);
                    }
                    $coachingLogDetail->next_date_coached = $request->next_date_coached;
                    $this->createFollowThrough($coachingLogDetail);
                }

                
                if($coachingLogDetail->save())
                    return response()->json(['success' => true, 'coachingLogDetail' => $coachingLogDetail], 200);
                else 
                    return response()->json(['error' => "Unable to save changes for now"], 200);
            }
        } catch (ValidationException $e) {  
            
            return response()->json(['error' => $e->errors()], 200);
        }
    }   

    public function createFollowThrough($coachingLogDetail){
        
        $coach_id = Auth::user()->id;
        $coach_team_id = Auth::user()->team->first()->id;

        $coachingLog = CoachingLog::create([
            'coach_id' => $coach_id,
            'date' => $coachingLogDetail->next_date_coached,
            'coach_team_id' => $coach_team_id, 
            'archive' => 0,
        ]);

        $follow_through_count = 0;
        if(in_array($coachingLogDetail->follow_coaching_log_parent, [null, ""])){
            $follow_coaching_log_parent = $coachingLogDetail->coaching_log_id;
            $follow_through_count++;
        }
        else {
            $follow_coaching_log_parent = $coachingLogDetail->follow_coaching_log_parent;
            $follow_through_count = CoachingLogDetail::where('follow_coaching_log_parent', $coachingLogDetail->follow_coaching_log_parent)->count();
            $follow_through_count++;
        }
        $coachingLog->coachingLogDetails()->create([
            'agent_id' => $coachingLogDetail->agent_id,
            'agent_team_id' => $coachingLogDetail->agent_team_id,
            'date_coached' => $coachingLogDetail->next_date_coached,
            'category_id' => $coachingLogDetail->category_id,
            'channel' => $coachingLogDetail->channel,
            'goal' => $coachingLogDetail->goal,
            'reality' => $coachingLogDetail->reality,
            'option' => $coachingLogDetail->option,
            'status' => '0',
            'follow_through' => '1',
            'follow_coaching_log_parent' => $follow_coaching_log_parent,
            'follow_through_count' => $follow_through_count
        ]);
    }

    public function getPrint($id)
    {
        $coachingLogDetail = CoachingLogDetail::find($id);
        $coachingLog = CoachingLog::find($coachingLogDetail->coaching_log_id);
        $getAgent = User::find($coachingLogDetail->agent_id);
        $agent_name = $getAgent->firstname . " " . $getAgent->lastname;
        $getCoach = User::find($coachingLog->coach_id);
        $coach_name =  $getCoach->firstname . " " . $getCoach->lastname;
        $categories = Category::where('status', 1)->get();
        return view('layouts.print', ['coachingLogDetail'=>$coachingLogDetail, 'coachingLog' => $coachingLog, 'categories' => $categories, 'agent_name' => $agent_name, 'coach_name' => $coach_name]);
        //return response()->json(['success' => true, 'coachingLogDetail' => $coachingLogDetail], 200);
    }

}
