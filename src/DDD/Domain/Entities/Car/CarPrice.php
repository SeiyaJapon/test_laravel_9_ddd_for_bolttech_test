<?php

declare(strict_types=1);

namespace DDD\Domain\Entities\Car;

use DDD\Domain\ValueObject\Car\CarId;
use DDD\Domain\ValueObject\CarPrice\CarPriceValue as Price;
use DDD\Domain\ValueObject\CarPrice\CarPriceId;
use DDD\Domain\ValueObject\Season\SeasonId;

class CarPrice
{
    private CarPriceId $id;
    private CarId $carId;
    private SeasonId $peakId;
    private Price $carPrice;

    public function __construct(
        CarPriceId $id,
        CarId $carId,
        SeasonId $peakId,
        Price $carPrice
    )
    {
        $this->id = $id;
        $this->carId = $carId;
        $this->peakId = $peakId;
        $this->carPrice = $carPrice;
    }

    /**
     * @return CarPriceId
     */
    public function getId(): CarPriceId
    {
        return $this->id;
    }

    /**
     * @param CarPriceId $id
     */
    public function setId(CarPriceId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return CarId
     */
    public function getCarId(): CarId
    {
        return $this->carId;
    }

    /**
     * @param CarId $carId
     */
    public function setCarId(CarId $carId): void
    {
        $this->carId = $carId;
    }

    /**
     * @return SeasonId
     */
    public function getSeasonId(): SeasonId
    {
        return $this->peakId;
    }

    /**
     * @param SeasonId $peakId
     */
    public function setSeasonId(SeasonId $peakId): void
    {
        $this->peakId = $peakId;
    }

    /**
     * @return Price
     */
    public function getCarPrice(): Price
    {
        return $this->carPrice;
    }

    /**
     * @param Price $carPrice
     */
    public function setCarPrice(Price $carPrice): void
    {
        $this->carPrice = $carPrice;
    }
}
