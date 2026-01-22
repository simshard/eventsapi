<?php

namespace App\Exceptions;

use Exception;

/**
 * EventCapacityException
 *
 * Thrown when booking would exceed event capacity
 */
class EventCapacityException extends Exception
{
    /**
     * Create a new exception instance
     *
     * @param int $eventId The event ID
     * @param int $availableSpots Available spots remaining
     */
    public function __construct(int $eventId, int $availableSpots)
    {
        $this->message = "Event {$eventId} is full. Only {$availableSpots} spots available.";
        parent::__construct();
    }
}
