<?php

namespace App\Livewire\Events;

use App\Models\Event;
use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class EventAttendees extends Component
{
    use WithPagination;

    public $eventId;
    public $event;
    public $filterStatus = '';

    public function mount($eventId)
    {
        $this->eventId = $eventId;
        $this->event = Event::findOrFail($eventId);
        $this->authorize('view', $this->event);
    }

    public function render()
    {
        $query = Booking::forEvent($this->eventId)->with(['user', 'attendee']);

        if ($this->filterStatus) {
            $query->byStatus($this->filterStatus);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);

        $stats = [
            'total_capacity' => $this->event->venue_capacity,
            'confirmed_bookings' => Booking::forEvent($this->eventId)->active()->count(),
            'cancelled_bookings' => Booking::forEvent($this->eventId)->byStatus('cancelled')->count(),
        ];

        return view('livewire.events.event-attendees', [
            'bookings' => $bookings,
            'stats' => $stats,
        ]);
    }
}
