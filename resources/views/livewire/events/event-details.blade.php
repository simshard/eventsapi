<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ $event->title }}</h1>
        <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Events
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Event Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold text-gray-700">ID:</span>
                    <span class="ml-2">{{ $event->id }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Location:</span>
                    <span class="ml-2">{{ $event->location ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Start Time:</span>
                    <span class="ml-2">{{ $event->start_time->format('M d, Y H:i') }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">End Time:</span>
                    <span class="ml-2">{{ $event->end_time?->format('M d, Y H:i') ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Venue Capacity:</span>
                    <span class="ml-2">{{ $event->venue_capacity }}</span>
                </div>
            </div>
        </div>

        <!-- Owner Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Owner Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold text-gray-700">Owner:</span>
                    <span class="ml-2">{{ $event->owner->name }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Email:</span>
                    <span class="ml-2">{{ $event->owner->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    @if ($event->description)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Description</h2>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
        </div>
    @endif

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Actions</h2>
        <div class="space-x-2">
            @if (auth()->id() === $event->user_id)
                <a href="{{ route('events.edit', $event->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
                <a href="{{ route('events.attendees', $event->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    View Attendees
                </a>
            @else
                <livewire:events.book-event :eventId="$event->id" />
            @endif
        </div>
    </div>
</div>
