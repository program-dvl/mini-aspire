<?php

namespace App\Rules;

use App\Models\RepaymentSchedule;
use Illuminate\Contracts\Validation\Rule;

class CheckPaymentScheduleAmount implements Rule
{
    public $scheduleId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
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
        $data = RepaymentSchedule::find($this->scheduleId);
        return (!empty($data) && $value >= $data->amount) ? true : false; 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The amount should be greter than or equal to scheduled payment.';
    }
}
