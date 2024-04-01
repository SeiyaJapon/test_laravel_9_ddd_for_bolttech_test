<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use App\Models\User;
use DDD\Domain\RegisterType;
use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private const URL = 'api/auth/login';

    private string $id;
    private string $email;
    private string $password;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create();

        $this->id = $faker->uuid;
        $this->email = $faker->email;
        $this->password = $faker->password;

        $payload = [
            'id' => $this->id,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ];

        $this->user = User::create($payload);

        $this->user->assignRole(RegisterType::CLIENT_ROLE);
    }

    public function testLogin()
    {
        $payload = [
            'email' => $this->email,
            'password' => $this->password
        ];

        $this->json('post', self::URL, $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    public function testLoginNoEmail()
    {
        $payload = [
            'password' => $this->password
        ];

        $this->json('post', self::URL, $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testLoginNoPassword()
    {
        $payload = [
            'email' => $this->email
        ];

        $this->json('post', self::URL, $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
