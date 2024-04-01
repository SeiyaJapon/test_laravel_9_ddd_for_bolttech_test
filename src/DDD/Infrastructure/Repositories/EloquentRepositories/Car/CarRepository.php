<?php

declare(strict_types=1);

namespace DDD\Infrastructure\Repositories\EloquentRepositories\Car;

use App\Models\Car as CarsEloquent;
use App\Models\Season;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DDD\Domain\Entities\Car\Car;
use DDD\Domain\Exceptions\Car\CarNotFoundByIdException;
use DDD\Domain\Repository\CarRepositoryInterface;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;
use DDD\Domain\ValueObject\Car\CarId;
use DDD\Domain\ValueObject\Car\CarStatus;
use DDD\Infrastructure\Exceptions\EloquentCommonException;
use DDD\Infrastructure\UI\Transformers\CarEloquentTransform;
use Exception;

class CarRepository implements CarRepositoryInterface
{
    const CAR_NOT_FOUND_WITH_ID_MESSAGE = 'Car with ID %s not found';

    /**
     * @param CarId $carId
     *
     * @return Car|null
     *
     * @throws Exception
     */
    public function findCarById(CarId $carId): ?Car
    {
        try {
            return ($carEloquent = CarsEloquent::find($carId)->with('brand')->first())
                ? CarEloquentTransform::__fromArray($carEloquent->toArray())
                : null;
        } catch (EloquentCommonException|Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param CarId $carId
     *
     * @return Car
     *
     * @throws CarNotFoundByIdException
     */
    public function findCarByIdOrFail(CarId $carId): Car
    {
        if (!$car = $this->findCarById($carId)) {
            throw new CarNotFoundByIdException(
                sprintf(self::CAR_NOT_FOUND_WITH_ID_MESSAGE, $carId->value())
            );
        }

        return $car;
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function getAvailableCarsNow(): array
    {
        try {
            return CarsEloquent::where('status', 0)
                ->with('brand')
                ->with(['prices' => function ($pricesQuery) {
                    $pricesQuery->with('season');
                }])
                ->get()
                ->toArray();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param BookingStart $start
     * @param BookingEnd   $end
     *
     * @return array
     *
     * @throws Exception
     */
    public function getAvailableCarsBetweenDates(BookingStart $start, BookingEnd $end): array
    {
        try {
            $start = Carbon::createFromFormat('Y-m-d', $start->value()->format('Y-m-d'))->toDateTime();
            $end = Carbon::createFromFormat('Y-m-d', $end->value()->format('Y-m-d'))->toDateTime();

            return CarsEloquent::whereDoesntHave('booking', function ($query) use ($start, $end) {
                    return $query->whereBetween('start', [$start, $end])
                        ->orWhereBetween('end', [$start, $end]);
                })
                ->with('brand')
                ->with(['prices' => function ($pricesQuery) {
                    $pricesQuery->with('season');
                }])
                ->get()
                ->each(function ($item) use ($start, $end) {
                    $item->setAttribute('slotPrice', $this->getSlotPrices($item, $start, $end));

                    $availableStock = $this->getAvailableStock($start, $end);
                    $item->brand->setAttribute('availableStock', $availableStock[$item->brand->model]);
                })
                ->toArray();

        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param Car       $car
     * @param CarStatus $status
     *
     * @return void
     *
     * @throws Exception
     */
    public function setNewStatus(Car $car, CarStatus $status): void
    {
        try {
            $carEloquent = CarsEloquent::find($car->getId()->value());

            $carEloquent->status = $status->value();

            $carEloquent->save();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param Car $item
     * @param \DateTime    $start
     * @param \DateTime    $end
     *
     * @return array
     */
    public function getSlotPrices(Car $item, \DateTime $start, \DateTime $end): array
    {
        $item = CarsEloquent::find($item->getId()->value());
        $slot = [
            'days' => [],
            'total' => 0.0
        ];
        $period = CarbonPeriod::create(
            Carbon::createFromDate($start)->startOfDay(),
            Carbon::createFromDate($end)->endOfDay()
        );

        foreach ($period as $date) {
            $daySeason = (new Season())->getDaySeason($date->toDateTime());

            $slot['days'][$date->format('Y-m-d')] = $item->prices->where('season_id', $daySeason->id)->first()->price;
            $slot['total'] += $item->prices->where('season_id', $daySeason->id)->first()->price;
        }

        return $slot;
    }

    private function getAvailableStock(\DateTime $start, \DateTime $end): array
    {
        $availableStock = [];
        $cars = CarsEloquent::whereDoesntHave('booking', function ($query) use ($start, $end) {
                    return $query->whereBetween('start', [$start, $end])
                        ->orWhereBetween('end', [$start, $end]);
                })->get();

        foreach ($cars as $car) {
            if (isset($availableStock[$car->brand->model])) {
                $availableStock[$car->brand->model] += 1;
            } else {
                $availableStock[$car->brand->model] = 1;
            }
        }

        return $availableStock;
    }
}
