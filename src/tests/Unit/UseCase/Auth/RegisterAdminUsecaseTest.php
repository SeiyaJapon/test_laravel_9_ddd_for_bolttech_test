<?php

namespace Tests\Unit\UseCase\Auth;

use DDD\Application\UseCase\Auth\RegisterAdminUsecase;
use DDD\Domain\Exceptions\User\UnableToBuildUserException;
use DDD\Domain\Exceptions\User\UserCredentialsValidationException;
use DDD\Domain\Repository\AuthRepositoryInterface;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\Services\ValidationServiceInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Infrastructure\Repositories\EloquentRepositories\Auth\AuthRepository;
use DDD\Infrastructure\UI\Transformers\UserEloquentTransform;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegisterAdminUsecaseTest extends TestCase
{
    private const EMAIL = 'mail2@mail.com';
    private const PASSWORD = '123123';
    private const PASS_CONF = '123123';

    /** @var AuthRepositoryInterface|MockObject */
    private $authRepository;
    /** @var UserRepositoryInterface|MockObject */
    private $userRepository;
    /** @var ValidationServiceInterface|MockObject */
    private $validationRepository;
    private RegisterAdminUsecase $registerAdminUsecase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authRepository = $this->getMockBuilder(AuthRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->userRepository = $this->getMockBuilder(UserRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validationRepository = $this->getMockBuilder(ValidationServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->registerAdminUsecase = new RegisterAdminUsecase(
            $this->authRepository,
            $this->userRepository,
            $this->validationRepository
        );
    }

    public function testRegisterNonExistingUser()
    {
        $this->validationRepository
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
            ->with($this->isType('object'))
            ->willReturn(null);

        $this->authRepository
            ->expects($this->exactly(1))
            ->method('registerAdmin')
            ->with(
                new UserEmail(self::EMAIL),
                new UserPassword(self::PASSWORD)
            )->willReturn(
                [
                    'code' => Response::HTTP_CREATED,
                    'message' => AuthRepository::REGISTER_SUCCESS
                ]
            );

        $this->registerAdminUsecase->execute(
            self::EMAIL,
            self::PASSWORD,
            self::PASS_CONF
        );
    }

    public function testRegisterExistingUserNotForThisProject()
    {
        $this->validationRepository
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
            ->with($this->isType('object'))
            ->willReturn(
                UserEloquentTransform::__fromArray(
                    [
                      "id" => "ec845dc7-ee3f-4d87-aa5a-6e5bdcbe50ea",
                      "email" => "mail2@mail.com",
                      "email_verified_at" => null,
                      "activation_token" => null,
                      "reset_password" => null,
                      "created_at" => "2021-05-31T13:52:07.000000Z",
                      "updated_at" => "2021-05-31T13:52:07.000000Z",
                      "deleted_at" => null
                    ]
                )
            );

        $this->authRepository
            ->expects($this->exactly(0))
            ->method('registerAdmin');

        $this->registerAdminUsecase->execute(
            self::EMAIL,
            self::PASSWORD,
            self::PASS_CONF
        );
    }

    public function testRegisterCredentialsFail()
    {
        $this->validationRepository
            ->expects($this->exactly(1))
            ->method('validateEmailAndPassword')
            ->with(
                $this->isType('object'),
                $this->isType('object'),
                $this->isType('object')
            )
            ->willThrowException(new UserCredentialsValidationException());

        $this->expectException(UserCredentialsValidationException::class);

        $this->registerAdminUsecase->execute(
            self::EMAIL,
            self::PASSWORD,
            self::PASS_CONF
        );
    }

    public function testRegisterNonExistingUserThrowsException()
    {
        $this->validationRepository
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
            ->with($this->isType('object'))
            ->willReturn(null);

        $this->authRepository
            ->expects($this->exactly(1))
            ->method('registerAdmin')
            ->with(
                new UserEmail(self::EMAIL),
                new UserPassword(self::PASSWORD)
            )
            ->willThrowException(new UnableToBuildUserException());

        $this->expectException(UnableToBuildUserException::class);

        $this->registerAdminUsecase->execute(
            self::EMAIL,
            self::PASSWORD,
            self::PASS_CONF
        );
    }
}
