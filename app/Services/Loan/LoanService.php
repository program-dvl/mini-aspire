<?php

namespace App\Services\Loan;

use App\Repositories\Loan\LoanRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;

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
        $loan = $this->loanRepository->apply($payload);
        $schedules = $this->generateRepaymentSchedule($payload['amount'], $payload['term']);
        $mappedData = Arr::map($schedules, function ($value) use ($loan) {
            $value['created_at'] = Carbon::now()->toDateTimeString();
            $value['loan_id'] = $loan->id;
            return $value;
        });
        $this->loanRepository->savePaymentReschedule($mappedData);
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

    /**
     * Update a loan data
     * 
     * @param array $payload
     * @param int $loanId
     * @return void
     */
    public function update(array $payload, int $loanId)
    {
        return $this->loanRepository->update($payload, $loanId);
    }

    /**
     * Generate repayment schedule
     * 
     * @param float $amount
     * @param int $term
     * @return array
     */
    public function generateRepaymentSchedule($amount, $term) {
        $emiSchedule = [];
        $emiAmount = round($amount / $term, 2);
        for ($i=1; $i <= $term; $i++) { 
            $emiDate = Carbon::now()->addWeeks($i);
            $emiSchedule[] = [
                'date' => $emiDate->toDateString(),
                'amount' => $emiAmount
            ];
        }
        return $emiSchedule;
    } 
    
    /**
     * Repay loan EMI
     * 
     * @param array $payload
     * @return void
     */
    public function repayment(array $payload)
    {
        $this->loanRepository->repaymentSchedule($payload['schedule_id']);
        $count = $this->loanRepository->checkPendingSchedules($payload['loan_id']);
        if (!$count) {
            $this->loanRepository->makeLoanPaid($payload['loan_id']);
        }
    }
}
