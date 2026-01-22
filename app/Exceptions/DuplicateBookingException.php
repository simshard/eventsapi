<?php

namespace App\Exceptions;

use Exception;

/**
 * DuplicateBookingException
 *
 * Thrown when user tries to book the same event twice
 */
class DuplicateBookingException extends Exception
{
    /**
     * Create a new exception instance
     *
     * @param int $userId The user ID
     * @param int $eventId The event ID
     */
    public function __construct(int $userId, int $eventId)
    {
        $this->message = "User {$userId} has already booked event {$eventId}.";
        parent::__construct();
    }
}
