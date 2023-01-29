<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginPostRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    /**
     * @var App\Services\Auth\AuthService
     */
    public $authService;

    /**
     * Create a new Loan Controller instance.
     *
     * @param  App\Services\Auth\AuthService $authService
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Admin Login api
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function login(LoginPostRequest $request): JsonResponse
    {
        $checkLogin = $this->authService->checkAdminLogin($request->email, $request->password);
        if (!$checkLogin) {
            return $this->sendError(
                'Unauthorised.', 
                ['error'=> trans('auth.unauthorised')],
                Response::HTTP_FORBIDDEN
            );
        } else {
            return $this->sendResponse(
                $checkLogin, 
                trans('auth.login_success'), 
                Response::HTTP_OK
            );
        }
    }
}
