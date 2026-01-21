<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">My Bookings</h1>
        <div>
            <select wire:model.live="filterStatus" class="border border-gray-300 rounded px-3 py-2 bg-gray-100 text-white">
                <option value="">All Bookings</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    @if ($bookings->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr class="text-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">Event</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Attendee Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Booking Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-blue-200  hover:text-gray-500 transition-colors">
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->event->title }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->user->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->user->email }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm font-medium
                                    @if ($booking->status === 'confirmed')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                @if ($booking->status === 'confirmed')
                                    <button
                                        wire:click="cancelBooking({{ $booking->id }})"
                                        onclick="confirm('Cancel this booking?') || event.stopImmediatePropagation()"
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        Cancel
                                    </button>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @else
        <p class="text-gray-500">No bookings found.</p>
    @endif
</div>
