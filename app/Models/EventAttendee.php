<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
/**
 * EventAttendee Pivot Model
 *
 * Links Events and Users in a many-to-many relationship
 * Represents users attending events
 */
class EventAttendee extends Pivot
{
    protected $table = 'attendees';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = ['event_id', 'user_id'];
}
