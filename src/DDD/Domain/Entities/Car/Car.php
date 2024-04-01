<?php

declare(strict_types=1);

namespace DDD\Domain\Entities\Car;

use DDD\Domain\Entities\Brand\Brand;
use DDD\Domain\ValueObject\Car\CarId;
use DDD\Domain\ValueObject\Car\CarStatus;

class Car
{
    private CarId $id;
    private Brand $brand;
    private CarStatus $status;
    private ?CarPrice $price;

    public function __construct(
        CarId $id,
        Brand $brand,
        CarStatus $status,
        ?CarPrice $price
    ) {
        $this->id = $id;
        $this->brand = $brand;
        $this->status = $status;
        $this->price = $price;
    }

    /**
     * @return CarId
     */
    public function getId(): CarId
    {
        return $this->id;
    }

    /**
     * @param CarId $id
     */
    public function setId(CarId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return CarStatus
     */
    public function getStatus(): CarStatus
    {
        return $this->status;
    }

    /**
     * @param CarStatus $status
     */
    public function setStatus(CarStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return ?CarPrice
     */
    public function getPrice(): ?CarPrice
    {
        return $this->price;
    }

    /**
     * @param ?CarPrice $price
     */
    public function setPrice(?CarPrice $price): void
    {
        $this->price = $price;
    }
}
