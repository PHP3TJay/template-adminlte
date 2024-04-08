<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\UserRegistrationEmail;
use App\Models\User;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\TeamPosition;
use App\Models\LoginHistory;
use App\Models\PasswordHistory;
use App\Http\Requests\UserRuleRequest;
use App\Services\UsernameGenerator;
use App\Services\GeneratePassword;
use Log;




class UserController extends Controller
{
    protected $timezone;

    public function __construct() {
        $this->timezone = config('app.timezone');
    }

    public function index()
    {
        $user = Auth::user();
        $roles = $this->getAllowedRoles($user);
        $teams = $this->getAllowedTeams($user);
        $currentRoleUser = RoleUser::where('user_id', $user->id)->first();
        $teamUser = TeamUser::where('user_id', $user->id)->first();
        $team_id = $teamUser->team_id;
        $teamPosition = TeamPosition::where('team_id', $team_id)->orderBy('hierarchy_level', 'desc')->first();
        $lowest = false;
        if($teamPosition->id == $teamUser->team_position_id){
            $lowest = true;
        }
        //return response()->json(['users' => $users], 201);
        if (Gate::allows('manage-superadmin', $user) || Gate::allows('manage-admin', $user)) {
            if (DB::connection('mypat')->getPdo()) {
                $region = DB::connection('mypat')->table('region')->get();
                $team_name = DB::connection('mypat')->table('team_name')->get();
                $team_leader = DB::connection('mypat')
                                        ->table('users')
                                        ->select('team_leader', DB::raw('MAX(employee_id) as employee_id'))
                                        ->where('team_leader', '!=' , '')
                                        ->groupBy('team_leader')
                                        ->orderBy('team_leader', 'asc')
                                        ->get();
                $user_level = DB::connection('mypat')->table('user_levels')->get();
                return view('user', compact('roles', 'teams', 'currentRoleUser', 'lowest', 'region', 'team_name', 'team_leader', 'user_level'));
            }
        }
        else 
            return view('401', compact('currentRoleUser','lowest'));
        
    }

    private function getAllowedRoles($user)
    {
        $allowedRoles = [];

        if (Gate::allows('manage-superadmin', $user)) {
            $allowedRoles = Role::all();
        } elseif (Gate::allows('manage-admin', $user)) {
            $allowedRoles = Role::whereNotIn('name', ['superadmin', 'admin'])->get();
        }

        return $allowedRoles;
    }

    private function getAllowedTeams($user)
    {
        $allowedTeams = [];

        if (Gate::allows('manage-superadmin', $user)) {
            $allowedTeams = Team::all();
        } elseif (Gate::allows('manage-admin', $user)) {
            $allowedTeams = Team::where('id', '!=','1')->where('status', '!=', '0')->get();
        }

        return $allowedTeams;
    }

