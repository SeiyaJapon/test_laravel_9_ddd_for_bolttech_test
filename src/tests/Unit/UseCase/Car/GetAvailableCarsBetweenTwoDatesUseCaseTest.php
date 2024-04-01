<?php

namespace Tests\Unit\UseCase\Car;

use Carbon\Carbon;
use DDD\Application\Exceptions\Common\ApplicationException;
use DDD\Application\UseCase\Car\GetAvailableCarsBetweenTwoDatesUseCase;
use DDD\Domain\Repository\CarRepositoryInterface;
use DDD\Domain\ValueObject\Booking\BookingEnd;
use DDD\Domain\ValueObject\Booking\BookingStart;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class GetAvailableCarsBetweenTwoDatesUseCaseTest extends TestCase
{
    private MockObject|CarRepositoryInterface $carRespository;
    private GetAvailableCarsBetweenTwoDatesUseCase $getAvailableCarsBetweenTwoDatesUseCase;
    private Carbon $now;
    private Carbon $tomorrow;

    protected function setUp(): void
    {
        parent::setUp();

        $this->carRespository = $this->getMockBuilder(CarRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->getAvailableCarsBetweenTwoDatesUseCase = new GetAvailableCarsBetweenTwoDatesUseCase(
            $this->carRespository
        );

        $this->now = Carbon::now();
        $this->tomorrow = Carbon::tomorrow();
    }

    public function testGetAvailableCarsBetweenTwoDates()
    {
        $this->carRespository
            ->expects($this->exactly(1))
            ->method('getAvailableCarsBetweenDates')
            ->with(
                $this->isType('object'),
                $this->isType('object')
            )
            ->willReturn(array());

        $response = $this->getAvailableCarsBetweenTwoDatesUseCase->execute(
            new BookingStart($this->now->toDateTimeImmutable()),
            new BookingEnd($this->tomorrow->toDateTimeImmutable())
        );

        self::assertIsArray($response);
    }

    public function testGetAvailableCarsBetweenTwoDatesThrowException()
    {
        $this->carRespository
            ->expects($this->exactly(1))
            ->method('getAvailableCarsBetweenDates')
            ->with(
                $this->isType('object'),
                $this->isType('object')
            )
            ->willThrowException(new ApplicationException());

        $this->expectException(ApplicationException::class);

        $response = $this->getAvailableCarsBetweenTwoDatesUseCase->execute(
            new BookingStart($this->now->toDateTimeImmutable()),
            new BookingEnd($this->tomorrow->toDateTimeImmutable())
        );
    }
}
