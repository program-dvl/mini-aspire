<?php
namespace App\Repositories\Loan;

use App\Models\Loan;
use App\Models\RepaymentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class LoanRepository implements LoanInterface
{
    /**
     * @var App\Models\Loan
     */
    public $loan;

    /**
     * @var App\Models\RepaymentSchedule
     */
    public $repaymentSchedule;

    /**
     * Create a new Loan repository instance.
     *
     * @param  App\Models\Loan $loan
     * @return void
     */
    public function __construct(
        Loan $loan,
        RepaymentSchedule $repaymentSchedule
    )
    {
        $this->loan = $loan;
        $this->repaymentSchedule = $repaymentSchedule;
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

     /**
     * Update a loan data
     *
     * @param array $data
     * @return void
     */
    public function update(array $payload, int $loanId)
    {
        $loan = $this->loan->findOrFail($loanId);
        return $loan->update($payload);
    }

    /**
     * Save EMI schedule data
     *
     * @param array $schedules
     * @return void
     */
    public function savePaymentReschedule($schedules) {
        $this->repaymentSchedule->insert($schedules);
    }

}
