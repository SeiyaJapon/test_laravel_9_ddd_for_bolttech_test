<?php

namespace Tests\Unit\UseCase\Auth;

use DDD\Application\UseCase\Auth\LoginUsecase;
use DDD\Domain\Exceptions\User\UserCredentialsValidationException;
use DDD\Domain\RegisterType;
use DDD\Domain\Repository\AuthRepositoryInterface;
use DDD\Domain\Services\ValidationServiceInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoginUsecaseTest extends TestCase
{
    private const TOKEN_RESPONSE = 'response_token';

    /** @var AuthRepositoryInterface|MockObject */
    private $authRepository;
    /** @var ValidationServiceInterface|MockObject */
    private $validationRepository;
    private LoginUsecase $loginUsecase;

    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authRepository = $this->getMockBuilder(AuthRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validationRepository = $this->getMockBuilder(ValidationServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loginUsecase = new LoginUsecase($this->authRepository, $this->validationRepository);

        $this->faker = Factory::create();
    }

    public function testLogin()
    {
        $this->validationRepository
            ->expects($this->exactly(1))
            ->method('validateCredentials')
            ->with(
                $this->isType('object'),
                $this->isType('object')
            );

        $this->authRepository
            ->expects($this->exactly(1))
            ->method('attempt')
            ->with(
                $this->isType('object'),
                $this->isType('object')
            )
            ->willReturn(
                [
                    'code' => Response::HTTP_OK,
                    'token' => self::TOKEN_RESPONSE
                ]
            );

        $this->loginUsecase->execute(
            new UserEmail($this->faker->email),
            new UserPassword($this->faker->password)
        );
    }

    public function testLoginFail()
    {
        $this->validationRepository
            ->expects($this->exactly(1))
            ->method('validateCredentials')
            ->with(
                $this->isType('object'),
                $this->isType('object')
            );

        $this->authRepository
            ->expects($this->exactly(1))
            ->method('attempt')
            ->with(
                $this->isType('object'),
                $this->isType('object')
            )
            ->willReturn(
                [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'token' => 'Unauthorized'
                ]
            );

        $this->loginUsecase->execute(
            new UserEmail($this->faker->email),
            new UserPassword($this->faker->password)
        );
    }

    public function testLoginCredentialsFail()
    {
        $this->validationRepository
            ->expects($this->exactly(1))
            ->method('validateCredentials')
            ->with(
                $this->isType('object'),
                $this->isType('object')
            )
            ->willThrowException(new UserCredentialsValidationException());

        $this->expectException(UserCredentialsValidationException::class);

        $this->loginUsecase->execute(
            new UserEmail($this->faker->email),
            new UserPassword($this->faker->password)
        );
    }
}
