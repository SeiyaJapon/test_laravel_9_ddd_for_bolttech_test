<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterAdminTest extends TestCase
{
    private const URL = 'api/auth/register_admin';
    private const USER_CREATED_REGISTER_SUCCESS = 'User created. Register Success';
    const DIFFERENT_PASS = 'different';

    private string $id;
    private string $email;
    private string $password;

    public function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create();

        $this->id = $faker->uuid;
        $this->email = $faker->email;
        $this->password = $faker->password;
    }

    public function testRegisterAdmin()
    {
        $payload = [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password
        ];

        $this->post(self::URL, $payload)
            ->assertCreated()
            ->assertJson(['message' => self::USER_CREATED_REGISTER_SUCCESS]);
    }

    public function testRegisterAdminNoEmail()
    {
        $payload = [
            'id' => $this->id,
            'password' => $this->password,
            'password_confirmation' => $this->password
        ];

        $this->json('post', self::URL, $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testRegisterAdminNoPassword()
    {
        $payload = [
            'id' => $this->id,
            'email' => $this->email,
            'password_confirmation' => $this->password
        ];

        $this->json('post', self::URL, $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testRegisterAdminNoPasswordConfirmation()
    {
        $payload = [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password
        ];

        $this->json('post', self::URL, $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testRegisterAdminDifferentPasswordConfirmation()
    {
        $payload = [
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => self::DIFFERENT_PASS
        ];

        $this->json('post', self::URL, $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
