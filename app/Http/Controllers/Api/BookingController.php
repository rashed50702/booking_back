<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\MeetingRoomBooking;
use App\Http\Controllers\Controller;
use App\Services\MeetingRoomAvailabilityService;
use App\Http\Requests\CreateMeetingRoomBookingRequest;

class BookingController extends Controller
{
    protected $availabilityService;

    public function __construct(MeetingRoomAvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    public function check(Request $request)
    {
        $start_time = Carbon::parse($request->start_time);
        $end_time = Carbon::parse($request->end_time);
        $user_id = $request->user_id;

        $response = $this->availabilityService->checkAvailability($start_time, $end_time, $user_id);

        return response()->json($response);
    }

    public function book_now(CreateMeetingRoomBookingRequest $request)
    {
        $validatedData = $request->validated();

        $booking = MeetingRoomBooking::create($validatedData);
        return response()->json(['message' => 'Booking created successfully', 'data' => $booking]);
    }


    public function my_bookings($id)
    {
        return MeetingRoomBooking::with('room')->where('user_id', $id)->get();
    }
}
