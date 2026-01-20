<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'venue_name',
        'fee',
        'currency',
        'venue_capacity',
        'start_time',
        'end_time',
        'user_id',
    ];

        protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the owner of the event.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');

    }

    /**
     * Bookings for this event
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'event_id');
    }

    /**
     * Attendees registered for this event
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class, 'event_id');
    }

    /**
     * Get count of confirmed bookings
     */
    public function getBookingCountAttribute(): int
    {
        return $this->bookings()->count();
    }

    /**
     * Check if event is at full capacity
     */
    public function isAtCapacity(): bool
    {
        return $this->bookings()->count() >= $this->venue_capacity;
    }

    /**
     * Get available spots
     */
    public function availableSpots(): int
    {
        return max(0, $this->venue_capacity - $this->bookings()->count());
    }

    /**
     * Check if event has already started
     */
    public function hasStarted(): bool
    {
        return now()->isAfter($this->start_time);
    }

    /**
     * Check if event is happening now
     */
    public function isHappening(): bool
    {
        return now()->isBetween($this->start_time, $this->end_time ?? $this->start_time);
    }

    /**
     * Scope: Get upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())->orderBy('start_time');
    }

    /**
     * Scope: Get past events
     */
    public function scopePast($query)
    {
        return $query->where('start_time', '<', now())->orderBy('start_time', 'desc');
    }

    /**
     * Scope: Get events by location
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope: Get events with available capacity
     */
    public function scopeWithAvailability($query)
    {
        return $query->whereRaw('venue_capacity > (SELECT COUNT(*) FROM bookings WHERE bookings.event_id = events.id)');
    }


}
