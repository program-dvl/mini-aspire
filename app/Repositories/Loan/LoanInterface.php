<?php
namespace App\Repositories\Loan;

interface LoanInterface
{
    /**
     * Apply for the loan, storing loan data
     *
     * @param array $data
     * @return array
     */
    public function apply(array $data);

     /**
     * Get a list of loans
     *
     * @param int $customerId
     * @return array
     */
    public function list(int $customerId);

}
