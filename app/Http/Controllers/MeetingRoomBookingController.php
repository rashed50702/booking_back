<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MeetingRoomBooking;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateMeetingRoomBookingRequest;

class MeetingRoomBookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $booking = MeetingRoomBooking::with('room', 'user')->latest()->get();
            return DataTables::of($booking)
                ->addIndexColumn()
                ->editColumn('start_time', function($booking){
                    return date('d-m-Y h:i a', strtotime($booking->start_time));
                })
                ->editColumn('end_time', function ($booking) {
                    return date('d-m-Y h:i a', strtotime($booking->end_time));
                })
                ->editColumn('status', function ($booking) {
                    if($booking->status === "Approved"){
                        return '<span class="badge bg-success">Approved</span>';
                    }
                    if($booking->status === "Pending"){
                        return '<span class="badge bg-warning">Pending</span>';
                    }
                    if($booking->status === "Canceled") {
                        return '<span class="badge bg-danger">Canceled</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-xs edit-item"><i class="fas fa-edit"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-xs text-danger delete-item"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.booking.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.booking.create');
    }

    public function booking_checks(Request $request)
    {
        $startDateTime = Carbon::parse($request->input('start_time'));
        $endDateTime = Carbon::parse($request->input('end_time'));
        $room_id = $request->input('meeting_room');
        $room = Room::where('id', $room_id)->pluck('name');

        $overlapBookings = DB::table('meeting_room_bookings')
        ->where('room_id', $room_id)
        ->where(function ($query) use ($startDateTime, $endDateTime) {
            $query->whereBetween('start_time', [$startDateTime, $endDateTime])
                ->orWhereBetween('end_time', [$startDateTime, $endDateTime])
                ->orWhere(function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_time', '<', $startDateTime)
                        ->where('end_time', '>', $endDateTime);
                });
        })->count();

        if ($overlapBookings > 0) {
            return response()->json(['availability' => 'not available']);
        } else {

            return response()->json([
                'room_id' => $room_id,
                'room' => $room,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'availability' => 'available'
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMeetingRoomBookingRequest $request)
    {
        $validatedData = $request->validated();

        $booking = MeetingRoomBooking::create($validatedData);
        return response()->json(['message' => 'Booking created successfully', 'data' => $booking]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return MeetingRoomBooking::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        MeetingRoomBooking::where('id', $id)->update([
            'status' => $request->status,
        ]);
        return response()->json(['message' => 'Booking status updated successfully']); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MeetingRoomBooking::where('id', $id)->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
