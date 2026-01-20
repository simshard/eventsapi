<div>
    @if (!auth()->check())
        <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to book an event.</p>
    @else
        <button
            wire:click="$set('showBookingModal', true)"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
        >
            Book Event
        </button>

        @if ($showBookingModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-2xl font-bold mb-4">Book Event</h2>
                    <p class="text-gray-600 mb-4">{{ $event->title }}</p>

                    @if ($bookingMessage)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ $bookingMessage }}
                        </div>
                    @endif

                    <form wire:submit="bookEvent" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-black">First Name *</label>
                                <input
                                    type="text"
                                    wire:model="attendeeFirstName"
                                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-black"
                                    placeholder="First name"
                                >
                                @error('attendeeFirstName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-black">Last Name *</label>
                                <input
                                    type="text"
                                    wire:model="attendeeLastName"
                                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-black"
                                    placeholder="Last name"
                                >
                                @error('attendeeLastName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-black">Email *</label>
                            <input
                                type="email"
                                wire:model="attendeeEmail"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-black"
                                placeholder="your@email.com"
                            >
                            @error('attendeeEmail') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-black">Phone</label>
                            <input
                                type="text"
                                wire:model="attendeePhone"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-black"
                                placeholder="+1234567890"
                            >
                            @error('attendeePhone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-4">
                            <button
                                type="button"
                                wire:click="$set('showBookingModal', false)"
                                class="flex-1 bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
                            >
                                Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif

    @script
        <script>
            $wire.on('booking-created', () => {
                alert('Event booked successfully!');
                window.location.reload();
            });
        </script>
    @endscript
</div>
