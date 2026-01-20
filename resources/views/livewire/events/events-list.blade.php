<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Events</h1>
        <div class="space-x-4">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model.live="filterOwned" class="rounded">
                <span class="ml-2">My Events Only</span>
            </label>
            <button
                wire:click="$set('showCreateModal', true)"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
            >
                Create Event
            </button>
        </div>
    </div>

    @if ($events->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead class="bg-blue-400">
                    <tr >
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Title</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Location</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Start Time</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Capacity</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Attendees</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Owner</th>
                        <th class="border border-gray-300 px-4 py-2 text-center text-gray-700"">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr class="hover:bg-blue-500  hover:text-gray-100 transition-colors">
                            <td class="border border-gray-300 px-4 py-2">
                               {{ $event->id }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $event->title }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $event->location ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $event->start_time->format('M d, Y H:i') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $event->venue_capacity }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $event->attendees()->count() }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $event->owner->name }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center space-x-2">
                                @if (auth()->id() === $event->user_id)
                                    <button
                                        wire:click="editEvent({{ $event->id }})"
                                        class="text-blue-600 hover:text-blue-800"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        wire:click="deleteEvent({{ $event->id }})"
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        Delete
                                    </button>
                                    <a
                                        href="{{ route('events.attendees', $event->id) }}"
                                        class="text-purple-600 hover:text-purple-800"
                                    >
                                        Attendees
                                    </a>
                                @else
                                    <livewire:events.book-event :eventId="$event->id" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    @else
        <p class="text-gray-500">No events found.</p>
    @endif

    <!-- Create Modal -->
    @if ($showCreateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl">
                <h2 class="text-2xl font-bold mb-4 text-black">Create Event</h2>
                <livewire:events.create-event />
                <button
                    wire:click="closeModals"
                    class="mt-4 bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg"
                >
                    Close
                </button>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if ($showEditModal && $editingEventId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl text-black">
                <h2 class="text-2xl font-bold mb-4">Edit Event</h2>
                <livewire:events.edit-event :eventId="$editingEventId" />
                <button
                    wire:click="closeModals"
                    class="mt-4 bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg"
                >
                    Close
                </button>
            </div>
        </div>
    @endif

    @script
        <script>
            $wire.on('event-created', () => {
                $wire.dispatch('refresh-list');
                $wire.set('showCreateModal', false);
                alert('Event created successfully!');
            });

            $wire.on('event-updated', () => {
                $wire.dispatch('refresh-list');
                $wire.set('showEditModal', false);
                alert('Event updated successfully!');
            });

            $wire.on('event-deleted', () => {
                $wire.dispatch('refresh-list');
                alert('Event deleted successfully!');
            });
        </script>
    @endscript
</div>
