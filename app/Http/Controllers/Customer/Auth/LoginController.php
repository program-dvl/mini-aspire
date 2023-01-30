<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Helpers\ResponseHelper;
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
     * @var App\Helpers\ResponseHelper
     */
    public $responseHelper;

    /**
     * Create a new Login Controller instance.
     *
     * @param  App\Services\Auth\AuthService $authService
     * @param  App\Helpers\ResponseHelper $responseHelper
     * @return void
     */
    public function __construct(
        AuthService $authService,
        ResponseHelper $responseHelper
    )
    {
        $this->authService = $authService;
        $this->responseHelper = $responseHelper;
    }

    /**
     * Customer Login api
     *
     * @param  LoginPostRequest $request
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function login(LoginPostRequest $request): JsonResponse
    {
        $checkLogin = $this->authService->checkLogin(
            $request->email, 
            $request->password
        );

        if (!$checkLogin) {
            return $this->responseHelper->error(
                Response::HTTP_FORBIDDEN,
                Response::$statusTexts[Response::HTTP_FORBIDDEN],
                false,
                'auth.unauthorised'
            );
        } else {
            return $this->responseHelper->success(
                Response::HTTP_OK, 
                'auth.login_success', 
                $checkLogin
            );
        } 
    }
}
