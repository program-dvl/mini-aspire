<?php

namespace Tests\Unit\Http\Admin\Auth;

use App\Enums\LoanStatusEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Admin\Loan\LoanController;
use App\Http\Requests\LoanApplicationPatchRequest;
use App\Services\Loan\LoanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class AdminLoanControllerTest extends TestCase
{
    /**
     * @var App\Services\Auth\AuthService
     */
    private $loanService;

    /**
     * @var App\Helpers\ResponseHelper
     */
    private $responseHelper;

    /**
     * @var App\Http\Controllers\Admin\Loan\LoanController
     */
    private $loanController;

    public function setUp(): void
    {
        parent::setUp();

        $this->loanService = $this->mock(LoanService::class);
        $this->responseHelper = $this->mock(ResponseHelper::class);
        $this->loanController = new LoanController(
            $this->loanService,
            $this->responseHelper
        );
    }


    /**
     * Test update loan status: loan not found
     *
     * @return void
     */
    public function test_loan_model_not_found()
    {
        $payLoad = [
            'status' => LoanStatusEnum::approved->name
        ];
        $loanId = 1;
        $request = new LoanApplicationPatchRequest($payLoad);
        $modelNotFoundException = $this->mock(ModelNotFoundException::class);
        
        $this->loanService
            ->shouldReceive('update')
            ->once()
            ->with($request->toArray(), $loanId)
            ->andThrow($modelNotFoundException);

        $this->responseHelper
            ->shouldReceive('error')
            ->once()
            ->with(
                Response::HTTP_NOT_FOUND,
                Response::$statusTexts[Response::HTTP_NOT_FOUND],
                false,
                'loan.loan_not_found'
            );

        $response = $this->loanController->update(
            $request, $loanId
        );
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Test update loan status: loan not found
     *
     * @return void
     */
    public function test_loan_update_success()
    {
        $payLoad = [
            'status' => LoanStatusEnum::approved->name
        ];
        $loanId = 1;
        $request = new LoanApplicationPatchRequest($payLoad);
        $loan = [];
        
        $this->loanService
            ->shouldReceive('update')
            ->once()
            ->with($request->toArray(), $loanId)
            ->andReturn($loan);

        $this->responseHelper
            ->shouldReceive('success')
            ->once()
            ->with(
                Response::HTTP_OK, 
                'loan.update_success', 
                $loan
            );

        $response = $this->loanController->update(
            $request, $loanId
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
