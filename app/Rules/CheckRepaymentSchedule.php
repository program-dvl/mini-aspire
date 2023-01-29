<?php

namespace App\Rules;

use App\Models\RepaymentSchedule;
use Illuminate\Contracts\Validation\Rule;

class CheckRepaymentSchedule implements Rule
{
    public $loanId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($loanId)
    {
        $this->loanId = $loanId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return RepaymentSchedule::where(
            [
                'id' => $value,
                'loan_id' => $this->loanId
            ]
        )->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please pass valid reschedule payment id of selected loan.';
    }
}
