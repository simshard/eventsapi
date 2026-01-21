<?php

namespace App\Services;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\Booking;
use App\Repositories\AttendeeRepository;
use Exception;

class AttendeeService
{
    public function __construct(private AttendeeRepository $repository) {}

    public function registerAttendee(int $eventId, array $data): Attendee
    {
        $event = Event::findOrFail($eventId);

        $confirmedCount = Booking::where('event_id', $eventId)
            ->where('status', 'confirmed')
            ->count();

        if ($confirmedCount >= $event->venue_capacity) {
            throw new Exception('Event is at full capacity');
        }

        $attendee = $this->repository->create(array_merge($data, ['event_id' => $eventId]));

        Booking::create([
            'event_id' => $eventId,
            'attendee_id' => $attendee->id,
            'user_id' => auth()->id() ?? null,
            'status' => 'confirmed',
        ]);

        return $attendee;
    }

    public function getEventAttendees(int $eventId): array
    {
        $attendees = Attendee::byEvent($eventId)->get();

        return $attendees->map(function ($attendee) {
            $booking = Booking::where('attendee_id', $attendee->id)->first();
            return [
                'id' => $attendee->id,
                'name' => $attendee->name,
                'email' => $attendee->email,
                'phone' => $attendee->phone,
                'booking_status' => $booking?->status ?? 'unconfirmed',
                'booked_at' => $booking?->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    public function getAttendeeCount(int $eventId): int
    {
        return Booking::where('event_id', $eventId)
            ->where('status', 'confirmed')
            ->count();
    }

    public function updateAttendee(Attendee $attendee, array $data): Attendee
    {
        if (isset($data['status']) && $data['status'] === 'cancelled') {
            $booking = Booking::where('attendee_id', $attendee->id)->first();
            if ($booking) {
                $booking->update(['status' => 'cancelled']);
            }
        }

        $attendee->update($data);
        return $attendee;
    }

    public function unregisterAttendee(Attendee $attendee): void
    {
        $booking = Booking::where('attendee_id', $attendee->id)->first();
        if ($booking) {
            $booking->update(['status' => 'cancelled']);
        }
        $attendee->delete();
    }
}
