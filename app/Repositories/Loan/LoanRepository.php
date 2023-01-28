<?php
namespace App\Repositories\Loan;

use App\Models\Loan;

class LoanRepository implements LoanInterface
{
    /**
     * @var App\Models\Loan
     */
    public $loan;

    /**
     * Create a new Loan repository instance.
     *
     * @param  App\Models\Loan $loan
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Apply for loan
     *
     * @param array $data
     * @return void
     */
    public function apply(array $data)
    {
        return $this->loan->create($data);
    }

    /**
     * Get list of loans
     *
     * @param int $customerId
     * @return void
     */
    public function list(int $customerId)
    {
        $list = $this->loan->where([
            'customer_id' => $customerId
        ])->get();

        return $list;
    }

    /**
     * Get a loan details
     *
     * @param int $loanId
     * @return void
     */
    public function details(int $loanId)
    {
        return $this->loan->findOrFail($loanId);
    }

}
