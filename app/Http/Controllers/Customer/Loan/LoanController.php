<?php

namespace App\Http\Controllers\Customer\Loan;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanApplicationPostRequest;
use App\Http\Requests\LoanRepaymentPostRequest;
use App\Services\Loan\LoanService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LoanController extends Controller
{
     /**
     * @var App\Services\Loan\LoanService
     */
    public $loanService;

    /**
     * @var App\Helpers\ResponseHelper
     */
    public $responseHelper;

    /**
     * Create a new Loan Controller instance.
     *
     * @param  App\Services\Loan\LoanService $loanService
     * @param  App\Helpers\ResponseHelper $responseHelper
     * @return void
     */
    public function __construct(
        LoanService $loanService,
        ResponseHelper $responseHelper
    )
    {
        $this->loanService = $loanService;
        $this->responseHelper = $responseHelper;
    }

     /**
     * Apply For Loan api
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function apply(LoanApplicationPostRequest $request): JsonResponse
    {
        $payload = $request->toArray();
        $payload['customer_id'] = Auth::user()->id;
        
        $this->loanService->apply($payload);

        return $this->responseHelper->success(
            Response::HTTP_CREATED, 
            'loan.success', 
            []
        );
    }

     /**
     * API to gey list of loans
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function list(): JsonResponse
    {
        $customerId = Auth::user()->id;
        $list = $this->loanService->list($customerId);
        return $this->responseHelper->success(
            Response::HTTP_OK, 
            'loan.success_listing', 
            $list->toArray()
        );
    }

     /**
     * Apply to get specific loan details
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function details(int $id): JsonResponse
    {
        try {
            $loan = $this->loanService->details($id);
            return $this->responseHelper->success(
                Response::HTTP_OK, 
                'loan.loan_found', 
                $loan->toArray()
            );
        } catch (ModelNotFoundException $e) {
            return $this->responseHelper->error(
                Response::HTTP_NOT_FOUND,
                Response::$statusTexts[Response::HTTP_NOT_FOUND],
                false,
                'loan.loan_not_found'
            );
        }
    }

     /**
     * Repayment of EMI
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function repayment(LoanRepaymentPostRequest $request): JsonResponse
    {
        $this->loanService->repayment($request->toArray());
        return $this->responseHelper->success(
            Response::HTTP_OK, 
            'loan.loan_repayment_success', 
            []
        );
    }
    
}
