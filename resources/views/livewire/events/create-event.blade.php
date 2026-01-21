<form wire:submit="store" class="space-y-4 w-full overflow-hidden">
    <div>
        <label class="block text-sm font-medium text-black dark:text-white">Title *</label>
        <input
            type="text"
            wire:model="title"
            class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
            placeholder="Event title"
        >
        @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-black dark:text-white">Description</label>
        <textarea
            wire:model="description"
            class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
            rows="3"
            placeholder="Event description"
        ></textarea>
        @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        <div>
            <label class="block text-sm font-medium text-black dark:text-white">Location</label>
            <input
                type="text"
                wire:model="location"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                placeholder="Country/City"
            >
            @error('location') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-black dark:text-white">Venue Name</label>
            <input
                type="text"
                wire:model="venue_name"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                placeholder="Venue name"
            >
            @error('venue_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
        <div>
            <label class="block text-sm font-medium text-black dark:text-white">Fee</label>
            <input
                type="number"
                step="0.01"
                wire:model="fee"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                placeholder="0.00"
            >
            @error('fee') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-black dark:text-white">Currency</label>
            <input
                type="text"
                wire:model="currency"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                placeholder="USD"
                maxlength="3"
            >
            @error('currency') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-black dark:text-white">Capacity *</label>
            <input
                type="number"
                wire:model="venue_capacity"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                placeholder="100"
                min="1"
            >
            @error('venue_capacity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        <div>
            <label class="block text-sm font-medium text-black dark:text-white">Start Date *</label>
            <input
                type="date"
                wire:model="start_date"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white"
            >
            @error('start_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-black dark:text-white">Start Time *</label>
            <input
                type="text"
                id="start_time"
                wire:model="start_time"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                placeholder="HH:MM"
            >
            @error('start_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        <div>
            <label class="block text-sm font-medium text-black dark:text-white">End Date</label>
            <input
                type="date"
                wire:model="end_date"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white"
            >
            @error('end_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-black dark:text-white">End Time</label>
            <input
                type="text"
                id="end_time"
                wire:model="end_time"
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-black dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                placeholder="HH:MM"
            >
            @error('end_time') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <button
        type="submit"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg w-full"
    >
        Create Event
    </button>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#start_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
        flatpickr("#end_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
    });
</script>
@endpush
