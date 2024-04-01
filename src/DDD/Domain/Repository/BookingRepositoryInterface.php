<?php

namespace DDD\Domain\Repository;

use DDD\Domain\Entities\Car\Car;
use DDD\Domain\Entities\User\User;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;

interface BookingRepositoryInterface
{
    public function save(Car $car, User $user, BookingStart $start, BookingEnd $end): void;

    public function userHasAlreadyHasBookingBetweenTwoDates(User $user, BookingStart $start, BookingEnd $end): bool;
}