    public function getUsersData(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');

        $usersQuery = User::with(['role', 'team', 'loginHistory']);

        $orderColumnMapping = [
            0 => 'id',
            1 => 'lastname',
            2 => 'firstname',
            3 => 'middlename',
            4 => 'lastname',
            5 => 'created_at',
        ];

        $searchableColumnsMapping = [
            1 => 'lastname',
            2 => 'employee_id',
            3 => 'username',
            4 => 'email',
            7 => 'hostname',
        ];

        $usersQuery->orderBy($orderColumnMapping[$orderColumn], $orderDir);

        $search = $request->input('search.value');
        if ($search) {
            $usersQuery->where(function ($query) use ($searchableColumnsMapping, $search) {
                foreach ($searchableColumnsMapping as $index => $column) {
                    if ($column === 'created_at') {
                        $query->orWhereHas('loginHistory', function ($subquery) use ($search) {
                            $subquery->whereDate('created_at', '=', $search);
                        });
                    } elseif ($column === 'hostname') {
                        $query->orWhereHas('loginHistory', function ($subquery) use ($search) {
                            $subquery->where('hostname', 'LIKE', "%{$search}%");
                        });
                    } else {
                        $query->orWhere($column, 'LIKE', "%{$search}%");
                    }
                }
            });
        }

        $user = Auth::user();
        if (Gate::allows('manage-admin', $user)) {
            $usersQuery->whereDoesntHave('role', function ($query) {
                $query->whereIn('name', ['superadmin', 'admin']);
            });
        }

        $recordsTotal = $usersQuery->count();

        $users = $usersQuery->skip($start)->take($length)->get();

        $data = [];

        foreach ($users as $user) {
            $latestLogin = $user->loginHistory->sortByDesc('created_at')->first();

            $data[] = [
                'id' => $user->id,
                'name' => $user->lastname . ", " . $user->firstname . " " . $user->middlename,
                'employee_id' => $user->employee_id,
                'username' => $user->username,
                'email' => $user->email,
                'account_status' => $user->account_status,
                'created_at' => optional($latestLogin)->created_at ? optional($latestLogin)->created_at->format('M d, Y h:i:a') : null,
                'hostname' => optional($latestLogin)->hostname,
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

    public function getUserById($id)
    {
        $user = User::with(['loginHistory' => function ($query) {
            $query->latest();
        }])
        ->withCount('loginHistory')
        ->where('id', $id)
        ->orderBy('lastname', 'desc')
        ->first();
    
        $user->roles = RoleUser::where('user_id', $id)->with('role')->get();
    
        foreach ($user->roles as $role) {
            $role->team = Team::find($role->team_id);
        }
        $usesr = Auth();
        $roles = $this->getAllowedRoles($user);
        $teams = $this->getAllowedTeams($user);
        $modules = Module::all();  
        $permissions = Permission::where('user_id', $id)->get();

        

        return view('modals.view_user_details', ['user'=>$user, 'roles'=>$roles, 'teams'=>$teams, 'modules'=> $modules, 'permissions' => $permissions]);
        //return response()->json(['success' => true, 'user' => $user], 200);
    }

    public function create(Request  $request)
    {
        try {
            $username = UsernameGenerator::generateUniqueUsername($request->firstname, $request->lastname, $request->middlename);
            $password = GeneratePassword::generatePassword();
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'middlename' => $request->middlename,
                'username' => $username,
                'email' => $request->email,
                'account' => 'inactive',
                'password' => $password,
                'photo' => 'assets/images/questionmarklogo.png',
                'employee_id' => $request->employee_id
            ]);
            
            $this->storeRoleUser($user->id, 7, $request->team_id);
            $this->storeTeamUser($user->id, $request->team_id, $request->team_position_id);

            DB::connection('mypat')->table('users')->insert([
                'employee_id' => $request->employee_id,
                'first_name' => $request->firstname,
                'middle_name' => $request->middlename,
                'last_name' => $request->lastname,
                'email_address' => $request->email,
                'region' => $request->region,
                'site_address' => $request->site_address,
                'username' => $request->employee_id,
                'joined_date' => $request->joined_date,
                'user_level_id' => $request->user_level,
                'team_name' => $request->team_name,
                'team_leader' => $request->team_leader,
                'contact_number' => '',
            ]);
            
            Mail::to($user->email)->send(new UserRegistrationEmail($user, $password));

            return response()->json(['success' => 'User created successfully'], 201);

        } catch (ValidationException $e) {
            
            return response()->json(['error' => $e->errors()], 200);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            if($user) {

                $username = $this->checkChanges($user, $request);

                foreach ($user->getFillable() as $fillableAttribute) {
                    $value = data_get($request, $fillableAttribute, null);
    
                    if ($fillableAttribute === 'username') {
                        $user->username = $username;
                    } elseif ($value !== null) {
                        $user->$fillableAttribute = $value;
                    }
                }
                
                $user->save();
                $this->updatePermission($request);
                $this->updateRoleTeam($request);
                return response()->json(['success' => true,'message' => 'User has been updated successfully!']);
            }
            else {
                return response()->json(['message' => 'User not found.']);
            } 
            return response()->json(['message' => 'User updated successfully'], 200);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 200);
        }
    }


