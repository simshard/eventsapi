<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Services\EventServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * EventController
 *
 * Handles HTTP requests for event management
 */
class EventController extends Controller
{
    public function __construct(private EventServiceInterface $eventService) {}

    /**
     * Get all events or user's events with pagination and filtering
     *
     * @param Request $request HTTP request with query parameters
     * @return JsonResponse JSON response with events and pagination metadata
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $search = $request->query('search');
        $filter = $request->query('filter');
        $userId = auth('sanctum')->id();

        if ($filter === 'my-events' && $userId) {
            $events = $this->eventService->getUserEvents($userId, $perPage);
        } else {
            $events = $this->eventService->getAllEvents($perPage, $search);
        }

        return response()->json([
            'data' => $events->items(),
            'meta' => [
                'total' => $events->total(),
                'per_page' => $events->perPage(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
            ],
        ]);
    }

    /**
     * Create a new event
     *
     * @param StoreEventRequest $request Validated event creation request
     * @return JsonResponse JSON response with created event (201 Created)
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $data = $request->validated();
        $event = $this->eventService->createEvent(auth('sanctum')->id(), $data);

        return response()->json(['data' => $event], 201);
    }

    /**
     * Retrieve a single event by ID
     *
     * @param Event $event The event instance (injected via route model binding)
     * @return JsonResponse JSON response with event
     */
    public function show(Event $event): JsonResponse
    {
        return response()->json(['data' => $event]);
    }

    /**
     * Update an existing event (owner only)
     *
     * @param UpdateEventRequest $request Validated event update request
     * @param Event $event The event instance (injected via route model binding)
     * @return JsonResponse JSON response with updated event or 403 Forbidden
     */
    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $userId = auth('sanctum')->id();
        if (!$userId || $userId !== $event->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();
        $updatedEvent = $this->eventService->updateEvent($event, $data);

        return response()->json(['data' => $updatedEvent]);
    }

    /**
     * Delete an event (owner only, no existing bookings)
     *
     * @param Event $event The event instance (injected via route model binding)
     * @return JsonResponse JSON response (204 No Content or 403/422 errors)
     */
    public function destroy(Event $event): JsonResponse
    {
        $userId = auth('sanctum')->id();
        if (!$userId || $userId !== $event->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($event->bookings()->exists()) {
            return response()->json(['message' => 'Cannot delete event with existing bookings'], 422);
        }

        $this->eventService->deleteEvent($event);
        return response()->json(null, 204);
    }
}
