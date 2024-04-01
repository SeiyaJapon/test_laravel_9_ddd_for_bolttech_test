<?php

declare(strict_types=1);

namespace DDD\Domain\Entities\User;

use DateTime;
use DDD\Domain\ValueObject\User\UserActivationToken;
use DDD\Domain\ValueObject\User\UserEmail;
use DDD\Domain\ValueObject\User\UserEmailVerification;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Domain\ValueObject\User\UserPassword;
use DDD\Domain\ValueObject\User\UserResetPassword;

class User
{
    private UserId $id;
    private UserEmail $email;
    private ?UserPassword $password;
    private ?UserEmailVerification $email_verified_at;
    private ?UserActivationToken $activation_token;
    private ?UserResetPassword $reset_password;
    private DateTime $created_at;
    private DateTime $updated_at;
    private ?DateTime $deleted_at;
    private ?DateTime $license_expiration;

    public function __construct(
        UserId $id,
        UserEmail $email,
        ?UserPassword $password
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->email_verified_at = null;
        $this->reset_password = null;
        $this->created_at = new DateTime();
        $this->markAsUpdated();
        $this->deleted_at = null;

        $this->setActivationToken(
            new UserActivationToken(sha1(uniqid()))
        );

        $this->license_expiration = null;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getPassword(): ?UserPassword
    {
        return $this->password;
    }

    public function setPassword(UserPassword $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function setEmail(UserEmail $email): void
    {
        $this->email = $email;
    }

    public function getEmailVerifiedAt(): ?UserEmailVerification
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(?DateTime $value): void
    {
        if (!$value) {
            $value = new DateTime();
        }

        $this->email_verified_at = new UserEmailVerification($value);
    }

    public function setEmailVerifiedAsNull(?DateTime $value): void
    {
        $this->email_verified_at = null;
    }

    public function isActive(): bool
    {
        return null === $this->getActivationToken();
    }

    public function getActivationToken(): ?UserActivationToken
    {
        return $this->activation_token;
    }

    public function setActivationToken(?UserActivationToken $token): void
    {
        $this->activation_token = $token;
    }

    public function getResetPasswordToken(): ?UserResetPassword
    {
        return $this->reset_password;
    }

    public function setResetPasswordToken(?UserResetPassword $resetPasswordToken): void
    {
        $this->reset_password = $resetPasswordToken;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $dateTime): void
    {
        $this->created_at = $dateTime;
    }

    public function updatedAt(): DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTime $dateTime): void
    {
        $this->updated_at = $dateTime;
    }

    public function markAsUpdated(): void
    {
        $this->updated_at = new DateTime();
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function isDelete(): bool
    {
        return $this->deleted_at !== null;
    }

    public function markAsDeleted(): void
    {
        $this->deleted_at = new DateTime();
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(DateTime $dateTime): void
    {
        $this->deleted_at = $dateTime;
    }

    public function equals(User $user): bool
    {
        return $this->id === $user->getId();
    }

    /**
     * @return ?DateTime
     */
    public function getLicenseExpiration(): ?DateTime
    {
        return $this->license_expiration;
    }

    /**
     * @param ?DateTime $license_expiration
     */
    public function setLicenseExpiration(DateTime $license_expiration = null): void
    {
        $this->license_expiration = $license_expiration;
    }

    public function __toArray(): array
    {
        return [
            'id' => $this->getId()->value(),
            'email' => $this->getEmail()->value(),
            'email_verified_at' => optional($this->getEmailVerifiedAt())->value(),
            'activation_token' => optional($this->getActivationToken())->value(),
            'reset_password' => optional($this->getResetPasswordToken())->value(),
            'created_at' => optional($this->getCreatedAt())->getTimestamp(),
            'markAsUpdated' => optional($this->getUpdatedAt())->getTimestamp(),
            'deleted_at' => optional($this->getDeletedAt())->getTimestamp(),
            'license_expiration' => optional($this->getLicenseExpiration())->getTimestamp()
        ];
    }
}
