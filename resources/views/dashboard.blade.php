<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">


        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
             <h1 class="font-bold text-2xl">  All Events </h1>
                             {{-- <div>
                    @forelse($allEvents as $event)
                        <div class="p-4 border-b">
                            <h3 class="font-semibold">{{ $event->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $event->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">No events available</p>
                    @endforelse
                </div> --}}

               @foreach($allEvents as $event)
             <div>{{ $event->title }}</div>
              @endforeach






            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />

            <h1 class="font-bold text-2xl"> {{ auth()->user()->name }} : My events </h1>

                {{-- <div>
                    @forelse($myEvents as $event)
                        <div class="p-4 border-b">
                            <h3 class="font-semibold">{{ $event->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $event->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">You have no events</p>
                    @endforelse
                </div> --}}



               @foreach($myEvents as $myevent)
             <div>{{ $myevent->title }}</div>
              @endforeach
            </div>

        </div>
    </div>
</x-layouts::app>
