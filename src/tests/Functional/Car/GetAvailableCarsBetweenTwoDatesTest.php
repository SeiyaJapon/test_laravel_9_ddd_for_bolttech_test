<?php

declare(strict_types=1);

namespace Tests\Functional\Car;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use DDD\Domain\RegisterType;
use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetAvailableCarsBetweenTwoDatesTest extends TestCase
{
    private const URL = 'api/car/%s/%s/available';
    private string $dateStart;
    private string $dateEnd;
    private string $dateWronEnd;

    public function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create();

        $this->id = $faker->uuid;
        $this->email = $faker->email;
        $this->password = $faker->password;

        $this->user = User::create([
            'id' => $this->id,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ]);

        Auth::login($this->user);

        $this->user->assignRole(RegisterType::CLIENT_ROLE);

        $this->bearer = $this->user->createToken('Token Name')->accessToken;

        $this->dateStart = Carbon::now()->addYears(10)->format('Y-m-d');
        $this->dateEnd = Carbon::now()->addDays(2)->addYears(10)->format('Y-m-d');
        $this->dateWronEnd = Carbon::yesterday()->format('Y-m-d');
    }

    public function testGetAvailableCarsBetweenTwoDates()
    {
        $url = sprintf(self::URL, $this->dateStart, $this->dateEnd);

        $this->withHeader('Authorization', 'Bearer ' . $this->bearer)
            ->get($url, [])
            ->assertOk();
    }

    public function testGetAvailableCarsBetweenTwoDatesWrongDates()
    {
        $url = sprintf(self::URL, $this->dateStart, $this->dateWronEnd);

        $this->withHeader('Authorization', 'Bearer ' . $this->bearer)
            ->get($url, [])
            ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function tearDown(): void
    {
        if ($user = User::where('email', $this->email)->first()) {
            $user->forceDelete();
        }

        parent::tearDown();
    }
}
