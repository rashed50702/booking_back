<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MeetingRoomBooking;
use Illuminate\Support\Facades\DB;
use App\Services\MeetingRoomAvailabilityService;
use App\Http\Requests\CreateMeetingRoomBookingRequest;

class MeetingRoomBookingController extends Controller
{
    protected $availabilityService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MeetingRoomAvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
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
                    switch ($booking->status) {
                        case "Approved":
                            return '<span class="badge bg-success">Approved</span>';
                        case "Pending":
                            return '<span class="badge bg-warning">Pending</span>';
                        case "Canceled":
                            return '<span class="badge bg-danger">Canceled</span>';
                        default:
                            return '';
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
        $start_time = Carbon::parse($request->input('start_time'));
        $end_time = Carbon::parse($request->input('end_time'));

        // $availableRooms = Room::with(['bookings' => function ($query) use ($start_time, $end_time) {
        //     $query->where(function ($query) use ($start_time, $end_time) {
        //         $query->whereBetween('start_time', [$start_time, $end_time])
        //             ->orWhereBetween('end_time', [$start_time, $end_time])
        //             ->orWhere(function ($query) use ($start_time, $end_time) {
        //                 $query->where('start_time', '<', $start_time)
        //                     ->where('end_time', '>', $end_time);
        //             });
        //     });
        // }])
        // ->get();

        // // Modify response structure
        // $response = $availableRooms->map(function ($room) use ($start_time, $end_time) {
        //     $response = [
        //         'room_id' => $room->id,
        //         'room_name' => $room->name,
        //         'is_booked' => $room->bookings->isNotEmpty()
        //     ];

        //     if ($response['is_booked']) {
        //         $booking = $room->bookings->first(); // Assuming one booking for simplicity
        //         $response['start_time'] = $booking->start_time;
        //         $response['end_time'] = $booking->end_time;
        //     } else {
        //         $response['start_time'] = $start_time;
        //         $response['end_time'] = $end_time;
        //     }

        //     return $response;
        // });
        $response = $this->availabilityService->checkAvailability($start_time, $end_time);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMeetingRoomBookingRequest $request)
    {
        // return $request->all();
        $validatedData = $request->validated();
        $validatedData['status'] = 'Approved';

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
        return MeetingRoomBooking::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //updating status
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
