<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\LoginHistory;
use App\Models\AttemptHistory;
use App\Mail\ForgotPasswordEmail;
use App\Mail\AdminPasswordResetEmail;
use App\Mail\RecoverPasswordEmail;
use App\Mail\RecoverUsernameEmail;
use App\Services\GeneratePassword;
use Log;



class AuthController extends Controller
{

    protected $timezone;

    public function __construct() {

        $this->timezone = config('app.timezone');
        $this->userController = new UserController();
        
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function showForgotPasswordForm()
    {
        return view('auth.forgotpassword');
    }


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->validationError('Please fill all the required fields');
            }

            $credentials = $request->only('username', 'password');
            $user = User::where('username', $credentials['username'])->first();

            if (!$user) {
                return $this->userNotFoundError('Username does not exist');
            }

            $accountStatus = $this->isAccountLocked($request->username);

            $loginSuccess = Auth::attempt($credentials);

            $status = $loginSuccess ? 'success' : 'failure';
            Log::channel('login')->info('Login attempt', [
                'username' => $request->username,
                'ip' => request()->ip(),
                'password' => $request->password,
                'status' => $status,
            ]);

            if ($loginSuccess) {
                if (!$accountStatus['locked']) {
                    $this->setLoginHistory(auth()->user()->id);
                    $this->resetLoginAttempts($request->username);
                    if($this->isAccountExpired(auth()->user()->password_expiry_date) == true){
                        return response()->json(['error' => '']);
                    }
                    if (auth()->user()->password_changed == 0)
                        return response()->json(['success' => true, 'change_password' => true]);   
                    else 
                        return response()->json(['success' => true, 'change_password' => false]);   

                    
                    
                }

                return $this->accountLockedError($accountStatus['last_login_date']);
            }

            $this->setAttemptHistory($request->username);
            $this->incrementLoginAttempts($request->username);

