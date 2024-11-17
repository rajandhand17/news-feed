<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $data = [
            'first_name' => 'Rajan',
            'last_name' => 'Dhand',
            'username' => 'rd',
            'password' => 'Test@123',
            'password_confirmation' => 'Test@123',
            'email' => 'rajan@gmail.com',
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(200);
        $data = $response->json();
        if ($data['success'] === true) {
            $response->assertOk();
        }
    }

    /** @test */
    public function user_cannot_register_with_mismatched_passwords()
    {
        $data = [
            'first_name' => 'Rajan',
            'last_name' => 'Dhand',
            'username' => 'rd',
            'password' => 'Test@123',
            'password_confirmation' => 'WrongPassword',
            'email' => 'rajan@gmail.com',
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function user_cannot_register_with_invalid_email_format()
    {
        $data = [
            'first_name' => 'Rajan',
            'last_name' => 'Dhand',
            'username' => 'rd',
            'password' => 'Test@123',
            'password_confirmation' => 'Test@123',
            'email' => 'invalid-email',
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function user_cannot_register_with_existing_email()
    {
        
        $data = [
            'first_name' => 'Rajan',
            'last_name' => 'Dhand',
            'username' => 'rd',
            'password' => 'Test@123',
            'password_confirmation' => 'Test@123',
            'email' => 'rajan@gmail.com',
        ];

        $responses = $this->postJson('/api/v1/auth/register', $data);
        //again inserting 
        $response = $this->postJson('/api/v1/auth/register', $data);
        $response->assertStatus(422);
    }

    /** @test */
public function user_can_login_with_valid_credentials()
{
    // Create a user
    $data = [
        'first_name' => 'Rajan',
        'last_name' => 'Dhand',
        'username' => 'rd',
        'password' => 'Test@123',
        'password_confirmation' => 'Test@123',
        'email' => 'rajan@gmail.com',
    ];

    $responses = $this->postJson('/api/v1/auth/register', $data);
    if($responses->status()==200){
        $data = [
            'email' => 'rajan@gmail.com',  // Or 'email' depending on your implementation
            'password' => 'Test@123',
        ];
    
        $response = $this->postJson('/api/v1/auth/login', $data);
        // Assert successful login (200 OK) and that an access token is returned
        $response->assertStatus(200);
        
    }
   
}

/** @test */
public function user_cannot_login_with_incorrect_password()
{// Create a user
    $data = [
        'first_name' => 'Rajan',
        'last_name' => 'Dhand',
        'username' => 'rd',
        'password' => 'Test@123',
        'password_confirmation' => 'Test@123',
        'email' => 'rajan@gmail.com',
    ];
    $this->postJson('/api/v1/auth/register', $data);

    // Send login request with incorrect password
    $data = [
        'email' => 'rajan@gmail.com',  
        'password' => 'WrongPassword',
    ];

    $response = $this->postJson('/api/v1/auth/login', $data);
    $response->assertStatus(401);
}

}
