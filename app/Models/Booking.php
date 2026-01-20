<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'booking_date',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    // Relationships
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event() : BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function attendee(): BelongsTo
    {
        return $this->belongsTo(Attendee::class);
    }

    // Scope: Get active/pending bookings
    public function scopeActive($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Scope: Get bookings by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope: Get bookings for event
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    // Check if user already booked this event
    public function scopeUserEventBooking($query, $userId, $eventId)
    {
        return $query->where('user_id', $userId)
                     ->where('event_id', $eventId)
                     ->where('status', 'confirmed');
    }

    // Scope: Filter by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
