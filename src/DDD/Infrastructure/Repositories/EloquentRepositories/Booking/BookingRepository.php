<?php

declare(strict_types=1);

namespace DDD\Infrastructure\Repositories\EloquentRepositories\Booking;

use App\Models\Booking as BookingEloquent;
use App\Models\User as UserEloquent;
use Carbon\Carbon;
use DDD\Domain\Entities\Car\Car;
use DDD\Domain\Entities\User\User;
use DDD\Domain\Exceptions\Booking\UnableToBuildBookingException;
use DDD\Domain\Repository\BookingRepositoryInterface;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;

class BookingRepository implements BookingRepositoryInterface
{
    public function save(Car $car, User $user, BookingStart $start, BookingEnd $end): void
    {
        try {
            BookingEloquent::create(
                [
                    'id' => Uuid::uuid4()->toString(),
                    'car_id' => $car->getId()->value(),
                    'user_id' => $user->getId()->value(),
                    'start' => Carbon::createFromFormat('Y-m-d', $start->format('Y-m-d')),
                    'end' => Carbon::createFromFormat('Y-m-d', $end->format('Y-m-d'))
                ]
            );
        } catch (ModelNotFoundException | Error | Exception $exception) {
            throw new UnableToBuildBookingException($exception->getMessage(), $exception->getCode());
        }
    }

    public function userHasAlreadyHasBookingBetweenTwoDates(User $user, BookingStart $start, BookingEnd $end): bool
    {
        $start = Carbon::createFromFormat('Y-m-d', $start->value()->format('Y-m-d'))->toDateTime();
        $end = Carbon::createFromFormat('Y-m-d', $end->value()->format('Y-m-d'))->toDateTime();

        return UserEloquent::whereDoesntHave('booking', function ($query) use ($start, $end) {
                return $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end]);
            })
            ->get()
            ->isEmpty();
    }
}
