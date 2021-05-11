<?php

namespace \Tests\Feature\App\Api\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private $userData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userData = [
            'name' => 'Fabio',
            'email' => Uuid::uuid4() . '@fabiofarias.com.br',
            'password' => '123456',
            'mobile_phone' => '219992220000'
        ];
    }

    public function testRegister()
    {
        $response = $this->post('/v1/auth/register', $this->userData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'name',
            'email',
            'mobile_phone'
        ]);

        return $this->userData;
    }

    /**
     * @depends testRegister
     */
    public function testExistUser($userData)
    {
        $response = $this->post('/v1/auth/register', $userData);
        $response->assertStatus(422);
    }

    /**
     * @depends testRegister
     */
    public function testLogin($userData)
    {
        $userLogin = [
            'email' => $userData['email'],
            'password' => $userData['password']
        ];

        $response = $this->post('/v1/auth/login', $userLogin);
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);

        return json_decode($response->getContent());
    }

    /**
     * @depends testLogin
     */
    public function testMe($token)
    {
        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token->access_token
        )->get('/v1/auth/me');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email'
            ]
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userData);
    }
}
