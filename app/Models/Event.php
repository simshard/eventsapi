<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Event Model
 *
 * Represents an event in the application
 * Events are created by users and can have multiple bookings
 *
 * @property int $id Primary key
 * @property int $user_id Foreign key to users table (event owner)
 * @property string $title Event title
 * @property string $description Event description
 * @property string $location Event location/venue
 * @property \DateTime $start_time Event start date and time
 * @property \DateTime $end_time Event end date and time
 * @property int $capacity Maximum number of attendees
 * @property decimal $price Ticket price (nullable)
 * @property \DateTime $created_at Timestamp when event was created
 * @property \DateTime $updated_at Timestamp when event was last updated
 *
 * @property-read User $user The user who created the event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Booking> $bookings Event bookings
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'capacity',
        'price',
    ];

    /**
     * The attributes that should be cast
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'decimal:2',
        'capacity' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the user that owns the event
     *
     * @return BelongsTo Relationship to User model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all bookings for this event
     *
     * @return HasMany Relationship to Booking model
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the number of available spots for this event
     *
     * @return int Number of remaining capacity
     */
    public function getAvailableSpots(): int
    {
        return $this->capacity - $this->bookings()->count();
    }

    /**
     * Check if the event has available capacity
     *
     * @return bool True if spots are available, false if full
     */
    public function hasAvailableSpots(): bool
    {
        return $this->getAvailableSpots() > 0;
    }

    /**
     * Check if the event has started
     *
     * @return bool True if event start time is in the past
     */
    public function hasStarted(): bool
    {
        return $this->start_time <= now();
    }

    /**
     * Check if the event has ended
     *
     * @return bool True if event end time is in the past
     */
    public function hasEnded(): bool
    {
        return $this->end_time <= now();
    }

    /**
     * Check if the event is currently happening
     *
     * @return bool True if event is between start and end time
     */
    public function isOngoing(): bool
    {
        return $this->hasStarted() && !$this->hasEnded();
    }

    /**
     * Check if the event is in the future
     *
     * @return bool True if event start time is in the future
     */
    public function isUpcoming(): bool
    {
        return $this->start_time > now();
    }

    /**
     * Get the duration of the event in minutes
     *
     * @return int Duration in minutes
     */
    public function getDurationInMinutes(): int
    {
        return (int) $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Get the duration of the event in hours
     *
     * @return float Duration in hours (with decimal precision)
     */
    public function getDurationInHours(): float
    {
        return round($this->start_time->diffInHours($this->end_time, absolute: true) +
                     ($this->start_time->diffInMinutes($this->end_time, absolute: true) % 60) / 60, 2);
    }

    /**
     * Get the number of confirmed bookings for this event
     *
     * @return int Count of confirmed bookings
     */
    public function getConfirmedBookingsCount(): int
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->count();
    }

    /**
     * Get the percentage of capacity booked
     *
     * @return float Percentage of capacity booked (0-100)
     */
    public function getBookingPercentage(): float
    {
        if ($this->capacity === 0) {
            return 0;
        }

        return round(($this->getConfirmedBookingsCount() / $this->capacity) * 100, 2);
    }

    /**
     * Scope: Filter events by upcoming (start_time in future)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    /**
     * Scope: Filter events by past (end_time in past)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('end_time', '<=', now());
    }

    /**
     * Scope: Filter events by user
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId The user ID to filter by
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter events that have available capacity
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAvailableCapacity($query)
    {
        return $query->whereRaw('capacity > (SELECT COUNT(*) FROM bookings WHERE bookings.event_id = events.id)');
    }

    /**
     * Scope: Search events by title or description
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm The search term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where('title', 'like', "%{$searchTerm}%")
                     ->orWhere('description', 'like', "%{$searchTerm}%");
    }
}
