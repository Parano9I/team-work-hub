<?php

namespace TeamWorkHub\Modules\Auth\Tests\Feature;

use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use TeamWorkHub\Modules\Auth\Models\Account;
use TeamWorkHub\Modules\Auth\Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private string $routeName = 'api.v1.auth.login';

    private Account $account;

    private array $credentials = [
        'email'    => 'test_email123@gmail.com',
        'password' => 'password12',
        'nickname' => 'testUser123'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([RolesSeeder::class]);

        $this->account = Account::factory()->create([
            'email'    => $this->credentials['email'],
            'nickname' => $this->credentials['nickname'],
            'password' => Hash::make($this->credentials['password'])
        ]);
    }

    public function test_success_by_email()
    {
        $response = $this->postJson(route($this->routeName), [
            'identifier' => $this->credentials['email'],
            'password'   => $this->credentials['password']
        ]);

        $response->assertOk()->assertJson([
            'data' => [
                'account' => [
                    'id'            => $this->account->id,
                    'first_name'    => $this->account->first_name,
                    'last_name'     => $this->account->last_name,
                    'nickname'      => $this->account->nickname,
                    'email'         => $this->account->email,
                    'date_of_birth' => $this->account->date_of_birth->toDateString(),
                    'avatar'        => null
                ],
                'token'   => [
                    'type'    => 'Bearer',
                    'payload' => $response['data']['token']['payload']
                ]
            ]
        ]);
    }

    public function test_success_by_nickname()
    {
        $response = $this->postJson(route($this->routeName), [
            'identifier' => $this->credentials['nickname'],
            'password'   => $this->credentials['password']
        ]);

        $response->assertOk()->assertJson([
            'data' => [
                'account' => [
                    'id'            => $this->account->id,
                    'first_name'    => $this->account->first_name,
                    'last_name'     => $this->account->last_name,
                    'nickname'      => $this->account->nickname,
                    'email'         => $this->account->email,
                    'date_of_birth' => $this->account->date_of_birth->toDateString(),
                    'avatar'        => null
                ],
                'token'   => [
                    'type'    => 'Bearer',
                    'payload' => $response['data']['token']['payload']
                ]
            ]
        ]);
    }

    public function test_invalid_credentials()
    {
        $response = $this->postJson(route($this->routeName), [
            'identifier' => $this->credentials['nickname'],
            'password'   => 'invalidPassword'
        ]);

        $response->assertUnauthorized()->assertJson([
            'message' => 'Sorry! You have entered invalid credentials.'
        ]);
    }
}
