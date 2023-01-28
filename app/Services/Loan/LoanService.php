<?php

namespace App\Services\Loan;

use App\Repositories\Loan\LoanRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class LoanService
{
    /**
     * @var App\Repositories\Loan\LoanRepository
     */
    private $loanRepository;

    /**
     * Create a new loan service instance.
     *
     * @param  App\Repositories\Loan\LoanRepository $loanRepository
     * @return void
     */
    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

     /**
     * Apply For Loan api
     *
     * @param array $data
     * @return void
     */
    public function apply(array $payload)
    {
        $this->loanRepository->apply($payload);
    }

     /**
     * Get a loan list
     *
     * @param int $customerId
     * @return void
     */
    public function list(int $customerId)
    {
        return $this->loanRepository->list($customerId);
    }

    /**
     * Get a loan details
     *
     * @param int $loanId
     * @return void
     */
    public function details(int $loanId)
    {
        return $this->loanRepository->details($loanId);
    }

    
}
