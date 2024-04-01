<?php

declare(strict_types=1);

namespace DDD\Infrastructure\UI\Transformers;

use DDD\Domain\Entities\Brand\Brand;
use DDD\Domain\Entities\Car\Car;
use DDD\Domain\ValueObject\Brand\BrandId;
use DDD\Domain\ValueObject\Brand\BrandModel;
use DDD\Domain\ValueObject\Brand\BrandName;
use DDD\Domain\ValueObject\Brand\BrandStock;
use DDD\Domain\ValueObject\Car\CarId;
use DDD\Domain\ValueObject\Car\CarStatus;

class CarEloquentTransform
{
    public static function __fromArray(array $data): Car
    {
        return new Car(
            new CarId($data['id']),
            new Brand(
                new BrandId($data['brand']['id']),
                new BrandName($data['brand']['name']),
                new BrandModel($data['brand']['model']),
                new BrandStock($data['brand']['stock'])
            ),
            new CarStatus((bool) $data['status']),
            null
        );
    }
}
