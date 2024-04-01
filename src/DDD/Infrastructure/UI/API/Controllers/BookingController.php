<?php

declare(strict_types=1);

namespace DDD\Infrastructure\UI\API\Controllers;

use Carbon\Carbon;
use DDD\Application\UseCase\Booking\BookingCarUseCase;
use DDD\Application\UseCase\Car\GetCarByIdUseCase;
use DDD\Application\UseCase\User\GetUserByIdUseCase;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;
use DDD\Domain\ValueObject\Car\CarId;
use DDD\Domain\ValueObject\User\UserId;
use DDD\Infrastructure\UI\Helpers\CommonJSONResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController
{
    use CommonJSONResponse;

    private BookingCarUseCase $bookingCarUseCase;
    private GetCarByIdUseCase $carByIdUseCase;
    private GetUserByIdUseCase $getUserByIdUseCase;

    public function __construct(
        BookingCarUseCase $bookingCarUseCase,
        GetCarByIdUseCase $carByIdUseCase,
        GetUserByIdUseCase $getUserByIdUseCase
    ) {
        $this->bookingCarUseCase = $bookingCarUseCase;
        $this->carByIdUseCase = $carByIdUseCase;
        $this->getUserByIdUseCase = $getUserByIdUseCase;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DDD\Application\Exceptions\Common\ApplicationException
     * @throws \DDD\Domain\Exceptions\Car\CarNotFoundByIdException
     */
    public function bookingCar(Request $request): JsonResponse
    {
        return $this->commonJSONResponse(
            $this->bookingCarUseCase->execute(
                $this->carByIdUseCase->execute(
                    new CarId($request->car_id)
                ),
                $this->getUserByIdUseCase->execute(
                    new UserId(\Auth::id())
                ),
                new BookingStart(
                    Carbon::createFromFormat('Y-m-d', $request->start)
                        ->toDateTimeImmutable()
                ),
                new BookingEnd(
                    Carbon::createFromFormat('Y-m-d', $request->end)
                    ->toDateTimeImmutable()
                )
            )
        );
    }
}
