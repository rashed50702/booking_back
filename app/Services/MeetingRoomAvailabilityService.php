<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;

class MeetingRoomAvailabilityService
{
    public function checkAvailability(Carbon $start_time, Carbon $end_time, ?int $user_id = null)
    {
        $availableRooms = Room::with(['bookings' => function ($query) use ($start_time, $end_time) {
            $query->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                    ->orWhereBetween('end_time', [$start_time, $end_time])
                    ->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', '<', $start_time)
                            ->where('end_time', '>', $end_time);
                    });
            });
        }])
            ->get();

        // Modify response structure
        $response = $availableRooms->map(function ($room) use ($start_time, $end_time, $user_id) {
            $response = [
                'room_id' => $room->id,
                'room_name' => $room->name,
                'is_booked' => $room->bookings->isNotEmpty()
            ];

            if ($response['is_booked']) {
                $booking = $room->bookings->first();
                $response['start_time'] = $booking->start_time;
                $response['end_time'] = $booking->end_time;
                if ($user_id !== null) {
                    $response['user_id'] = $booking->user_id;
                }
            } else {
                $response['start_time'] = $start_time;
                $response['end_time'] = $end_time;
                if ($user_id !== null) {
                    $response['user_id'] = $user_id;
                }
            }

            return $response;
        });

        return $response;
    }
}