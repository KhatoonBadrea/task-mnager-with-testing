<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
   
    use DatabaseTransactions;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->authService = new AuthService();
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->authService->login($credentials);

        $this->assertArrayHasKey('access_token', $response);
        $this->assertEquals($response['token_type'], 'bearer');
        $this->assertEquals($response['user']->email, $user->email);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role_id' => Role::where('name', 'Developer')->first()->id,
        ]);
    
        $invalidCredentials = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];
    
        $response = $this->authService->login($invalidCredentials);
    
        $this->assertIsArray($response);
        $this->assertEquals('Unauthorized', $response['message']);
    }
    
}