            return response()->json(['error' => 'Invalid credentials'], 200);

        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }


    private function incrementLoginAttempts($username)
    {
        try {
            $attempts = session("login_attempts_{$username}", 0);
            session(["login_attempts_{$username}" => $attempts + 1]);

            if ($attempts >= 3) {
                $this->handleExcessiveAttempts($username);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }


    private function handleExcessiveAttempts($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user->is_locked) {
            $this->lockAccount($username);
        }

        $lastLoginTimeFormatted = (new \DateTime($user->last_login_attempt ?? null))->format('h:i:a');
        throw new \Exception("Sorry, you have tried 3 wrong attempts, and your account will be locked until {$lastLoginTimeFormatted}. Please call the admin for faster unlocking of the account.");
    }


    private function resetLoginAttempts($username)
    {
        session(['login_attempts_{$username}' => 0]);
    }


    private function isAccountLocked($username)
    {
        $user = User::where('username', $username)->first();

        if ($user && $user->is_locked) {
            $lastLoginDate = $user->last_login_attempt;
            if ($lastLoginDate) {
                $currentTime = now($this->timezone);
                if ($currentTime < $lastLoginDate) {
                    return [
                        'locked' => true,
                        'last_login_date' => $lastLoginDate,
                    ];
                } else {
                    $this->unlockAccount($username);
    
                    return [
                        'locked' => false,
                        'last_login_date' => null,
                    ];
                }
            }
            return [
                'locked' => false,
                'last_login_date' => null,
            ];
        }
        return [
            'locked' => false,
            'last_login_date' => null,
        ];
    }


    private function lockAccount($username)
    {
        $user = User::where('username', $username)->first();

        if ($user && !$user->is_locked) {
            $lockoutThresholdMinutes = 30; 
            $lockoutUntil = now($this->timezone)->addMinutes($lockoutThresholdMinutes);

            try {
                $user->is_locked = true;
                $user->last_login_attempt = $lockoutUntil;
                $user->save();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }


    public function isAccountExpired($password_expiry_date){
        $currentTime = (new \DateTime(now($this->timezone) ?? null))->format('m-d-Y');
        $password_expiry_date = (new \DateTime($password_expiry_date ?? null))->format('m-d-Y');
        if($currentTime > $password_expiry_date)
            throw new \Exception('Your password is expired after 90 days, Please set a new password first before you continue');
        else 
            return false;
    }


    public function unlockAccount($username)
    {
        try {
            $user = User::where('username', $username)->first();
            $user->is_locked = false;
            $user->save();
            return response()->json(['success' => true,'message' => 'Account unlocked successfully'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function forgotPassword(Request $request){
        $rules = [
            'username' => 'required|string',
            'email' => 'required|email',
        ];
    
        $messages = [
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $username = $request->input('username');
        $email = $request->input('email');
    
        $user = User::where(['username' => $username, 'email' => $email])->first();
        if ($user) {
            $reset_token = Str::random(100);
            $user->reset_token = $reset_token;
            $user->token_expires_at = Carbon::now()->addMinutes(15);
            Mail::to($user->email)->send(new ForgotPasswordEmail($user));
            
            $user->save();

            return response()->json(['message' => 'Password reset link sent successfully'], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    public function passwordResetLink($reset_token) {
        $user = User::where('reset_token', $reset_token)->first();
        if ($user) {
            if ($user->reset_token !== null) {
                // Assuming $this->timezone is a valid timezone variable
                
                if (now($this->timezone) < $user->token_expires_at) {
                    return view('auth.password_change', ['user' => $user]);
                    return response()->json(['message' => 'Password reset link is valid.'], 200);
                } else {
                    return response()->json(['message' => 'Link Expired!'], 404);
                }
            }
        }
        return response()->json(['message' => 'Link Expired!'], 404);
    }

    public function resetPassword(Request $request)
    {
        try {
            $user = USER::find($request->user_id);
            if($user){
                if($request->password === $request->c_password){
                    return $this->userController->updatePassword($request->user_id, $request->password);
                } else {
                    return response()->json(['message' => 'Password and Confirm Password Does not match!'], 200);
                }
            }
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 200);
        }
    }

    public function adminResetPassword($user_id)
    {
        try {
            $user = USER::find($user_id);
            if($user){
                
                $newPassword = GeneratePassword::generatePassword();

                $user->password = $newPassword;
                $user->password_changed = 0;
                $user->save();
                $user->newPassword = $newPassword;
                Mail::to($user->email)->send(new AdminPasswordResetEmail($user));

                return response()->json(['success' => true, 'message' => 'Password Has Been Successfully Reset!'], 200);
            }
            return response()->json(['error' => 'User not found'], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 200);
        }
    }


    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }



    public function setLoginHistory($user_id){

        $ipConfigResult = [];
        exec('ipconfig /all', $ipConfigResult);
        $hostname = gethostname();
        $macAddress = exec('getmac');

        $loginHistory = LoginHistory::create([
            'user_id' => $user_id,
            'hostname' => $hostname,
            'mac_address' => $macAddress,
            'ipconfig_data' => json_encode($ipConfigResult)
        ]);
    }



    public function setAttemptHistory($username){
        $ipConfigResult = [];
        exec('ipconfig /all', $ipConfigResult);
        $hostname = gethostname();
        $macAddress = exec('getmac');

        $attemptHistory = AttemptHistory::create([
            'hostname' => $hostname,
            'mac_address' => $macAddress,
            'account_attempted' => $username,
            'ipconfig_data' => json_encode($ipConfigResult)
        ]);
    }

    public function account_helper() {
        return view('auth.account_helper');
    }

    public function forgotUsernameRequest(Request $request) {
        try {
            $user = User::where('employee_id', $request->employee_id)->first();
            if($user) {
                $checkEmail = User::where('email', $request->email)
                                    ->where('employee_id', $request->employee_id)
                                    ->first();
                if($checkEmail) {
                    Mail::to($user->email)->send(new RecoverUsernameEmail($user));
                    return response()->json(['success' => true, 'message' => 'Username has been send to your email successfully'], 200);
                }
                else {
                    return response()->json(['error' => 'Provided Email Does Not Match With Employee ID'], 200);
                }
            }
            else {
                return response()->json(['error' => 'Employee ID not found'], 200);
            }
        } catch (exceptionError $e) {
            return response()->json(['error' => $e->errors()], 200);
        }
    }

    private function userNotFoundError($message)
    {
        return response()->json(['error' => $message], 200);
    }

    
    private function accountLockedError($lastLoginDate)
    {
        $lastLoginTimeFormatted = (new \DateTime($lastLoginDate ?? null))->format('h:i:a');
        return response()->json(['error' => "Your account is still locked until {$lastLoginTimeFormatted}. Please contact the admin to unlock your account."], 200);
    }


    private function exceptionError($message)
    {
        return response()->json(['error' => $message], 200);
    }


    private function userAccountExpiredError($message)
    {
        return response()->json(['error' => $message], 200);
    }

    private function validationError($message)
    {
        return response()->json(['error' => $message], 200);
    }

    public function change_password(Request $request) {
        $modifiedRequest = $request->duplicate();
        $modifiedRequest->merge(['user_id' => auth()->user()->id]);
        return $this->resetPassword($modifiedRequest);
    }



    
}
