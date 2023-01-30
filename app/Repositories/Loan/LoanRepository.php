<?php
namespace App\Repositories\Loan;

use App\Enums\LoanRepaymentScheduleStatusEnum;
use App\Enums\LoanStatusEnum;
use App\Models\Loan;
use App\Models\RepaymentSchedule;

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
    public function details(int $loanId) {
        return $this->loan->with('paymentSchedule')->findOrFail($loanId);
    }

     /**
     * Update a loan data
     *
     * @param array $payload
     * @param int $loanId
     * @return void
     */
    public function update(array $payload, int $loanId) {
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

     /**
     * Repay loan EMI
     *
     * @param int $scheduleId
     * @return void
     */
    public function repaymentSchedule($scheduleId) {
        $loan = $this->repaymentSchedule->find($scheduleId);
        $payload = ['status' => LoanRepaymentScheduleStatusEnum::paid];
        return $loan->update($payload);
    }

     /**
     * Get the count of pending schedules of loan
     *
     * @param int $loanId
     * @return void
     */
    public function checkPendingSchedules($loanId) {
        return $this->repaymentSchedule
            ->where('loan_id', $loanId)
            ->whereNot('status', LoanRepaymentScheduleStatusEnum::paid)->count();
    }

     /**
     * Make a loan status to paid
     *
     * @param int $loanId
     * @return void
     */
    public function makeLoanPaid($loanId) {
        $loan = $this->loan->find($loanId);
        $payload = ['status' => LoanStatusEnum::paid];
        $loan->update($payload);
    }

}
