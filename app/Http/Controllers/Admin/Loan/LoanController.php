<?php

namespace App\Http\Controllers\Admin\Loan;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanApplicationPatchRequest;
use App\Services\Loan\LoanService;
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
     * Update a loan api ( loan status )
     *
     * @param  LoanApplicationPatchRequest $request
     * @param  int $loanId
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function update(LoanApplicationPatchRequest $request, int $loanId): JsonResponse
    {
        try {
            $this->loanService->update($request->toArray(), $loanId);
            return $this->responseHelper->success(
                Response::HTTP_OK, 
                'loan.update_success', 
                []
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
}
