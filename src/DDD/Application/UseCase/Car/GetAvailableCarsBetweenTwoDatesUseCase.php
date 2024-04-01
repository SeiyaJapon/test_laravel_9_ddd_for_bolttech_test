<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\Car;

use DDD\Application\Exceptions\Common\ApplicationException;
use DDD\Domain\CodeValues;
use DDD\Domain\Repository\CarRepositoryInterface;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;

class GetAvailableCarsBetweenTwoDatesUseCase
{
    private const START_GREATER_THEN_END_MESSAGE_EXCEPTION = 'Date start is greater than date end. Wrong Expression.';

    private CarRepositoryInterface $carRepository;

    public function __construct(CarRepositoryInterface $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * @param BookingStart $start
     * @param BookingEnd   $end
     *
     * @return array
     *
     * @throws ApplicationException
     */
    public function execute(BookingStart $start, BookingEnd $end): array
    {
        try {
            $this->validateDates($start, $end);

            return [
                'cars' => $this->carRepository->getAvailableCarsBetweenDates($start, $end),
                'code' => CodeValues::SUCCESS_CODE
            ];
        } catch (\Exception $exception) {
            throw new ApplicationException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param BookingStart $start
     * @param BookingEnd   $end
     *
     * @return void
     *
     * @throws ApplicationException
     */
    private function validateDates(BookingStart $start, BookingEnd $end): void
    {
        if ($start->greaterThan(\DateTime::createFromInterface($end->value()))) {
            throw new ApplicationException(
                self::START_GREATER_THEN_END_MESSAGE_EXCEPTION,
                CodeValues::BAD_REQUEST
            );
        }
    }
}
