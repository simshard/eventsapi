<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'venue_name',
        'fee',
        'currency',
        'venue_capacity',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'title' => 'required|string',
            'start_time' => 'required|datetime',
            'end_time' => 'required|datetime|after:start_time',
            'venue_capacity' => 'required|integer|min:1',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function getAvailableCapacityAttribute()
    {
        $confirmedBookings = $this->bookings()
            ->where('status', 'confirmed')
            ->count();
        return $this->venue_capacity - $confirmedBookings;
    }

    public function getIsFullyBookedAttribute()
    {
        $confirmedBookings = $this->bookings()
            ->where('status', 'confirmed')
            ->count();
        return $confirmedBookings >= $this->venue_capacity;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

public function scopePast($query)
    {
        return $query->where('start_time', '<', now());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('venue_capacity > (SELECT COUNT(*) FROM bookings WHERE bookings.event_id = events.id AND bookings.status = "confirmed")');
    }
}
