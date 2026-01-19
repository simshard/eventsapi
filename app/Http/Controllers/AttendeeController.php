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
 /**
     * Get all attendees for an event
     */
    public function index(int $eventId)
    {
        $attendees = $this->service->getEventAttendees($eventId);
        $count = $this->service->getAttendeeCount($eventId);

        return response()->json([
            'event_id' => $eventId,
            'total_attendees' => $count,
            'attendees' => $attendees,
        ]);
    }

     /**
     * Update attendee information
     */
    public function update(UpdateAttendeeRequest $request, Attendee $attendee)
    {
        try {
            $updated = $this->service->updateAttendee($attendee, $request->validated());
            return response()->json($updated, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Unregister an attendee
     */
    public function destroy(Attendee $attendee)
    {
        $this->service->unregisterAttendee($attendee);
        return response()->json(null, 204);
    }


}
/*
✅ Register attendees without authentication
✅ Validate capacity before registration
✅ Prevent duplicate email registrations per event
✅ Manage attendee information (CRUD)
✅ Get attendee counts and lists
✅ Centralized attendee business logic

*/
