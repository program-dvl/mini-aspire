<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPostRequest;
use Auth;
use Lang;

class LoginController extends Controller
{
    /**
     * Admin Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginPostRequest $request)
    {
        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::guard('admin')->user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, Lang::get('auth.login_success'));
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=> Lang::get('auth.unauthorised')]);
        } 
    }
}
