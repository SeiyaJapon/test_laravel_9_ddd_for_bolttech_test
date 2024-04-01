<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\Car;

use DDD\Domain\Entities\Car\Car;
use DDD\Domain\Exceptions\Car\CarNotFoundByIdException;
use DDD\Domain\Repository\CarRepositoryInterface;
use DDD\Domain\ValueObject\Car\CarId;

class GetCarByIdUseCase
{
    private CarRepositoryInterface $carRepository;

    public function __construct(CarRepositoryInterface $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * @param CarId $id
     *
     * @return Car
     *
     * @throws CarNotFoundByIdException
     */
    public function execute(CarId $id): Car
    {
        return $this->carRepository->findCarByIdOrFail($id);
    }
}
