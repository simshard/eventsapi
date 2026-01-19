<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'registration_date',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];


    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Accessor: Check if attendee has booked this event
    public function booking()
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
