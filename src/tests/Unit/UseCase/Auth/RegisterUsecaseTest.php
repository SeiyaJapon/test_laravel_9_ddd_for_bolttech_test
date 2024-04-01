<?php

namespace Tests\Unit\UseCase\Auth;

use DDD\Application\UseCase\Auth\RegisterUsecase;
use DDD\Domain\Exceptions\User\UnableToBuildUserException;
use DDD\Domain\Exceptions\User\UserCredentialsValidationException;
use DDD\Domain\RegisterType;
use DDD\Domain\Repository\AuthRepositoryInterface;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\Services\ValidationServiceInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Infrastructure\Repositories\EloquentRepositories\Auth\AuthRepository;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class RegisterUsecaseTest extends TestCase
{
    private const EMAIL = 'mail2@mail.com';
    private const PASSWORD = '123123';
    private const PASS_CONF = '123123';
    private const ROLE = RegisterType::CLIENT_ROLE;
    private const PRO_ID = '1234567890';

    /** @var AuthRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $authRepository;
    /** @var UserRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $userRepository;
    /** @var ValidationServiceInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $validationService;
    private RegisterUsecase $registerUsecase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authRepository = $this->getMockBuilder(AuthRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->userRepository = $this->getMockBuilder(UserRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validationService = $this->getMockBuilder(ValidationServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registerUsecase = new RegisterUsecase(
            $this->authRepository,
            $this->userRepository,
            $this->validationService
        );
    }

    public function testRegisterNonExistingUser()
    {
        $this->validationService
            ->expects($this->exactly(1))
            ->method('validateEmailAndPassword')
            ->with(
                $this->isType('object'),
                $this->isType('object'),
                $this->isType('object')
            );

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findByEmail')
            ->with(
                $this->isType('object')
            )
            ->willReturn(null);

        $this->authRepository
            ->expects($this->exactly(1))
            ->method('register')
            ->with(
                new UserEmail(self::EMAIL),
                new UserPassword(self::PASSWORD)
            )->willReturn(
                [
                    'code' => Response::HTTP_CREATED,
                    'message' => AuthRepository::REGISTER_SUCCESS
                ]
            );

        $this->registerUsecase->execute(
            new UserEmail(self::EMAIL),
            new UserPassword(self::PASSWORD),
            new UserPassword(self::PASS_CONF)
        );
    }

    public function testRegisterCredentialsFail()
    {
        $this->validationService
            ->expects($this->exactly(1))
            ->method('validateEmailAndPassword')
            ->with(
                $this->isType('object'),
                $this->isType('object'),
                $this->isType('object')
            )
            ->willThrowException(new UserCredentialsValidationException());

        $this->expectException(UserCredentialsValidationException::class);

        $this->registerUsecase->execute(
            new UserEmail(self::EMAIL),
            new UserPassword(self::PASSWORD),
            new UserPassword(self::PASS_CONF)
        );
    }

    public function testRegisterNonExistingUserThrowsException()
    {
        $this->validationService
            ->expects($this->exactly(1))
            ->method('validateEmailAndPassword')
            ->with(
                $this->isType('object'),
                $this->isType('object'),
                $this->isType('object')
            );

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findByEmail')
            ->with(
                $this->isType('object')
            )
            ->willReturn(null);

        $this->authRepository
            ->expects($this->exactly(1))
            ->method('register')
            ->with(
                self::EMAIL,
                self::PASSWORD
            )
            ->willThrowException(new UnableToBuildUserException());

        $this->expectException(UnableToBuildUserException::class);

        $this->registerUsecase->execute(
            new UserEmail(self::EMAIL),
            new UserPassword(self::PASSWORD),
            new UserPassword(self::PASS_CONF)
        );
    }
}
