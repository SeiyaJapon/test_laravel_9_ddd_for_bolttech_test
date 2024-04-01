<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\Booking;

use Carbon\Carbon;
use DDD\Application\Exceptions\Common\ApplicationException;
use DDD\Domain\CodeValues;
use DDD\Domain\Entities\Car\Car;
use DDD\Domain\Entities\User\User;
use DDD\Domain\Repository\BookingRepositoryInterface;
use DDD\Domain\Repository\CarRepositoryInterface;
use DDD\Domain\Repository\UserRepositoryInterface;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;
use DDD\Domain\ValueObject\Car\CarStatus;

class BookingCarUseCase
{
    const BOOKING_SUCCESSFUL_MESSAGE = 'Booking successful!';
    const USER_LICENSE_IS_NOT_VALID_ON_THIS_TIME_SLOT = 'User license is not valid on this time slot';
    private BookingRepositoryInterface $bookingRepository;
    private UserRepositoryInterface $userRepository;
    private CarRepositoryInterface $carRepository;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        UserRepositoryInterface $userRepository,
        CarRepositoryInterface $carRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->userRepository = $userRepository;
        $this->carRepository = $carRepository;
    }

    public function execute(Car $car, User $user, BookingStart $start, BookingEnd $end): array
    {
        try {
            $endDateTime = Carbon::createFromFormat('Y-m-d', $end->format('Y-m-d'))->toDateTime();

            if (! $this->bookingRepository->userHasAlreadyHasBookingBetweenTwoDates($user, $start, $end)) {
                if ($this->userRepository->isUserLicenseValidBetweenDates($user, $endDateTime)) {
                    $this->bookingRepository->save($car, $user, $start, $end);
                    $this->carRepository->setNewStatus($car, new CarStatus(true));

                    return [
                        'message' => self::BOOKING_SUCCESSFUL_MESSAGE,
                        'code' => CodeValues::SUCCESS_CODE
                    ];
                } else {
                    return [
                        'message' => self::USER_LICENSE_IS_NOT_VALID_ON_THIS_TIME_SLOT,
                        'code' => CodeValues::SUCCESS_CODE
                    ];
                }
            }

            return [
                'message' => 'User already has a booking on this time slot',
                'code' => CodeValues::SUCCESS_CODE
            ];
        } catch (\Exception $exception) {
            throw new ApplicationException($exception->getMessage());
        }
    }
}
