<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventsList extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingEventId = null;
    public $filterOwned = false;

    public function render()
    {
        $query = Event::query();

        if ($this->filterOwned) {
            $query->where('user_id', auth()->id());
        }

        $events = $query->with('owner')->paginate(10);

        return view('livewire.events.events-list', [
            'events' => $events,
        ]);
    }

    public function deleteEvent($eventId)
    {
        $event = Event::findOrFail($eventId);
        $this->authorize('delete', $event);
        $event->delete();
        $this->dispatch('event-deleted', 'Event deleted successfully');
    }

    public function editEvent($eventId)
    {
        $this->editingEventId = $eventId;
        $this->showEditModal = true;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editingEventId = null;
    }
}
