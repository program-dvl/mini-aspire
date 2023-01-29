<?php

namespace App\Services\Auth;

use Auth;

class AuthService
{
     /**
     * Check user login
     *
     * @param string $email
     * @param string $password
     * @return void|array
     */
    public function checkAdminLogin(string $email, string $password)
    {
        if(Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])){
            $user = Auth::guard('admin')->user(); 
            $success['token'] = $user->createToken('MyApp')->plainTextToken; 
            $success['name'] = $user->name;
            return $success;
        } else {
            return false;
        }
    }

     /**
     * Check user login
     *
     * @param string $email
     * @param string $password
     * @return void|array
     */
    public function checkLogin(string $email, string $password)
    {
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            $user = Auth::user(); 
            $success['token'] = $user->createToken('MyApp')->plainTextToken; 
            $success['name'] = $user->name;
            return $success;
        } else {
            return false;
        }
    }
}