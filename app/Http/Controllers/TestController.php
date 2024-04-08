<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamPosition;
use App\Models\TeamUser;
use App\Models\RoleUser;
use App\Services\UsernameGenerator;
use App\Services\GeneratePassword;
use Illuminate\Support\Str;
use App\Mail\UserRegistrationEmail;
use Illuminate\Support\Facades\Mail;
use Log;


class TestController extends Controller
{
    public function index($offset, $limit) {
        $tests = DB::table('test')->where('user_level_id', '1')->where('team_name', '!=', 'PTC')->offset($offset)->limit($limit)->get();

        foreach($tests as $test){
            if (in_array($test->username, ['.', '0', '']))
                $username = UsernameGenerator::generateUniqueUsername($test->firstname, $test->lastname, $test->middlename);
            else 
                $username = strtolower($test->username);
            $password = GeneratePassword::generatePassword();
            $user = User::create([
                'firstname' => $test->firstname,
                'lastname' => $test->lastname,
                'middlename' => $test->middlename,
                'username' => $username,
                'email' => $test->email_address,
                'account' => 'active',
                'password' => $password,
                'employee_id' => $test->employee_id,
                //'mypat_id' => $test->mypat_id
            ]);

            DB::table('test')
            ->where('employee_id', $test->employee_id)
            ->update([
                'temp_password' => $password
            ]);

            $slug = Str::slug($test->team_name);
            $team = Team::where('slug', $slug)->first();
            if (!$team) {

                $team = Team::create([
                    'name' => $test->team_name,
                    'slug' => $slug
                ]);
            }

            $team_id = $team->id;

            if ($test->user_level_id == 1) {
                //check if position is available in team_positions using team_id
                $position = TeamPosition::where('team_id', $team_id)
                                          ->where('title', 'agent')
                                          ->first();
                if (!$position) {
                    //if not exists, create one, then get id
                    $position = TeamPosition::create([
                        'team_id' => $team_id,
                        'title' => 'agent',
                        'hierarchy_level' => 0 // Temporary level
                    ]);
    
                    // Update hierarchy levels based on the defined hierarchy
                    $hierarchyLevels = ['service level manager', 'service owner head', 'service owner', 'team leader', 'ptc', 'agent'];
                    $currentLevel = array_search(strtolower('agent'), $hierarchyLevels) + 1;
                    $teamPositions = TeamPosition::where('team_id', $team_id)->whereIn('title', $hierarchyLevels)->get();
                    foreach ($teamPositions as $teamPosition) {
                        if ($teamPosition->id == $position->id) {
                            $teamPosition->hierarchy_level = 1;
                        } else {
                            if (array_search(strtolower($teamPosition->title), $hierarchyLevels) + 1 < $currentLevel) {
                                $teamPosition->hierarchy_level++;
                            }
                        }
                        $teamPosition->save();
                    }
                }
    
                TeamUser::create([
                    'user_id' => $user->id,
                    'team_id' => $team_id,
                    'team_position_id' => $position->id
                ]);
    
                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 7,
                    'team_id' => $team_id
                ]);

                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '1', $test->tl_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '2', $test->so_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '3', $test->soh_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '4', $test->slm_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '5', $test->ptc_1_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '6', $test->ptc_2_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '7', $test->ptc_3_emp_id]);
            }

        }
        return response()->json(['tests' => $tests]);
    }

    public function test_user_ptc($offset, $limit) {
        $tests = DB::table('test')->where('user_level_id', '1')->where('team_name', '=', 'PTC')->offset($offset)->limit($limit)->get();

        foreach($tests as $test){

            if (in_array($test->username, ['.', '0', '']))
                $username = UsernameGenerator::generateUniqueUsername($test->firstname, $test->lastname, $test->middlename);
            else 
                $username = strtolower($test->username);
            $password = GeneratePassword::generatePassword();
            $user = User::create([
                'firstname' => $test->firstname,
                'lastname' => $test->lastname,
                'middlename' => $test->middlename,
                'username' => $username,
                'email' => $test->email_address,
                'account' => 'active',
                'password' => $password,
                'employee_id' => $test->employee_id,
                //'mypat_id' => $test->mypat_id
            ]);

            DB::table('test')
            ->where('employee_id', $test->employee_id)
            ->update([
                'temp_password' => $password
            ]);

        }
        return response()->json(['tests' => $tests]);
    }

    public function test_user_upper($offset, $limit) {
        $tests = DB::table('test')->whereIn('user_level_id', [2,3,4,5])->offset($offset)->limit($limit)->get();

        foreach($tests as $test){

            $user = User::where('employee_id', $test->employee_id)->first();

            if(!$user) {
                if (in_array($test->username, ['.', '0', '']))
                    $username = UsernameGenerator::generateUniqueUsername($test->firstname, $test->lastname, $test->middlename);
                else 
                    $username = strtolower($test->username);
                $password = GeneratePassword::generatePassword();
                $user = User::create([
                    'firstname' => $test->firstname,
                    'lastname' => $test->lastname,
                    'middlename' => $test->middlename,
                    'username' => $username,
                    'email' => $test->email_address,
                    'account' => 'active',
                    'password' => $password,
                    'employee_id' => $test->employee_id,
                    //'mypat_id' => $test->mypat_id
                ]);

                DB::table('test')
                ->where('employee_id', $test->employee_id)
                ->update([
                    'temp_password' => $password
                ]);
            }

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => 7,
            ]);

            DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '1', $test->tl_emp_id]);
            DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '2', $test->so_emp_id]);
            DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '3', $test->soh_emp_id]);
            DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '4', $test->slm_emp_id]);
            DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '5', $test->ptc_1_emp_id]);
            DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '6', $test->ptc_2_emp_id]);
            DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '7', $test->ptc_3_emp_id]);

        }
        return response()->json(['tests' => $tests]);
    }

    public function emailAccounts($offset, $limit) {
        $tests = DB::table('test')->whereIn('email_address', ['jvaleriano@smits.sanmiguel.com.ph', 'caragoncillo@smits.sanmiguel.com.ph'])->offset($offset)->limit($limit)->get();
        foreach($tests as $test){
            $user = User::where('employee_id', $test->employee_id)->first();
            if($user) {
                Mail::to($user->email)->send(new UserRegistrationEmail($user, $test->temp_password));
            }
            $test->email_status = 2;
            $test->save();
        }
        return response()->json(['tests' => $tests]);
    }


    public function tl($offset, $limit) {
        $tests = DB::table('test')->where('user_level_id', '2 ')->offset($offset)->limit($limit)->get();

        foreach($tests as $test){
            if (in_array($test->username, ['.', '0', '']))
                $username = UsernameGenerator::generateUniqueUsername($test->firstname, $test->lastname, $test->middlename);
            else 
                $username = strtolower($test->username);
            $password = GeneratePassword::generatePassword();
            $user = User::create([
                'firstname' => $test->firstname,
                'lastname' => $test->lastname,
                'middlename' => $test->middlename,
                'username' => $username,
                'email' => $test->email_address,
                'account' => 'active',
                'password' => $password,
                'employee_id' => $test->employee_id,
                //'mypat_id' => $test->mypat_id
            ]);

            DB::table('test')
            ->where('employee_id', $test->employee_id)
            ->update([
                'temp_password' => $password
            ]);

            $testteam = DB::table('test')->where('user_level_id', '2 ')->offset($offset)->limit($limit)->get();

            $slug = Str::slug($test->team_name);
            $team = Team::where('slug', $slug)->first();
            if (!$team) {

                $team = Team::create([
                    'name' => $test->team_name,
                    'slug' => $slug
                ]);
            }

            $team_id = $team->id;

            if ($test->user_level_id == 1) {
                //check if position is available in team_positions using team_id
                $position = TeamPosition::where('team_id', $team_id)
                                          ->where('title', 'agent')
                                          ->first();
                if (!$position) {
                    //if not exists, create one, then get id
                    $position = TeamPosition::create([
                        'team_id' => $team_id,
                        'title' => 'agent',
                        'hierarchy_level' => 0 // Temporary level
                    ]);
    
                    // Update hierarchy levels based on the defined hierarchy
                    $hierarchyLevels = ['service level manager', 'service owner head', 'service owner', 'team leader', 'ptc', 'agent'];
                    $currentLevel = array_search(strtolower('agent'), $hierarchyLevels) + 1;
                    $teamPositions = TeamPosition::where('team_id', $team_id)->whereIn('title', $hierarchyLevels)->get();
                    foreach ($teamPositions as $teamPosition) {
                        if ($teamPosition->id == $position->id) {
                            $teamPosition->hierarchy_level = 1;
                        } else {
                            if (array_search(strtolower($teamPosition->title), $hierarchyLevels) + 1 < $currentLevel) {
                                $teamPosition->hierarchy_level++;
                            }
                        }
                        $teamPosition->save();
                    }
                }
    
                TeamUser::create([
                    'user_id' => $user->id,
                    'team_id' => $team_id,
                    'team_position_id' => $position->id
                ]);
    
                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 7,
                    'team_id' => $team_id
                ]);

                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '1', $test->tl_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '2', $test->so_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '3', $test->soh_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '4', $test->slm_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '5', $test->ptc_1_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '6', $test->ptc_2_emp_id]);
                DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, '7', $test->ptc_3_emp_id]);
            }

        }
        return response()->json(['tests' => $tests]);
    }


    public function final_user($offset, $limit) {
        $tests = DB::table('test')->offset($offset)->limit($limit)->get();

        foreach($tests as $test){

            $user = User::where('employee_id', $test->employee_id)->first();

            if(!$user) {
                if (in_array($test->username, ['.', '0', '']))
                $username = UsernameGenerator::generateUniqueUsername($test->firstname, $test->lastname, $test->middlename);
                else 
                    $username = strtolower($test->username);
                $password = GeneratePassword::generatePassword();
                $user = User::create([
                    'firstname' => $test->firstname,
                    'lastname' => $test->lastname,
                    'middlename' => $test->middlename,
                    'username' => $username,
                    'email' => $test->email_address,
                    'account' => 'active',
                    'password' => $password,
                    'employee_id' => $test->employee_id,
                    //'mypat_id' => $test->mypat_id
                ]);

                DB::table('test')
                ->where('employee_id', $test->employee_id)
                ->update([
                    'temp_password' => $password
                ]);
            }

            $positions = [
                ['position_id' => '1', 'superior_id' => $test->tl_emp_id],
                ['position_id' => '2', 'superior_id' => $test->so_emp_id],
                ['position_id' => '3', 'superior_id' => $test->soh_emp_id],
                ['position_id' => '4', 'superior_id' => $test->slm_emp_id],
                ['position_id' => '5', 'superior_id' => $test->ptc_1_emp_id],
                ['position_id' => '6', 'superior_id' => $test->ptc_2_emp_id],
                ['position_id' => '7', 'superior_id' => $test->ptc_3_emp_id],
            ];
            
            foreach ($positions as $position) {
                $existingRecord = DB::table('management_position_users')
                    ->where('user_id', $user->id)
                    ->where('management_position_id', $position['position_id'])
                    ->first();
            
                if (!$existingRecord) {
                    DB::insert('INSERT INTO management_position_users (user_id, management_position_id, superior_employee_id) VALUES (?, ?, ?)', [$user->id, $position['position_id'], $position['superior_id']]);
                }
            }

            if (!in_array($test->team_name, ['.', '0', 'PTC'])) {


                $slug = Str::slug($test->team_name);
                $team = Team::where('slug', $slug)->first();
                if (!$team) {

                    $team = Team::create([
                        'name' => $test->team_name,
                        'slug' => $slug
                    ]);
                }

            
                if ($test->user_level_id == 1) {
                    $slug = Str::slug($test->team_name);
                    $team = Team::where('slug', $slug)->first();
                    $team_id = $team->id;

                    if (!in_array($test->tl_emp_id, ['.', '0', ''])) {
                        $tluser = User::where('employee_id', $test->tl_emp_id)->first();
                        if ($tluser) {
                            $tluser_id = $tluser->id;
                            $position = TeamPosition::where('team_id', $team_id)
                                                ->where('title', 'team leader')
                                                ->first();
                            if (!$position) {
                                $position = $this->addingPosition('team Leader',$team_id);
                            }
                                                
                            $teamUser = TeamUser::where('user_id',$tluser_id)->where('team_id',$team_id)->where('team_position_id',$position->id)->first();
                            if(!$teamUser) {
                                TeamUser::create([
                                    'user_id' => $tluser_id,
                                    'team_id' => $team_id,
                                    'team_position_id' => $position->id
                                ]);
                            }
                        }
                    }

                    if (!in_array($test->so_emp_id, ['.', '0', ''])) {
                        $souser = User::where('employee_id', $test->so_emp_id)->first();
                        if ($souser) {
                            $souser_id = $souser->id;
                            $position = TeamPosition::where('team_id', $team_id)
                                                ->where('title', 'service owner')
                                                ->first();
                            if (!$position) {
                                $position = $this->addingPosition('service owner',$team_id);
                            }
                                                
                            $teamUser = TeamUser::where('user_id',$souser_id)->where('team_id',$team_id)->where('team_position_id',$position->id)->first();
                            if(!$teamUser) {
                                TeamUser::create([
                                    'user_id' => $souser_id,
                                    'team_id' => $team_id,
                                    'team_position_id' => $position->id
                                ]);
                            }
                        }
                    }

                    if (!in_array($test->soh_emp_id, ['.', '0', ''])) {
                        $sohuser = User::where('employee_id', $test->soh_emp_id)->first();
                        if ($sohuser) {
                            $sohuser_id = $sohuser->id;
                            $position = TeamPosition::where('team_id', $team_id)
                                                ->where('title', 'service owner head')
                                                ->first();
                            if (!$position) {
                                $position = $this->addingPosition('service owner head',$team_id);
                            }
                            $teamUser = TeamUser::where('user_id',$sohuser_id)->where('team_id',$team_id)->where('team_position_id',$position->id)->first();
                            if(!$teamUser) {
                                $teamusercreate = TeamUser::create([
                                    'user_id' => $sohuser_id,
                                    'team_id' => $team_id,
                                    'team_position_id' => $position->id
                                ]);
                            }
                        }
                    }

                    if (!in_array($test->slm_emp_id, ['.', '0', ''])) {
                        $slmuser = User::where('employee_id', $test->slm_emp_id)->first();
                        if ($slmuser) {
                            $slmuser_id = $slmuser->id;
                            $position = TeamPosition::where('team_id', $team_id)
                                                ->where('title', 'service level manager')
                                                ->first();
                            if (!$position) {
                                $position = $this->addingPosition('service level manager',$team_id);
                            }
                                                
                            $teamUser = TeamUser::where('user_id',$slmuser_id)->where('team_id',$team_id)->where('team_position_id',$position->id)->first();
                            if(!$teamUser) {
                                TeamUser::create([
                                    'user_id' => $slmuser_id,
                                    'team_id' => $team_id,
                                    'team_position_id' => $position->id
                                ]);
                            }
                        }
                    }

                    if (!in_array($test->ptc_1_emp_id, ['.', '0', ''])) {
                        $ptcuser = User::where('employee_id', $test->ptc_1_emp_id)->first();
                        if ($ptcuser) {
                            $ptcuser_id = $ptcuser->id;
                            $position = TeamPosition::where('team_id', $team_id)
                                                ->where('title', 'ptc')
                                                ->first();
                            if (!$position) {
                                $position = $this->addingPosition('ptc',$team_id);
                            }
                                                
                            $teamUser = TeamUser::where('user_id',$ptcuser_id)->where('team_id',$team_id)->where('team_position_id',$position->id)->first();
                            if(!$teamUser) {
                                TeamUser::create([
                                    'user_id' => $ptcuser_id,
                                    'team_id' => $team_id,
                                    'team_position_id' => $position->id
                                ]);
                            }
                        }
                    }

                    if (!in_array($test->ptc_2_emp_id, ['.', '0', ''])) {
                        $ptcuser = User::where('employee_id', $test->ptc_2_emp_id)->first();
                        if ($ptcuser) {
                            $ptcuser_id = $ptcuser->id;
                            $position = TeamPosition::where('team_id', $team_id)
                                                ->where('title', 'ptc')
                                                ->first();
                            if (!$position) {
                                $position = $this->addingPosition('ptc',$team_id);
                            }
                                                
                            $teamUser = TeamUser::where('user_id',$ptcuser_id)->where('team_id',$team_id)->where('team_position_id',$position->id)->first();
                            if(!$teamUser) {
                                TeamUser::create([
                                    'user_id' => $ptcuser_id,
                                    'team_id' => $team_id,
                                    'team_position_id' => $position->id
                                ]);
                            }
                        }
                    }

                    if (!in_array($test->ptc_3_emp_id, ['.', '0', ''])) {
                        $ptcuser = User::where('employee_id', $test->ptc_3_emp_id)->first();
                        if ($ptcuser) {
                            $ptcuser_id = $ptcuser->id;
                            $position = TeamPosition::where('team_id', $team_id)
                                                ->where('title', 'ptc')
                                                ->first();
                            if (!$position) {
                                $position = $this->addingPosition('ptc',$team_id);
                            }
                                                
                            $teamUser = TeamUser::where('user_id',$ptcuser_id)->where('team_id',$team_id)->where('team_position_id',$position->id)->first();
                            if(!$teamUser) {
                                TeamUser::create([
                                    'user_id' => $ptcuser_id,
                                    'team_id' => $team_id,
                                    'team_position_id' => $position->id
                                ]);
                            }
                        }
                    }
                }
            }
            
        }
        return response()->json(['tests' => $tests]);
    }

    public function addingPosition($title, $team_id) {
        $position = TeamPosition::create([
            'team_id' => $team_id,
            'title' => $title,
            'hierarchy_level' => 0 
        ]);
    
        $hierarchyOrder = ['service level manager', 'service owner head', 'service owner', 'team leader', 'ptc', 'agent'];
        $currentLevel = array_search(strtolower($title), array_map('strtolower', $hierarchyOrder)) + 1;
        $teamPositions = TeamPosition::where('team_id', $team_id)->whereIn('title', $hierarchyOrder)->get();
    
        foreach ($teamPositions as $teamPosition) {
            $positionLevel = array_search(strtolower($teamPosition->title), array_map('strtolower', $hierarchyOrder)) + 1;
            $teamPosition->hierarchy_level = $positionLevel;
            $teamPosition->save();
        }
    
        return $position;
    }

    public function roles() {

        $users = User::all();
        $empty = [];
        foreach ($users as $user){
            $roleuser = RoleUser::where('user_id',$user->id)->first();
            if(!$roleuser) {
                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 7,
                    'team_id' => '1'
                ]);
            }
        }
        return response()->json(['empty' => $empty]);
    }


    public function checkDatabaseConnection()
    {
        try {
            DB::connection('mypat')->getPdo();
            return response()->json(DB::connection('mypat')->table('users')->get());
        } catch (\Exception $e) {
            return response()->json(['connected' => false, 'error' => $e->getMessage()]);
        }
    }
    
    
}
