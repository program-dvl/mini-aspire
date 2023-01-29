<?php

namespace App\Http\Requests;

use App\Rules\CheckPaymentScheduleAmount;
use App\Rules\CheckRepaymentSchedule;
use Illuminate\Foundation\Http\FormRequest;

class LoanRepaymentPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $loanId = $this->request->get('loan_id');
        $scheduleId = $this->request->get('schedule_id');
        return [
            'loan_id' => 'integer|exists:loans,id,deleted_at,NULL',
            'schedule_id' => [
                'integer',
                'exists:repayment_schedules,id,deleted_at,NULL',
                new CheckRepaymentSchedule($loanId)
            ],
            'amount' => [
                'required',
                'between:0,999999999.99',
                new CheckPaymentScheduleAmount($scheduleId)
            ]
        ];
    }
}
