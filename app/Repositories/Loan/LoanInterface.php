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

    /**
     * Get a loan details
     *
     * @param int $loanId
     * @return void
     */
    public function details(int $loanId);

    /**
     * Update a loan data
     *
     * @param int $customerId
     * @return array
     */
    public function update(array $payload, int $loanId);

    /**
     * Save EMI schedule data
     *
     * @param array $payload
     * @return array
     */
    public function savePaymentReschedule(array $payload);

}
