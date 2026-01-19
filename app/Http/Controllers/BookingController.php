<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Http\Requests\StoreBookingRequest;
use Exception;

class BookingController extends Controller
{
    public function __construct(private BookingService $service) {}

    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = $this->service->bookEvent(
                auth()->id(),
                $request->event_id
            );
            return response()->json($booking, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $bookingId)
    {
        $this->authorize('delete', Booking::findOrFail($bookingId));
        $this->service->cancelBooking($bookingId);
        return response()->json(null, 204);
    }
}
