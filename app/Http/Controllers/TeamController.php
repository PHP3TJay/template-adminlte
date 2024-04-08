<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamPosition;
use App\Models\TeamUser;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Log;

class TeamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $teams = Team::orderBy('id', 'desc')->get();
        $currentRoleUser = RoleUser::where('user_id', $user->id)->first();
        $teamUser = TeamUser::where('user_id', $user->id)->first();
        $team_id = $teamUser->team_id;
        $teamPosition = TeamPosition::where('team_id', $team_id)->orderBy('hierarchy_level', 'desc')->first();
        $lowest = false;
        if($teamPosition->id == $teamUser->team_position_id){
            $lowest = true;
        }

        if (Gate::allows('manage-superadmin', $user) || Gate::allows('manage-admin', $user))
            return view('team',compact('teams', 'currentRoleUser', 'lowest'));
        else 
            return view('401',compact('teams', 'currentRoleUser'));
        
    }

    public function view_team($id)
    {
        $user = auth()->user();
        $currentRoleUser = RoleUser::where('user_id', $user->id)->first();
        $teamUser = TeamUser::where('user_id', $user->id)->first();
        $team_id = $teamUser->team_id;
        $teamPosition = TeamPosition::where('team_id', $team_id)->orderBy('hierarchy_level', 'desc')->first();
        $lowest = false;
        if($teamPosition->id == $teamUser->team_position_id){
            $lowest = true;
        }
        if (Gate::allows('manage-superadmin', $user) || Gate::allows('manage-admin', $user))
        {
            try {
                $team = Team::find($id);
                if($team) {
                    $team_positions = TeamPosition::where('team_id',$id)->orderBy('hierarchy_level')->get();
                    return view('team_edit',compact('team', 'currentRoleUser', 'team_positions','lowest'));
                }

            } catch (\Exception $e) {
                return $e->getMessage();
                //return view('404',compact('teams', 'currentRoleUser'));
            }
        }   
        else 
            return view('401',compact('teams', 'currentRoleUser'));
    }
    
    public function getTeam(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');

        $teamsQuery = Team::orderBy('id', 'desc');

        $orderColumnMapping = [
            0 => 'id',
        ];


        $searchableColumnsMapping = [
            1 => 'name',
        ];

        $teamsQuery->orderBy($orderColumnMapping[$orderColumn], $orderDir);

        $search = $request->input('search.value');
        if ($search) {
            $teamsQuery->where(function ($query) use ($searchableColumnsMapping, $search) {
                foreach ($searchableColumnsMapping as $index => $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        $recordsTotal = $teamsQuery->count();

        $teams = $teamsQuery->skip($start)->take($length)->get();

        $data = [];

        foreach ($teams as $team) {
            $data[] = [
                'id' => $team->id,
                'name' => $team->name,
                'description' => $team->description,
                'status' => $team->status
            ];
        }

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ];

        return response()->json($response);
        
        // $teams = Team::orderBy('id', 'desc')->get();
        // return response()->json([
        //     'teams' => $teams,
        // ]);
    }

    public function getTeamUsers(Request $request)
    {
        $team_id = $request->input('team_id');
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');

        $teamUsersQuery = TeamUser::select(
            'team_users.id',
            'users.id as user_id',
            'users.employee_id',
            'users.lastname',
            'users.firstname',
            'users.middlename',
            'team_positions.title'
        )
        ->join('teams', 'teams.id', '=', 'team_users.team_id')
        ->join('users', 'users.id', '=', 'team_users.user_id')
        ->join ('team_positions', 'team_positions.id' , '=', 'team_users.team_position_id')
        ->where('team_users.team_id', $team_id);
        
        $orderColumnMapping = [
            0 => 'users.employee_id', 
            1 => 'users.lastname', 
            2 => 'team_positions.title', 
        ];

        $searchableColumnsMapping = [
            0 => 'users.employee_id', 
            1 => 'users.lastname',
            2 => 'users.firstname',
            3 => 'users.middlename',
            4 => 'team_positions.title',
        ];
        $teamUsersQuery->orderByRaw($orderColumnMapping[$orderColumn]);

        
        $search = $request->input('search.value');
        if ($search) {
            $teamUsersQuery->where(function ($query) use ($searchableColumnsMapping, $search) {
                foreach ($searchableColumnsMapping as $index => $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        $recordsTotal = $teamUsersQuery->count();

        $teamUsers = $teamUsersQuery->skip($start)->take($length)->get();

        $data = [];

        foreach ($teamUsers as $teamUser) {
            $name = $teamUser['lastname'] . ", " . $teamUser['firstname'];
            $data[] = [
                'id' => $teamUser['id'],
                'user_id' => $teamUser['user_id'],
                'employee_id' => $teamUser['employee_id'],
                'name' => $name,
                'position' => $teamUser['title']
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

    public function save_team(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string'
        ]);
        $team = new Team([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        $team->save();

        return response()->json(['success' => 'Team created successfully'], 201);
    }

    public function update(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'name' => 'required'
            ]);
            Log::info($validatedData['name']);
            $id = $request['team_id'];
            $team = Team::find($id);

            if ($team) {
                $team->name = $validatedData['name'];
                $team->description = $request['description'];
                $team->status = $request['status'];
                $team->save();
            
            }  
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error decoding JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing data'], 200);
        }
    }

    public function getTeamById($id) {
        try {
            $team = Team::find($id);
            if($team){
                $team_positions = TeamPosition::where('team_id',$team->id)->orderBy('hierarchy_level', 'asc')->get();
                return view('modals.view_team_details', ['team'=>$team, 'team_positions' => $team_positions]);
            }
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 200);
        }
        
    }

    public function updatePosition(Request $request) {
        
        $positionsDataJson = $request->input('positionsData');
        DB::beginTransaction();
        try {
            $counter = 0;
            foreach ($positionsDataJson as $positionData) {
                $counter++;
                if($positionData['position_id'] === "new") {
                    if($positionData['checked']) {
                        $team_position = TeamPosition::create([
                            'team_id' => $request->input('team_id'),
                            'title' => $positionData['title'],
                            'hierarchy_level' => $counter,
                            'is_active' => true
                        ]);
                    }
                    else 
                        return response()->json(['error' => 'New position "'.$positionData['title'].'" added cannot be inactive, Please put a check on the checkbox first'], 200);
                }
                else {
                    $team_position = TeamPosition::find($positionData['position_id']);
                    $team_position->is_active = $positionData['checked'];
                    if(!$positionData['checked']) {
                        $team_users_count = TeamUser::where('team_position_id',$team_position->id)->count();
                        if($team_users_count > 0)
                            return response()->json(['error' => 'You cannot set a position inactive if there are users still associated with this current position.'], 200);
                        else
                            $team_position->is_active = $positionData['checked'];
                    }
                    $team_position->hierarchy_level = $counter;
                    $team_position->save();
                    Log::info($team_position);
                }
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error decoding JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing data'], 200);
        }
    }

    public function saveTeamUsers(Request $request)
    {
        $team_id = $request->input('team_id');
        $position_id = $request->input('position_id');
        $user_ids = $request->input('user_ids');

        foreach ($user_ids as $user_id) {
            TeamUser::create([
                'team_id' => $team_id,
                'user_id' => $user_id,
                'team_position_id' => $position_id
            ]);

            RoleUser::create([
                'user_id' => $user_id,
                'role_id' => '7',
                'team_id' => $team_id
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function getTeamPositions(Request $request) {
        try {
            $team_positions = TeamPosition::where('team_id', $request->team_id)->orderBy('hierarchy_level', 'asc')->get();
            if($team_positions) {
                return response()->json(['positions' => $team_positions]);
            }
            else {
                return response()->json(['error' => 'No team positions found for the given team'], 200);
            }
        } catch (\Throwable $e) {
            Log::error('Error decoding JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing data'], 200);
        }
    }

    public function removeUser($id , $user_id) {
        try {
            $team_users_count = TeamUser::where('user_id',$user_id)->count();
            if($team_users_count > 1) {
                $teamUser = TeamUser::find($id);
                if($teamUser){
                    $teamUser->delete();
                    return response()->json(['success' => 'User Removed Sucessfully'], 200);
                } else {
                    return response()->json(['error' => 'ID not found'], 200);
                }
            } else {
                return response()->json(['error' => 'This user does not have new team yet, Please add him to a new team first before removing them here'], 200);
            }
        } catch (\Throwable $e) {
            Log::error('Error decoding JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing data'], 200);
        }
    }
    
}



