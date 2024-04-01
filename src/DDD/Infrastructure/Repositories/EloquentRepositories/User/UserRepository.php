<?php

declare(strict_types=1);

namespace DDD\Infrastructure\Repositories\EloquentRepositories\User;

use App\Models\User as UserEloquent;
use Carbon\Carbon;
use DDD\Domain\Entities\User\User;
use DDD\Domain\Exceptions\User\UnableToBuildUserException;
use DDD\Domain\Exceptions\User\UserHasNoPermissions;
use DDD\Domain\Exceptions\User\UserNotFoundByIdException;
use DDD\Domain\Exceptions\User\UserNotFoundException;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Infrastructure\UI\Transformers\UserEloquentTransform;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;

class UserRepository implements UserRepositoryInterface
{
    const NON_VALID_ACTIVATION_TOKEN_MESSAGE = 'This user activation token is not valid.';
    const USER_NOT_FOUND_WITH_ID_MESSAGE = 'User with ID %s not found';

    /**
     * @param UserId $id
     *
     * @return User|null
     *
     * @throws Exception
     */
    public function findById(UserId $id): ?User
    {
        return ($userEloquent = UserEloquent::find($id))
            ? UserEloquentTransform::__fromArray($userEloquent->toArray())
            : null;
    }

    /**
     * @param UserId $id
     *
     * @return User|null
     *
     * @throws UserNotFoundByIdException
     */
    public function findByIdOrFail(UserId $id): ?User
    {
        if (!$user = $this->findById($id)) {
            throw new UserNotFoundByIdException(
                sprintf(self::USER_NOT_FOUND_WITH_ID_MESSAGE, $id->value())
            );
        }

        return $user;
    }

    /**
     * @param UserEmail $email
     *
     * @return User|null
     *
     * @throws Exception
     */
    public function findByEmail(UserEmail $email): ?User
    {
        return ($userEloquent = UserEloquent::where('email', $email->value())->first())
            ? UserEloquentTransform::__fromArray($userEloquent->toArray())
            : null;
    }

    /**
     * @param UserEmail $email
     *
     * @return User|null
     *
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function findByEmailOrFail(UserEmail $email): ?User
    {
        if (null === ($user = $this->findByEmail($email))) {
            throw new UserNotFoundException($email->value());
        }

        return $user;
    }

    /**
     * @param UserEmail    $email
     * @param UserPassword $password
     * @param string       $role
     *
     * @return User|null
     *
     * @throws UnableToBuildUserException
     */
    public function save(UserEmail $email, UserPassword $password, string $role = 'user'): ?User
    {
        try {
            $userEloquent = UserEloquent::create(
                [
                    'id' => Uuid::uuid4()->toString(),
                    'email' => $email->value(),
                    'password' => bcrypt($password->value())
                ]
            );

            $userEloquent->assignRole($role);

            $user = UserEloquentTransform::__fromArray($userEloquent->toArray());

            return $user;
        } catch (ModelNotFoundException | Error | Exception $exception) {
            throw new UnableToBuildUserException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param UserId $userId
     *
     * @throws UserNotFoundByIdException
     * @throws Exception
     */
    public function delete(UserId $userId): void
    {
        try {
            $this->checkAuthUserCanDeleteThisUser($userId);

            $userEloquent = UserEloquent::find($userId->value());

            $userEloquent->delete();
        } catch (ModelNotFoundException | Error) {
            throw new UserNotFoundByIdException($userId->value());
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param UserId $userId
     *
     * @return bool
     */
    public function isAdmin(UserId $userId): bool
    {
        $user = $userEloquent = UserEloquent::find($userId);

        return optional($user)->isAdmin();
    }

    /**
     * @param UserId $userId
     *
     * @return bool
     */
    public function isActive(UserId $userId): bool
    {
        $user = UserEloquent::find($userId);

        return optional($user)->isActive();
    }

    /**
     * @param User      $user
     * @param \DateTime $end
     *
     * @return bool
     *
     * @throws UserNotFoundByIdException
     */
    public function isUserLicenseValidBetweenDates(User $user, \DateTime $end): bool
    {
        try {
            $licenseDate = Carbon::createFromTimestamp($user->getLicenseExpiration()->getTimestamp());

            return $licenseDate->greaterThan(Carbon::createFromDate($end));
        } catch (ModelNotFoundException | Error) {
            throw new UserNotFoundByIdException($user->getId()->value());
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param UserId $userId
     *
     * @return void
     * @throws UserHasNoPermissions
     */
    private function checkAuthUserCanDeleteThisUser(UserId $userId): void
    {
        if (! auth()->user()->isAdmin() && ! ($userId->value() === auth()->id())) {
            throw new UserHasNoPermissions();
        }
    }
}
