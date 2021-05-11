<?php

namespace \Tests\Feature\User;

use Tests\TestCase;

class GetUserProfileTest extends TestCase
{
    private $token;
    private $expectedDataUser;

    protected function setUp(): void
    {
        parent::setUp();

        $dataFile = base_path('tests/Feature/data.json');
        $contentFile = file_get_contents($dataFile);
        $dataJson = json_decode($contentFile);

        $this->token = $dataJson->token;
        $this->expectedDataUser = [
            'name',
            'email',
            'trade_name',
            'is_affiliate',
            'is_producer',
            'allow_showcase',
            'active',
            'document_number',
            'notes',
            'dob',
            'website',
            'about',
            'rate',
            'show_contact_info',
            'show_contact_to_producer',
            'show_messenger',
            'days_credit',
            'public_phone',
            'public_email',
            'public_facebook',
            'public_skype',
            'public_email_support',
            'account_manager_id',
            'affiliate_type',
            'is_affiliate_manager',
            'experience_time',
            'advantages'
        ];
    }

    public function testMe()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->get('/v1/auth/me');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => $this->expectedDataUser
        ]);
    }
    public function testUsersId()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->get('/v1/users/1110');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => $this->expectedDataUser
        ]);
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->token);
    }
}
