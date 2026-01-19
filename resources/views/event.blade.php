<x-layouts::app :title="__('Event Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-4">{{ $event->title }}</h1>
                <p class="text-lg">{{ $event->description }}</p>
                <p class="mt-4 text-sm text-gray-600">Date: {{ $event->date }}</p>
                <p class="mt-1 text-sm text-gray-600">Location: {{ $event->location }}</p>
            </div>
        </div>
    </div>
</x-layouts::app>
