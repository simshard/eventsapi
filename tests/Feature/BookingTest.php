<?php

use Exception;
use App\Services\BookingService;
use App\Repositories\BookingRepository;

test('user cannot book event twice', function () {
    $repository = mock(BookingRepository::class);
    $repository->userHasBooked(1, 1)->andReturn(true);

    $service = new BookingService($repository);

    $service->bookEvent(1, 1);
})->throws(Exception::class, 'User already booked this event');
