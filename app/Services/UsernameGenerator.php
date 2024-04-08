<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UsernameGenerator
{
    public static function generateUniqueUsername($firstname, $lastname, $middlename = null, $user_id = null)
    {
        $user = "";
        if($user_id != null) {
            $user = User::find($user_id);
        }
        $baseUsername = Str::lower(substr($firstname, 0, 1) . $lastname);
        if($user_id != null) {
            if($user->username === $baseUsername){
                return $baseUsername;
            } else if(!User::where('username', $baseUsername)->exists()){
                return $baseUsername;
            }
        } else if (!User::where('username', $baseUsername)->exists()) {
            return $baseUsername;
        }

        $nameParts = explode(' ', $firstname);
        if (count($nameParts) === 2) {
            $secondUsername = Str::lower(substr($nameParts[1], 0, 1) . $lastname);
            $thirdUsername = Str::lower(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1) . $lastname);
            $usernamesToCheck = [$secondUsername, $thirdUsername];
            foreach ($usernamesToCheck as $usernameToCheck) {
                if ($user_id !== null) {
                    if ($user->username === $usernameToCheck) {
                        return $usernameToCheck;
                    } elseif (!User::where('username', $usernameToCheck)->exists()) {
                        return $usernameToCheck;
                    }
                } elseif (!User::where('username', $usernameToCheck)->exists()) {
                    return $usernameToCheck;
                }
            }
        }

        $fourthUsername = Str::lower(substr($firstname, 0, 1) . substr($middlename, 0, 1) . $lastname);
        if($user_id != null) {
            if($user->username === $fourthUsername){
                return $fourthUsername;
            } else if(!User::where('username', $fourthUsername)->exists()){
                return $fourthUsername;
            }
        } else if (!User::where('username', $fourthUsername)->exists()) {
            return $fourthUsername;
        }

        $fifthUsername = $baseUsername . rand(10, 99);
        if($user_id != null) {
            if($user->username === $fifthUsername){
                return $fifthUsername;
            } else if(!User::where('username', $fifthUsername)->exists()){
                return $fifthUsername;
            }
        } else if (!User::where('username', $fifthUsername)->exists()) {
            return $fifthUsername;
        }

        return $baseUsername . Str::random(5);
    }
}