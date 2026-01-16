<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    protected $guarded = [];


    /**
     * Get the owner of the event.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');

    }

    /**
     * Determine if the event is free to attend.
     */
    public function isFree(): bool
    {
        return $this->fee === null || $this->fee->isZero();
    }
}
