<?php

namespace App\Http\Controllers\Customer\Loan;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanApplicationPostRequest;
use App\Services\Loan\LoanService;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * Apply For Loan api
     *
     * @return \Illuminate\Http\Response
     */
    public function apply(LoanApplicationPostRequest $request)
    {
        $payload = $request->toArray();
        $payload['customer_id'] = Auth::user()->id;
        $this->loanService->apply($payload);

        return $this->sendResponse([], trans('loan.success'), Response::HTTP_CREATED);
    }

     /**
     * API to gey list of loans
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $customerId = Auth::user()->id;
        $list = $this->loanService->list($customerId);
        return $this->sendResponse($list, trans('loan.success_listing'), Response::HTTP_OK);
    }

     /**
     * Apply to get specific loan details
     *
     * @return \Illuminate\Http\Response
     */
    public function details(int $id)
    {
        try {
            $loan = $this->loanService->details($id);
            return $this->sendResponse($loan, trans('loan.loan_found'), Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return $this->sendError(
                trans('loan.loan_not_found'), 
                [],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
