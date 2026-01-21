<?php

namespace App\Http\Controllers;

use App\Services\AttendeeService;
use App\Http\Requests\StoreAttendeeRequest;
use App\Http\Requests\UpdateAttendeeRequest;
use App\Models\Attendee;
use Exception;

class AttendeeController extends Controller
{
    public function __construct(private AttendeeService $attendeeService) {}

    public function store(StoreAttendeeRequest $request)
    {
        try {
            $attendee = $this->attendeeService->registerAttendee(
                $request->event_id,
                $request->validated()
            );
            return response()->json($attendee, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function index(int $eventId)
    {
        $attendees = $this->attendeeService->getEventAttendees($eventId);
        $count = $this->attendeeService->getAttendeeCount($eventId);

        return response()->json([
            'event_id' => $eventId,
            'total_attendees' => $count,
            'attendees' => $attendees,
        ]);
    }

    public function update(UpdateAttendeeRequest $request, Attendee $attendee)
    {
        try {
            $updated = $this->attendeeService->updateAttendee($attendee, $request->validated());
            return response()->json($updated, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(Attendee $attendee)
    {
        $this->attendeeService->unregisterAttendee($attendee);
        return response()->json(null, 204);
    }
}
