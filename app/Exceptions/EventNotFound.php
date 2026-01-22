<?php

namespace App\Exceptions;

use Exception;

/**
 * EventNotFoundException
 *
 * Thrown when an event cannot be found
 */
class EventNotFoundException extends Exception
{
    /**
     * Create a new exception instance
     *
     * @param int $eventId The event ID that was not found
     */
    public function __construct(int $eventId)
    {
        $this->message = "Event with ID {$eventId} not found.";
        parent::__construct();
    }
}
