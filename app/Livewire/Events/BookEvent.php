<?php

namespace App\Livewire\Events;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Attendee;
use Livewire\Component;

class BookEvent extends Component
{
    public $eventId;
    public $event;
    public $attendeeFirstName = '';
    public $attendeeLastName = '';
    public $attendeeEmail = '';
    public $attendeePhone = '';
    public $showBookingModal = false;
    public $bookingMessage = '';

    public function mount($eventId)
    {
        $this->eventId = $eventId;
        $this->event = Event::findOrFail($eventId);
    }

    public function render()
    {
        return view('livewire.events.book-event');
    }

    public function checkCanBook()
    {
        // Check if user already booked this event
        $existingBooking = Booking::userEventBooking(auth()->id(), $this->eventId)->first();
        if ($existingBooking) {
            $this->bookingMessage = 'You have already booked this event.';
            return false;
        }

        // Check venue capacity
        $bookedCount = Booking::forEvent($this->eventId)->active()->count();
        if ($bookedCount >= $this->event->venue_capacity) {
            $this->bookingMessage = 'This event is fully booked.';
            return false;
        }

        return true;
    }

    public function bookEvent()
    {
        if (!$this->checkCanBook()) {
            return;
        }

        $validated = $this->validate([
            'attendeeFirstName' => 'required|string|max:255',
            'attendeeLastName' => 'required|string|max:255',
            'attendeeEmail' => 'required|email|max:255',
            'attendeePhone' => 'nullable|string|max:20',
        ]);

        // Combine first and last name
        $fullName = $validated['attendeeFirstName'] . ' ' . $validated['attendeeLastName'];

        // Create attendee record
        $attendee = Attendee::create([
            'event_id' => $this->eventId,
            'name' => $fullName,
            'email' => $validated['attendeeEmail'],
            'phone' => $validated['attendeePhone'],
        ]);

        // Create booking record
        Booking::create([
            'user_id' => auth()->id(),
            'event_id' => $this->eventId,
            'booking_date' => now(),
            'status' => 'confirmed',
        ]);

        $this->reset(['attendeeFirstName', 'attendeeLastName', 'attendeeEmail', 'attendeePhone', 'showBookingModal']);
        $this->dispatch('booking-created', 'Event booked successfully!');
    }
}
