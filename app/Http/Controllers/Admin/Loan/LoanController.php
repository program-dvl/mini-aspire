<?php

namespace App\Http\Controllers\Admin\Loan;

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
     * Create a new Loan Controller instance.
     *
     * @param  App\Services\Loan\LoanService $loanService
     * @return void
     */
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

     /**
     * Update a loan api
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function update(LoanApplicationPatchRequest $request, int $loanId): JsonResponse
    {
        try {
            $loan = $this->loanService->update($request->toArray(), $loanId);
            return $this->sendResponse($loan, trans('loan.update_success'), Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->sendError(
                trans('loan.loan_not_found'), 
                [],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
