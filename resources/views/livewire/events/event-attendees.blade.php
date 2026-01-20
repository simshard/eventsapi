<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Event Attendees</h1>
        <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Events</a>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-gray-600 text-sm">Total Capacity</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_capacity'] }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-gray-600 text-sm">Confirmed Bookings</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['confirmed_bookings'] }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-gray-600 text-sm">Cancelled Bookings</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['cancelled_bookings'] }}</p>
        </div>
    </div>

    <div>
        <select wire:model.live="filterStatus" class="border border-gray-300 rounded px-3 py-2 bg-gray-100 text-black">
            <option value="">All Bookings</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    @if ($bookings->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Attendee Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Phone</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Booked By</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Booking Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-blue-100 hover:bg-opacity-50 transition-colors">
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->attendee->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->attendee->email }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->attendee->phone ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $booking->user->name }}</td>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @else
        <p class="text-gray-500">No bookings for this event.</p>
    @endif
</div>
