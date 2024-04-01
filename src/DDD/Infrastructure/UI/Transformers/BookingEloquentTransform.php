<?php

declare(strict_types=1);

namespace DDD\Infrastructure\UI\Transformers;

use Carbon\Carbon;
use DDD\Domain\Entities\Booking\Booking;
use DDD\Domain\Repository\CarRepositoryInterface;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingId;
use DDD\Domain\ValueObject\Booking\BookingStart;
use DDD\Domain\ValueObject\Booking\BookingTotal;
use DDD\Domain\ValueObject\Car\CarId;
use DDD\Domain\ValueObject\User\UserId;

class BookingEloquentTransform
{
        // BookingId $id,
        // Car $car,
        // User $user,
        // BookingStart $start,
        // BookingEnd $end,
        // BookingTotal $total
    private CarRepositoryInterface $carRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        CarRepositoryInterface $carRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->carRepository = $carRepository;
        $this->userRepository = $userRepository;
    }

    public function __FromArray(array $data): Booking
    {
        return new Booking(
            new BookingId($data['id']),
            $this->carRepository->findCarById(
                new CarId($data['car_id'])
            ),
            $this->userRepository->findById(
                new UserId($data['user_id'])
            ),
            new BookingStart(
                Carbon::createFromFormat('Y-m-d', $data['start'])->toDateTimeImmutable()
            ),
            new BookingEnd(
                Carbon::createFromFormat('Y-m-d', $data['end'])->toDateTimeImmutable()
            ),
            new BookingTotal(1, 's')
        );
    }
}
