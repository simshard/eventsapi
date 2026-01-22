<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\EventServiceInterface;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollectionResource;
use App\Http\Responses\ApiResponse;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * EventController
 *
 * Handles event API endpoints with proper response serialization
 */
class EventController extends Controller
{
    use AuthorizesRequests;
    private EventServiceInterface $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Get all events (paginated)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $events = $this->eventService->getAllEvents();

            return ApiResponse::success(
                new EventCollectionResource($events),
                'Events retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a single event by ID
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        try {
            return ApiResponse::success(
                new EventResource($event),
                'Event retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new event
     *
     * @param StoreEventRequest $request
     * @return JsonResponse
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        try {
            $event = $this->eventService->createEvent(
                auth()->id(),
                $request->validated()
            );

            return ApiResponse::success(
                new EventResource($event),
                'Event created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   /**
 * Update an event
 *
 * @param Event $event
 * @param UpdateEventRequest $request
 * @return JsonResponse
 */
public function update(Event $event, UpdateEventRequest $request): JsonResponse
{
    try {
        // Check authorization first
        if ($this->authorize('update', $event) === false) {
            return ApiResponse::error(
                'You are not authorized to update this event',
                Response::HTTP_FORBIDDEN
            );
        }

        $event = $this->eventService->updateEvent($event, $request->validated());

        return ApiResponse::success(
            new EventResource($event),
            'Event updated successfully'
        );
    } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
        \Log::warning('Authorization denied for event update', [
            'user_id' => auth()->id(),
            'event_id' => $event->id,
        ]);
        return ApiResponse::error(
            'You are not authorized to update this event',
            Response::HTTP_FORBIDDEN
        );
    } catch (\Exception $e) {
        \Log::error('Error updating event', ['error' => $e->getMessage()]);
        return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    /**
     * Delete an event
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function destroy(Event $event): JsonResponse
    {
        try {
            $this->authorize('delete', $event);

            $this->eventService->deleteEvent($event);

            return ApiResponse::success(
                null,
                'Event deleted successfully',
                Response::HTTP_NO_CONTENT
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
