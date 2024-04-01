<?php

declare(strict_types=1);

namespace DDD\Infrastructure\UI\API\Controllers;

use Carbon\Carbon;
use DDD\Application\Exceptions\Common\ApplicationException;
use DDD\Application\UseCase\Car\GetAvailableCarsBetweenTwoDatesUseCase;
use DDD\Application\UseCase\Car\GetAvailableCarsNowUseCase;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;
use DDD\Infrastructure\UI\Helpers\CommonJSONResponse;
use Illuminate\Http\JsonResponse;

class CarController
{
    use CommonJSONResponse;

    private GetAvailableCarsNowUseCase $availableCarsNowUseCase;
    private GetAvailableCarsBetweenTwoDatesUseCase $availableCarsBetweenTwoDates;

    public function __construct(
        GetAvailableCarsNowUseCase $availableCarsNowUseCase,
        GetAvailableCarsBetweenTwoDatesUseCase $availableCarsBetweenTwoDates
    ) {
        $this->availableCarsNowUseCase = $availableCarsNowUseCase;
        $this->availableCarsBetweenTwoDates = $availableCarsBetweenTwoDates;
    }

    /**
     * @return JsonResponse
     *
     * @throws ApplicationException
     */
    public function getAvailableCarsNow(): JsonResponse
    {
        return $this->commonJSONResponse(
            $this->availableCarsNowUseCase->execute()
        );
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return JsonResponse
     *
     * @throws ApplicationException
     */
    public function getAvailableCarsByDates(string $start, string $end): JsonResponse
    {
        return $this->commonJSONResponse(
            $this->availableCarsBetweenTwoDates->execute(
                new BookingStart(
                    Carbon::createFromFormat('Y-m-d', $start)->toDateTimeImmutable()
                ),
                new BookingEnd(
                    Carbon::createFromFormat('Y-m-d', $end)->toDateTimeImmutable()
                )
            )
        );
    }
}
