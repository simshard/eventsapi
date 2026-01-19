<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $allEvents = \App\Models\Event::all();
         $myEvents = auth()->user()->events;
         return view('dashboard', [
             'allEvents' => $allEvents,
             'myEvents' => $myEvents,
         ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $event = Event::create($data);
        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // return view('event', [
        //     'event' => $event
        //  ]);

         return response()->json($event);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
         $this->authorize('update', $event);
        $event->update($request->validated());
        return response()->json($event, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
     $this->authorize('delete', $event);
        $event->delete();
        return response()->json(null, 204);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }


/*
✅ Pagination & filtering for list endpoints
✅ Date validation logic
✅ Capacity checking before operations
✅ Prevents deletion of events with bookings
✅ Availability calculation
✅ Centralized business rules

*/
// public function store(StoreEventRequest $request)
// {
//     $event = $this->eventService->createEvent(auth()->id(), $request->validated());
//     return response()->json($event, 201);
// }

// public function index()
// {
//     $allEvents = $this->eventService->getAllEvents();
//     $myEvents = $this->eventService->getUserEvents(auth()->id());

//     return response()->json([
//         'all_events' => $allEvents,
//         'my_events' => $myEvents,
//     ]);
// }



}
