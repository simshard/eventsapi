<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
    ];

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    // Scope: Get attendees by event
    public function scopeByEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    // Scope: Search by email or name
    public function scopeSearch($query, $term)
    {
        return $query->where('email', 'like', "%{$term}%")
                     ->orWhere('name', 'like', "%{$term}%");
    }
}
