<?php

namespace App\Services;

use App\Models\User;

class GeneratePassword
{
    public static function generatePassword() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $digits = '0123456789';
    
        $randomChars = substr(str_shuffle($characters), 0, 2);
        $randomDigits = self::generateNonSequentialDigits(6);
        $password = $randomChars . $randomDigits;
    
        return $password;
    }

    public static function generateNonSequentialDigits($length) {
        $digits = '0123456789';
    
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $digits[random_int(0, strlen($digits) - 1)];
        }
    
        return $result;
    }
}