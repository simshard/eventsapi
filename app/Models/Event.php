<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Event Model
 *
 * Represents an event in the application
 * Events are created by users and can have multiple bookings and attendees
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The table associated with the model
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'venue_capacity',
        'price',
    ];

    /**
     * The attributes that should be cast
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'decimal:2',
        'venue_capacity' => 'integer',
    ];

    /**
     * The accessors to append to model's array form
     */
    protected $appends = ['available_capacity', 'is_fully_booked'];

    /**
     * Get the user that owns the event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all bookings for this event
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

   /**
    * Get all attendees for this event (many-to-many through attendees table)
    *
    * @return BelongsToMany Relationship to User model through EventAttendee pivot
    */
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendees', 'event_id', 'user_id')
            ->using(EventAttendee::class);
    }


    /**
     * Get the number of available spots for this event
     */
    public function getAvailableSpots(): int
    {
        return $this->venue_capacity - $this->bookings()
            ->where('status', 'confirmed')
            ->count();
    }

    /**
     * Get available capacity as an accessor
     */
    public function getAvailableCapacityAttribute(): int
    {
        return $this->getAvailableSpots();
    }

    /**
     * Check if event is fully booked
     */
    public function getIsFullyBookedAttribute(): bool
    {
        return $this->getAvailableSpots() <= 0;
    }

    /**
     * Check if the event has available capacity
     */
    public function hasAvailableSpots(): bool
    {
        return $this->getAvailableSpots() > 0;
    }

    /**
     * Check if the event has started
     */
    public function hasStarted(): bool
    {
        return $this->start_time <= now();
    }

    /**
     * Check if the event has ended
     */
    public function hasEnded(): bool
    {
        return $this->end_time <= now();
    }

    /**
     * Check if the event is currently happening
     */
    public function isOngoing(): bool
    {
        return $this->hasStarted() && !$this->hasEnded();
    }

    /**
     * Check if the event is in the future
     */
    public function isUpcoming(): bool
    {
        return $this->start_time > now();
    }

    /**
     * Get the duration of the event in minutes
     */
    public function getDurationInMinutes(): int
    {
        return (int) $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Get the duration of the event in hours
     */
    public function getDurationInHours(): float
    {
        return round($this->start_time->diffInHours($this->end_time, absolute: true) +
                     ($this->start_time->diffInMinutes($this->end_time, absolute: true) % 60) / 60, 2);
    }

    /**
     * Get the number of confirmed bookings for this event
     */
    public function getConfirmedBookingsCount(): int
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->count();
    }

    /**
     * Get the percentage of capacity booked
     */
    public function getBookingPercentage(): float
    {
        if ($this->venue_capacity === 0) {
            return 0;
        }

        return round(($this->getConfirmedBookingsCount() / $this->venue_capacity) * 100, 2);
    }

    /**
     * Scope: Filter events by upcoming (start_time in future)
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    /**
     * Scope: Filter events by past (start_time in past)
     */
    public function scopePast($query)
    {
        return $query->where('start_time', '<=', now());
    }

    /**
     * Scope: Filter events by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter events that have available capacity
     */
    public function scopeWithAvailableCapacity($query)
    {
        return $query->whereRaw('venue_capacity > (SELECT COUNT(*) FROM bookings WHERE bookings.event_id = events.id AND bookings.status = "confirmed")');
    }

    /**
     * Scope: Alias for withAvailableCapacity
     */
    public function scopeAvailable($query)
    {
        return $this->scopeWithAvailableCapacity($query);
    }

    /**
     * Scope: Search events by title or description
     */
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where('title', 'like', "%{$searchTerm}%")
                     ->orWhere('description', 'like', "%{$searchTerm}%");
    }
}
