<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\MeetingRoomBooking;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMeetingRoomBookingRequest;

class BookingController extends Controller
{
    public function check(Request $request)
    {
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $user_id = $request->user_id;

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
                $booking = $room->bookings->first(); // Assuming one booking for simplicity
                $response['start_time'] = $booking->start_time;
                $response['end_time'] = $booking->end_time;
                $response['user_id'] = $booking->user_id;
            } else {
                $response['start_time'] = $start_time;
                $response['end_time'] = $end_time;
                $response['user_id'] = $user_id;
            }

            return $response;
        });

        return response()->json($response);
    }

    public function book_now(CreateMeetingRoomBookingRequest $request)
    {
        $validatedData = $request->validated();

        $booking = MeetingRoomBooking::create($validatedData);
        return response()->json(['message' => 'Booking created successfully', 'data' => $booking]);
    }
}
