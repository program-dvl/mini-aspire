<?php

namespace Tests\Unit\Http\Admin\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Requests\LoginPostRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * @var App\Services\Auth\AuthService
     */
    private $authService;

    /**
     * @var App\Helpers\ResponseHelper
     */
    private $responseHelper;

    /**
     * @var App\Http\Controllers\Admin\LoginController
     */
    private $loginController;

    public function setUp(): void
    {
        parent::setUp();

        $this->authService = $this->mock(AuthService::class);
        $this->responseHelper = $this->mock(ResponseHelper::class);
        $this->loginController = new LoginController(
            $this->authService,
            $this->responseHelper
        );
    }


    /**
     * Test login crendetials failed case
     *
     * @return void
     */
    public function test_login_credentials_failed()
    {
        $payLoad = [
            'email' => 'dhaval@testing.com',
            'password' => '123456'
        ];
        $request = new LoginPostRequest($payLoad);
        
        $this->authService
            ->shouldReceive('checkLogin')
            ->once()
            ->with($request->email, $request->password)
            ->andReturn(false);

        $this->responseHelper
            ->shouldReceive('error')
            ->once()
            ->with(
                Response::HTTP_FORBIDDEN,
                Response::$statusTexts[Response::HTTP_FORBIDDEN],
                false,
                'auth.unauthorised'
            );

        $response = $this->loginController->login(
            $request
        );
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Test login successful
     *
     * @return void
     */
    public function test_login_success()
    {
        $payLoad = [
            'email' => 'dhaval@testing.com',
            'password' => '123456'
        ];
        $request = new LoginPostRequest($payLoad);
        
        $checkLogin = [
            'token' => '29|TCcgx36Mf73AJtPguzeCVoeoTEMwbfGyY5ERxgFL',
            'name' => 'test'
        ];

        $this->authService
            ->shouldReceive('checkLogin')
            ->once()
            ->with($request->email, $request->password)
            ->andReturn($checkLogin);

        $this->responseHelper
            ->shouldReceive('success')
            ->once()
            ->with(
                Response::HTTP_OK, 
                'auth.login_success', 
                $checkLogin
            );

        $response = $this->loginController->login(
            $request
        );
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Mock an object
     *
     * @param string name
     *
     * @return Mockery
     */
    private function mock($class)
    {
        return Mockery::mock($class);
    }
}
