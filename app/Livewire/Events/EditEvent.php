<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class EditEvent extends Component
{
    public $eventId;
    public $title = '';
    public $description = '';
    public $location = '';
    public $venue_name = '';
    public $fee = '';
    public $currency = 'USD';
    public $venue_capacity = '';
    public $start_date = '';
    public $start_time = '';
    public $end_date = '';
    public $end_time = '';

    public function mount($eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorize('update', $event);

        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->location = $event->location;
        $this->venue_name = $event->venue_name;
        $this->fee = $event->fee;
        $this->currency = $event->currency;
        $this->venue_capacity = $event->venue_capacity;
        $this->start_date = $event->start_time->format('Y-m-d');
        $this->start_time = $event->start_time->format('H:i');
        $this->end_date = $event->end_time?->format('Y-m-d');
        $this->end_time = $event->end_time?->format('H:i');
    }

    public function render()
    {
        return view('livewire.events.edit-event');
    }

    public function update()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'venue_name' => 'nullable|string|max:255',
            'fee' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'venue_capacity' => 'required|integer|min:1',
            'start_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'nullable|date_format:Y-m-d',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        // Combine date and time into datetime strings
        $start_datetime = $validated['start_date'] . ' ' . $validated['start_time'];
        $end_datetime = $validated['end_date'] && $validated['end_time']
            ? $validated['end_date'] . ' ' . $validated['end_time']
            : null;

        Event::findOrFail($this->eventId)->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'venue_name' => $validated['venue_name'],
            'fee' => $validated['fee'],
            'currency' => $validated['currency'],
            'venue_capacity' => $validated['venue_capacity'],
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
        ]);

        $this->dispatch('event-updated', 'Event updated successfully');
    }
}
