<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Admin;
use App\Models\Loan;
use App\Models\RepaymentSchedule;
use App\Models\User;
use Tests\TestCase;

class FeatureLoanControllerTest extends TestCase
{
     /**
     * Test Update loan status: success
     *
     * @return void
     */
    public function test_update_loan_status_by_admin_success()
    {
        $loginResponse = $this->postJson('/api/admin/login', [
            'email' => 'admin@miniaspire.com', 
            'password' => 'password'
        ]);
        $loginResponse = json_decode($loginResponse->content());
        
        $user = Admin::first();
        $loan = new Loan();
        $loan->create([
            'amount' => 10000,
            'term' => 1,
            'customer_id' => $user->id
        ]);
        $lastLoan = Loan::orderBy('created_at', 'desc')->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$loginResponse->data->token,
        ])->patchJson('/api/admin/loan/'.$lastLoan->id, [
            'status' => 'approved',
        ]);

        $json = '{
            "status": 200,
            "message": "Loan data updated successfully."
        }';
        
        $response
            ->assertStatus(200)
            ->assertJson(json_decode($json, true));
            
    }

    /**
     * Test Update loan status: loan model not found
     *
     * @return void
     */
    public function test_update_loan_status_by_admin_loan_not_found()
    {
        $loginResponse = $this->postJson('/api/admin/login', [
            'email' => 'admin@miniaspire.com', 
            'password' => 'password'
        ]);
        $loginResponse = json_decode($loginResponse->content());

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$loginResponse->data->token,
        ])->patchJson('/api/admin/loan/'.rand(10000,100000), [
            'status' => 'approved',
        ]);

        $json = '{
            "errors": [
                {
                    "status": 404,
                    "type": "Not Found",
                    "message": "Loan data not found."
                }
            ]
        }';
        
        $response
            ->assertStatus(404)
            ->assertJson(json_decode($json, true));
            
    }

     /**
     * Test apply loan: success
     *
     * @return void
     */
    public function test_apply_for_loan_success()
    {
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'dhaval@miniaspire.com', 
            'password' => 'password'
        ]);
        $loginResponse = json_decode($loginResponse->content());

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$loginResponse->data->token,
        ])->postJson('/api/loan/apply/', [
            'amount' => '1000',
            'term' => 3
        ]);

        $json = '{"status":201,"message":"Applied for loan successfully."}';
        
        $response
            ->assertStatus(201)
            ->assertJson(json_decode($json, true));
            
    }

     /**
     * Test get loan details: success
     *
     * @return void
     */
    public function test_get_details_of_loan_success()
    {
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'dhaval@miniaspire.com', 
            'password' => 'password'
        ]);
        $loginResponse = json_decode($loginResponse->content());
        
        $user = User::first();
        $loan = new Loan();
        $loan->create([
            'amount' => 10000,
            'term' => 1,
            'customer_id' => $user->id
        ]);
        $lastLoan = Loan::orderBy('created_at', 'desc')->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$loginResponse->data->token,
        ])->getJson('/api/loan/'.$lastLoan->id, [
            'amount' => '1000',
            'term' => 3
        ]);
        
        $response->assertStatus(200);
            
    }

    /**
     * Test get loan details: model not found
     *
     * @return void
     */
    public function test_get_details_of_loan_model_not_found()
    {
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'dhaval@miniaspire.com', 
            'password' => 'password'
        ]);
        $loginResponse = json_decode($loginResponse->content());
        
        $user = User::first();
        $loan = new Loan();
        $loan->create([
            'amount' => 10000,
            'term' => 1,
            'customer_id' => $user->id
        ]);
        $lastLoan = Loan::orderBy('created_at', 'desc')->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$loginResponse->data->token,
        ])->getJson('/api/loan/'.rand(), [
            'amount' => '1000',
            'term' => 3
        ]);
        
        $json = '{
            "errors": [
                {
                    "status": 404,
                    "type": "Not Found",
                    "message": "Loan data not found."
                }
            ]
        }';
        
        $response
            ->assertStatus(404)
            ->assertJson(json_decode($json, true));
            
    }

    /**
     * Test loan repayment: success
     *
     * @return void
     */
    public function test_loan_repayment_success()
    {
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'dhaval@miniaspire.com', 
            'password' => 'password'
        ]);
        $loginResponse = json_decode($loginResponse->content());

        $this->withHeaders([
            'Authorization' => 'Bearer '.$loginResponse->data->token,
        ])->postJson('/api/loan/apply/', [
            'amount' => '1000',
            'term' => 3
        ]);
        
        $lastLoan = Loan::orderBy('id', 'desc')->first();
        $lastSchedule = RepaymentSchedule::orderBy('id', 'desc')->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$loginResponse->data->token,
        ])->postJson('/api/loan/repayment', [
            'loan_id' => $lastLoan->id,
            'schedule_id' => $lastSchedule->id,
            'amount' => 4000
        ]);
        
        $json = '{
            "status": 200,
            "message": "Loan repayment paid successfully."
        }';
        
        $response
            ->assertStatus(200)
            ->assertJson(json_decode($json, true));
            
    }

}
