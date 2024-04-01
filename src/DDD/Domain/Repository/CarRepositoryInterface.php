<?php

namespace DDD\Domain\Repository;

use DDD\Domain\Entities\Car\Car;
use DDD\Domain\Exceptions\Car\CarNotFoundByIdException;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;
use DDD\Domain\ValueObject\Car\CarId;
use DDD\Domain\ValueObject\Car\CarStatus;
use Exception;

interface CarRepositoryInterface
{
    /**
     * @param CarId $carId
     *
     * @return Car|null
     *
     * @throws Exception
     */
    public function findCarById(CarId $carId): ?Car;

    /**
     * @param CarId $carId
     *
     * @return Car
     *
     * @throws CarNotFoundByIdException
     */
    public function findCarByIdOrFail(CarId $carId): Car;

    /**
     * @return array
     *
     * @throws Exception
     */
    public function getAvailableCarsNow(): array;

    /**
     * @param BookingStart $start
     * @param BookingEnd   $end
     *
     * @return array
     *
     * @throws Exception
     */
    public function getAvailableCarsBetweenDates(BookingStart $start, BookingEnd $end): array;

    /**
     * @param Car       $car
     * @param CarStatus $status
     *
     * @return void
     *
     * @throws Exception
     */
    public function setNewStatus(Car $car, CarStatus $status): void;

    /**
     * @param Car       $item
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return array
     */
    public function getSlotPrices(Car $item, \DateTime $start, \DateTime $end): array;
}
