<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class EventDetails extends Component
{
    public Event $event;

    public function mount($id)
    {
        $this->event = Event::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.events.event-details');
    }
}
