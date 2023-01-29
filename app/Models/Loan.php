<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\LoanStatusEnum;
use App\Models\RepaymentSchedule;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'term',
        'status',
        'customer_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => LoanStatusEnum::class,
    ];

    /**
     * Get the document record associated with the mission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentSchedule(): HasMany
    {
        return $this->hasMany(RepaymentSchedule::class, 'loan_id', 'id');
    }
}
