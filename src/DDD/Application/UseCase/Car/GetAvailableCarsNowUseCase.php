<?php

declare(strict_types=1);

namespace DDD\Application\UseCase\Car;

use DDD\Application\Exceptions\Common\ApplicationException;
use DDD\Domain\CodeValues;
use DDD\Domain\Repository\CarRepositoryInterface;
use Exception;

class GetAvailableCarsNowUseCase
{
    private CarRepositoryInterface $carRepository;

    public function __construct(CarRepositoryInterface $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * @return array
     *
     * @throws ApplicationException
     */
    public function execute(): array
    {
        try {
            return [
                'cars' => $this->carRepository->getAvailableCarsNow(),
                'code' => CodeValues::SUCCESS_CODE
            ];
        } catch (Exception $exception) {
            throw new ApplicationException($exception->getMessage());
        }
    }
}
