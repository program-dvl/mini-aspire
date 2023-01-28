<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPostRequest;
use Auth;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginPostRequest $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse(
                $success, 
                trans('auth.login_success'), 
                Response::HTTP_OK
            );
        } 
        else{ 
            return $this->sendError(
                'Unauthorised.', 
                ['error'=> trans('auth.unauthorised')],
                Response::HTTP_FORBIDDEN
            );
        } 
    }
}
