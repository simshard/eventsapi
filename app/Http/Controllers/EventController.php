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
        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //$event = Event::find($event);
        if(!$event){
            return response()->json(['message' => 'Event not found'], 404);
        }

        return view('event', [
            'event' => $event
         ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //$event = Event::find($event);
        $event->update($request->all());
        return response()->json($event,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event = Event::destroy($event);
        return response()->json(null,204);
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
}
