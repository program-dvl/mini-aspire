<?php

namespace Tests\Unit\Http\Customer\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Customer\Loan\LoanController;
use App\Http\Requests\LoanApplicationPostRequest;
use App\Http\Requests\LoanRepaymentPostRequest;
use App\Models\Loan;
use App\Models\User;
use App\Services\Loan\LoanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class LoanControllerTest extends TestCase
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
     * @var App\Models\User
     */
    private $user;

    /**
     * @var App\Http\Controllers\Customer\Loan\LoanController
     */
    private $loanController;

    public function setUp(): void
    {
        parent::setUp();

        $this->loanService = $this->mock(LoanService::class);
        $this->responseHelper = $this->mock(ResponseHelper::class);
        $this->user = $this->mock(User::class);
        $this->loanController = new LoanController(
            $this->loanService,
            $this->responseHelper
        );
    }


    /**
     * Test Apply for loan successfully
     *
     * @return void
     */
    public function test_apply_for_loan_success()
    {
        $payLoad = [
            'amount' => 12000,
            'term' => 5
        ];
        
        $request = new LoanApplicationPostRequest($payLoad);
        $user = new User();
        $user->id = 1;
        Auth::shouldReceive('user')->once()->andReturn($user);
        $payLoad = $request->toArray();
        $payLoad['customer_id'] = 1;
        $this->loanService
            ->shouldReceive('apply')
            ->once()
            ->with($payLoad);

        $this->responseHelper
            ->shouldReceive('success')
            ->once()
            ->with(
                Response::HTTP_CREATED, 
                'loan.success', 
                []
            );

        $response = $this->loanController->apply(
            $request        
        );
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Test get list of loan successfully
     *
     * @return void
     */
    public function test_get_list_of_loan_success()
    {
        $user = new User();
        $user->id = 1;
        Auth::shouldReceive('user')->once()->andReturn($user);
        $loan = new Loan();
        $this->loanService
            ->shouldReceive('list')
            ->once()
            ->with(1)
            ->andReturn($loan);

        $this->responseHelper
            ->shouldReceive('success')
            ->once()
            ->with(
                Response::HTTP_OK, 
                'loan.success_listing', 
                []
            );

        $response = $this->loanController->list();
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Test get loan details, model not found
     *
     * @return void
     */
    public function test_loan_model_not_found()
    {
        $loanId = 1;
        $modelNotFoundException = $this->mock(ModelNotFoundException::class);
        $this->loanService
            ->shouldReceive('details')
            ->once()
            ->with($loanId)
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

        $response = $this->loanController->details($loanId);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Test get loan details successfully
     *
     * @return void
     */
    public function test_loan_get_details_success()
    {
        $loanId = 1;
        $loan = new Loan();
        $this->loanService
            ->shouldReceive('details')
            ->once()
            ->with($loanId)
            ->andReturn($loan);

        $this->responseHelper
            ->shouldReceive('success')
            ->once()
            ->with(
                Response::HTTP_OK, 
                'loan.loan_found', 
                $loan->toArray()
            );

        $response = $this->loanController->details($loanId);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Test get loan repayment successfully
     *
     * @return void
     */
    public function test_loan_repayment_success()
    {
        $payLoad = [
            'loan_id' => 1,
            'schedule_id' => 1,
            'amount' => 12000
        ];
        $request = new LoanRepaymentPostRequest($payLoad);
        $this->loanService
            ->shouldReceive('repayment')
            ->once()
            ->with($request->toArray());

        $this->responseHelper
            ->shouldReceive('success')
            ->once()
            ->with(
                Response::HTTP_OK, 
                'loan.loan_repayment_success', 
                []
            );

        $response = $this->loanController->repayment($request);
        
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
