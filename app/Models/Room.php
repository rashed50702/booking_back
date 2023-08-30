<?php

namespace App\Models;

use App\Models\MeetingRoomBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function bookings()
    {
        return $this->hasMany(MeetingRoomBooking::class, 'room_id');
    }
}