    public function updatePermission($request)
    {
        try {
            DB::beginTransaction();
            try {
                $existingPermissions = DB::table('permissions')->where('user_id', $request->user_id)->get();
                if ($existingPermissions->isNotEmpty()) {
                    DB::table('permissions')->where('user_id', $request->user_id)->delete();
                }
                foreach ($request->permissions as $permission) {
                    if($permission['checked'] == true){
                        DB::table('permissions')->insert([
                            'user_id' => $request->user_id,
                            'module_id' => $permission['module_id']
                        ]);
                    }
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Permissions updated successfully'], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } catch (ValidationException $e) {
            Log::error('Validation Errors: ' . json_encode($e->errors()));
            return response()->json(['error' => $e->errors()], 422);
        }
    }

    public function updateRoleTeam($request)
    {
        DB::connection()->enableQueryLog();
        DB::beginTransaction();
        try {
            DB::table('role_users')->where('user_id', $request->user_id)->delete();
            DB::table('team_users')->where('user_id', $request->user_id)->delete();
            if (isset($request->roles) && is_array($request->roles)) {
                $existingCombinations = [];
                foreach ($request->roles as $role_index => $role) {
                    foreach ($request->teams as $team_index => $team) {
                        if ($role_index == $team_index) {
                            $combination = $role['role_id'] . '_' . $team['team_id'];
                            if (!in_array($combination, $existingCombinations)) {
                                $existingCombinations[] = $combination;
                                $this->storeRoleUser($request->user_id, $role['role_id'], $team['team_id']);
                                $this->storeTeamUser($request->user_id, $team['team_id']);
                            }
                        }
                    }
                }
            }
            DB::commit();
            $queries = DB::getQueryLog();
            Log::info('Database Queries: ' . json_encode($queries));
            return response()->json(['success' => true, 'message' => 'Role and Team assignments updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Exception Message: ' . $e->getMessage());
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }






    public function checkChanges($user, $request)
    {
        $username = $user->username;
        $checkCaller = 0;
        if ($request->firstname !== $user->firstname || $request->middlename !== $user->middlename || $request->lastname !== $user->lastname) {
            $username = UsernameGenerator::generateUniqueUsername($request->firstname, $request->lastname, $request->middlename, $user->id);
            $checkCaller++;
        }

        if($checkCaller == 1){
            return $username;
        } else {
            return $user->username;
        }
    }



    public function updatePassword($user_id, $password)
    {
        try {
            $this->validatePasswordComplexity($password);
            
            $user = User::find($user_id);
            if($user) {
                $checkPasswordHistory = PasswordHistory::where(['user_id' => $user_id])
                                    ->orderByDesc('created_at')
                                    ->take('6')
                                    ->get();        
                if($checkPasswordHistory->isNotEmpty()) {
                    foreach($checkPasswordHistory as $data){
                        if(Hash::check($password,$data->password)) {
                            return response()->json(['errors' => 'Sorry, Password was already used. Please set a new password '], 200);
                        }
                    }
                }
                
                $MinutesToAdd = 129600; 
                $password_expiry_date = now($this->timezone)->addMinutes($MinutesToAdd);
                $user->password = $password;
                $user->password_expiry_date = $password_expiry_date;
                $user->password_changed = 1;
                $user->save();

                $passwordHistory = PasswordHistory::create([
                    'user_id' => $user_id,
                    'password' => Hash::make($password)
                ]);

                return response()->json(['message' => 'Password Change Successfully'], 200);
            }
            else {
                return response()->json(['message' => 'User not found.']);
            }
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 200);
        }
        
    }

    public function validatePasswordComplexity($password)
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'The password must be at least 8 characters.';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'The password must contain at least one uppercase letter.';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'The password must contain at least one lowercase letter.';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'The password must contain at least one numeric character.';
        }

        if (!preg_match('/[@$!%*#?&\-\\(){}[\]:;<>,.=_+~]/', $password)) {
            $errors[] = 'The password must contain at least one special character.';
        }

        if (!empty($errors)) {
            $errorMessage = implode(', ', $errors);
            throw ValidationException::withMessages(['password' => [$errorMessage]]);
        }
    }


    protected function storeRoleUser($userId, $roleId, $teamId)
    {  
        RoleUser::create([
            'user_id' => $userId,
            'role_id' => $roleId,
            'team_id' => $teamId
        ]);
    }

    protected function storeTeamUser($userId, $teamId, $teamPositionId)
    {
        TeamUser::create([
            'user_id' => $userId,
            'team_id' => $teamId,
            'team_position_id' => $teamPositionId
        ]);
    }

    public function deactivateUser(Request $request){
        try {
            $user = USER::find($request->user_id);
            if($user){
                $user->account_status = 'deactivate';
                $user->save();

                return  response()->json(['success' => 'Account has been deactivated successfully'], 201 );
            }
            else {
                throw new \Exception("User ID not found");
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUsersForTeam($teamId)  //this will be used for multiple selection of positions for different user but disabled for now
    {
        $users = User::all();

        $teamUsers = TeamUser::where('team_id', $teamId)->pluck('user_id')->toArray();

        $filteredUsers = $users->reject(function ($user) use ($teamUsers) {
            return in_array($user->id, $teamUsers) || in_array($user->id, [1, 2] ) ;
        });

        $formattedUsers = $filteredUsers->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->lastname . ', ' . $user->firstname
            ];
        });

        return response()->json(['data' => $formattedUsers]);
    }

    public function get_mypat_site_address(Request $request) {
        try {
            $site_address = DB::connection('mypat')->table('site_address')->where('region_name', $request->region_name)->get();
            if($site_address) {
                return response()->json(['site_addresses' => $site_address]);
            }
            else {
                return response()->json(['error' => 'No site address found on this region'], 200);
            }
        } catch (\Throwable $e) {
            Log::error('Error decoding JSON: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing data'], 200);
        }
    }
}
