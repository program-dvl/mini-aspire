<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureLoginControllerTest extends TestCase
{
     /**
     * Test login failed
     *
     * @return void
     */
    public function test_login_failed()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'john@miniaspire.com', 
            'password' => 'password'
        ]);
        $json = '{
            "errors": [
                {
                    "status": 403,
                    "type": "Forbidden",
                    "message": "Unauthorised"
                }
            ]
        }';

        $response
            ->assertStatus(403)
            ->assertJson(json_decode($json, true));
            
    }

    /**
     * Test login success
     *
     * @return void
     */
    public function test_login_successfully()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'dhaval@miniaspire.com', 
            'password' => 'password'
        ]);
 
        $response
            ->assertStatus(200);
    }

    /**
     * Test admin_login failed
     *
     * @return void
     */
    public function test_admin_login_failed()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'john@miniaspire.com', 
            'password' => 'password'
        ]);
        $json = '{
            "errors": [
                {
                    "status": 403,
                    "type": "Forbidden",
                    "message": "Unauthorised"
                }
            ]
        }';
        
        $response
            ->assertStatus(403)
            ->assertJson(json_decode($json, true));
            
    }

    /**
     * Test admin login success
     *
     * @return void
     */
    public function test_admin_login_successfully()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@miniaspire.com', 
            'password' => 'password'
        ]);
 
        $response
            ->assertStatus(200);
    }
}
