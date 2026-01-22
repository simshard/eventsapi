<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-black dark:text-white">Event Attendees</h1>
        <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">← Back to Events</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
            <p class="text-gray-600 dark:text-gray-300 text-sm">Total Capacity</p>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_capacity'] }}</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
            <p class="text-gray-600 dark:text-gray-300 text-sm">Confirmed Bookings</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['confirmed_bookings'] }}</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg p-4">
            <p class="text-gray-600 dark:text-gray-300 text-sm">Cancelled Bookings</p>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['cancelled_bookings'] }}</p>
        </div>
    </div>

    <div>
        <select wire:model.live="filterStatus" class="border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white">
            <option value="">All Bookings</option>
            <option value="confirmed">Confirmed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    @if ($bookings->count())
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-black dark:text-white">Attendee Name</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-black dark:text-white">Email</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-black dark:text-white">Phone</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-black dark:text-white">Booked By</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-black dark:text-white">Booking Date</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-black dark:text-white">Status</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach ($bookings as $booking)
                        <tr class="hover:bg-blue-100 dark:hover:bg-gray-700 hover:bg-opacity-50 transition-colors">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-black dark:text-white">{{ $booking->attendee?->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-black dark:text-white">{{ $booking->attendee?->email ?? 'N/A' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-black dark:text-white">{{ $booking->attendee?->phone ?? 'N/A' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-black dark:text-white">{{ $booking->user->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-black dark:text-white">{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm font-medium
                                    @if ($booking->status === 'confirmed')
                                        bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @else
                                        bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
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
        <p class="text-gray-500 dark:text-gray-400">No bookings for this event.</p>
    @endif
</div>
