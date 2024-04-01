<?php

declare(strict_types=1);

namespace DDD\Domain\Entities\Booking;

use DDD\Domain\Entities\Car\Car;
use DDD\Domain\Entities\User\User;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingId;
use DDD\Domain\ValueObject\Booking\BookingStart;
use DDD\Domain\ValueObject\Booking\BookingTotal;

class Booking
{
    private BookingId $id;
    private Car $car;
    private User $user;
    private BookingStart $start;
    private BookingEnd $end;
    private BookingTotal $total;

    public function __construct(
        BookingId $id,
        Car $car,
        User $user,
        BookingStart $start,
        BookingEnd $end,
        BookingTotal $total
    ) {
        $this->id = $id;
        $this->car = $car;
        $this->user = $user;
        $this->start = $start;
        $this->end = $end;
        $this->total = $total;
    }

    /**
     * @return BookingId
     */
    public function getId(): BookingId
    {
        return $this->id;
    }

    /**
     * @param BookingId $id
     */
    public function setId(BookingId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car): void
    {
        $this->car = $car;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return BookingStart
     */
    public function getStart(): BookingStart
    {
        return $this->start;
    }

    /**
     * @param BookingStart $start
     */
    public function setStart(BookingStart $start): void
    {
        $this->start = $start;
    }

    /**
     * @return BookingEnd
     */
    public function getEnd(): BookingEnd
    {
        return $this->end;
    }

    /**
     * @param BookingEnd $end
     */
    public function setEnd(BookingEnd $end): void
    {
        $this->end = $end;
    }

    /**
     * @return BookingTotal
     */
    public function getTotal(): BookingTotal
    {
        return $this->total;
    }

    /**
     * @param BookingTotal $total
     */
    public function setTotal(BookingTotal $total): void
    {
        $this->total = $total;
    }
}
