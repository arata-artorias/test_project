<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_fields_required_for_registration()
    {
        $this->json('POST', 'api/users/register', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "success" => false,
                "message" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "phone_number" => ["The phone number field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function test_unique_email()
    {
        $userData = [
            "name" => "Aung Myo Khaing",
            "email" => "aungmyokhaing.arata@gmail.com",
            "password" => "demo12345"
        ];

        $this->json('POST', 'api/users/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "success" => false,
                "message" => [
                    "email" => ["The email has already been taken."]
                ]
            ]);
    }
}
