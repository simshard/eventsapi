<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class MyBookings extends Component
{
    use WithPagination;

    public $filterStatus = '';

    public function render()
    {
        $query = Booking::byUser(auth()->id())->with(['event', 'attendee']);

        if ($this->filterStatus) {
            $query->byStatus($this->filterStatus);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);

        return view('livewire.bookings.my-bookings', [
            'bookings' => $bookings,
        ]);
    }

    public function cancelBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $this->authorize('delete', $booking);

        $booking->update(['status' => 'cancelled']);
        $this->dispatch('booking-cancelled', 'Booking cancelled successfully!');
    }
}
