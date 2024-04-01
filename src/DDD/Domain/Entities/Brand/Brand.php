<?php

declare(strict_types=1);

namespace DDD\Domain\Entities\Brand;

use DDD\Domain\ValueObject\Brand\BrandId;
use DDD\Domain\ValueObject\Brand\BrandModel;
use DDD\Domain\ValueObject\Brand\BrandName;
use DDD\Domain\ValueObject\Brand\BrandStock;

class Brand
{
    private BrandId $id;
    private BrandName $brandName;
    private BrandModel $brandModel;
    private BrandStock $brandStock;

    public function __construct(
        BrandId $id,
        BrandName $brandName,
        BrandModel $brandModel,
        BrandStock $brandStock
    ) {
        $this->id = $id;
        $this->brandName = $brandName;
        $this->brandModel = $brandModel;
        $this->brandStock = $brandStock;
    }

    /**
     * @return BrandId
     */
    public function getId(): BrandId
    {
        return $this->id;
    }

    /**
     * @param BrandId $id
     */
    public function setBrandId(BrandId $id): void
    {
        $this->id = $id;
    }

    /**
     * @return BrandName
     */
    public function getBrandName(): BrandName
    {
        return $this->brandName;
    }

    /**
     * @param BrandName $brandName
     */
    public function setBrandName(BrandName $brandName): void
    {
        $this->brandName = $brandName;
    }

    /**
     * @return BrandModel
     */
    public function getBrandModel(): BrandModel
    {
        return $this->brandModel;
    }

    /**
     * @param BrandModel $brandModel
     */
    public function setBrandModel(BrandModel $brandModel): void
    {
        $this->brandModel = $brandModel;
    }

    /**
     * @return BrandStock
     */
    public function getBrandStock(): BrandStock
    {
        return $this->brandStock;
    }

    /**
     * @param BrandStock $brandStock
     */
    public function setBrandStock(BrandStock $brandStock): void
    {
        $this->brandStock = $brandStock;
    }
}
