<?php

namespace Tests\Unit\Http\Admin\Auth;

use App\Enums\LoanRepaymentScheduleStatusEnum;
use App\Enums\LoanStatusEnum;
use App\Models\Loan;
use App\Models\RepaymentSchedule;
use App\Repositories\Loan\LoanRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class LoanRepositoryTest extends TestCase
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
     * @var App\Repositories\Loan\LoanRepository
     */
    private $loanRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->loan = $this->mock(Loan::class);
        $this->repaymentSchedule = $this->mock(RepaymentSchedule::class);
        $this->loanRepository = new LoanRepository(
            $this->loan,
            $this->repaymentSchedule
        );
    }


    /**
     * Test apply for loan success
     *
     * @return void
     */
    public function test_loan_apply_success()
    {
        $payLoad = [
            'status' => LoanStatusEnum::approved->name,
            'amount' => 12000,
            'term' => 12
        ];
        $loan = new Loan();
        $this->loan
            ->shouldReceive('create')
            ->once()
            ->with($payLoad)
            ->andReturn($loan);

        $response = $this->loanRepository->apply(
            $payLoad
        );
        
        $this->assertInstanceOf(Loan::class, $response);
    }
    
    /**
     * Test get list of loans succesfully
     *
     * @return void
     */
    public function test_loan_get_list_success()
    {
        $customerId = 1;
        $loan = new Loan();
        $payLoad['customer_id'] = $customerId;
        $this->loan
            ->shouldReceive('where')
            ->once()
            ->with($payLoad)
            ->andReturnSelf()
            ->shouldReceive('get')
            ->with()
            ->andReturn($loan);

        $response = $this->loanRepository->list(
            $customerId
        );
        
        $this->assertInstanceOf(Loan::class, $response);
    }

    // /**
    //  * Test get loan details
    //  *
    //  * @return void
    //  */
    // public function test_loan_get_details_success()
    // {
    //     $loanId = 1;
    //     $loan = new Loan();
    //     $this->loan
    //         ->shouldReceive('with')
    //         ->once()
    //         ->with('paymentSchedule')
    //         ->andReturnSelf()
    //         ->shouldReceive('findOrFail')
    //         ->with($loanId)
    //         ->andReturn($loan);

    //     $response = $this->loanRepository->details(
    //         $loanId
    //     );
        
    //     $this->assertInstanceOf(Loan::class, $response);
    // }

    /**
     * Test update a loan data succesfully
     *
     * @return void
     */
    public function test_loan_update_success()
    {
        $loanId = 1;
        $payLoad = [
            'status' => LoanStatusEnum::approved->name
        ];
        
        $this->loan
            ->shouldReceive('findOrFail')
            ->with($loanId)
            ->andReturnSelf()
            ->shouldReceive('update')
            ->with($payLoad)
            ->andReturn(true);

        $response = $this->loanRepository->update(
            $payLoad, $loanId
        );
        
        $this->assertTrue($response);
    }

    /**
     * Test repayment loan emi successfully
     *
     * @return void
     */
    public function test_loan_repay_emi_success()
    {
        $scheduleId = 1;
        $schedule = new RepaymentSchedule();
        $payLoad = ['status' => LoanRepaymentScheduleStatusEnum::paid];
        $this->repaymentSchedule
            ->shouldReceive('find')
            ->with($scheduleId)
            ->andReturnSelf()
            ->shouldReceive('update')
            ->with($payLoad)
            ->andReturn(true);

        $response = $this->loanRepository->repaymentSchedule(
            $scheduleId
        );
        
        $this->assertTrue($response);
    }

    /**
     * Test check loans pending schedules
     *
     * @return void
     */
    public function test_check_for_pending_loan_schedules_success()
    {
        $loanId = 1;
        $this->repaymentSchedule
            ->shouldReceive('where')
            ->with('loan_id', $loanId)
            ->andReturnSelf()
            ->shouldReceive('whereNot')
            ->with('status', LoanRepaymentScheduleStatusEnum::paid)
            ->andReturnSelf()
            ->shouldReceive('count')
            ->with()
            ->andReturn(1);

        $response = $this->loanRepository->checkPendingSchedules(
            $loanId
        );
        
        $this->assertSame(1, $response);
    }

     /**
     * Test for make loan paid
     *
     * @return void
     */
    public function test_make_loan_paid_suceess()
    {
        $loanId = 1;
        $payload = ['status' => LoanStatusEnum::paid];
        $this->loan
            ->shouldReceive('find')
            ->with($loanId)
            ->andReturnSelf()
            ->shouldReceive('update')
            ->with($payload)
            ->andReturn(null);

        $response = $this->loanRepository->makeLoanPaid(
            $loanId
        );
        
        $this->assertNull($response);
    }

    /**
     * Mock an object
     *
     * @param string name
     *
     * @return Mockery
     */
    private function mock($class)
    {
        return Mockery::mock($class);
    }
}
