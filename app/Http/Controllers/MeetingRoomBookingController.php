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
                ->editColumn('start_time', fn ($booking) => date('d-m-Y h:i a', strtotime($booking->start_time)))
                ->editColumn('end_time', fn ($booking) => date('d-m-Y h:i a', strtotime($booking->end_time)))
                ->editColumn('status', fn ($booking) => $this->formatBookingStatus($booking->status))
                ->addColumn('action', fn ($row) => $this->generateActionButtons($row))
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.booking.index');
    }

    private function formatBookingStatus($status)
    {
        $statusLabels = [
            'Approved' => 'success',
            'Pending' => 'warning',
            'Canceled' => 'danger',
        ];

        $badgeClass = $statusLabels[$status] ?? 'secondary';
        return "<span class=\"badge bg-$badgeClass\">$status</span>";
    }

    private function generateActionButtons($row)
    {
        $editButton = sprintf('<a href="javascript:void(0)" data-toggle="tooltip" data-id="%d" data-original-title="Edit" class="edit btn btn-xs edit-item"><i class="fas fa-edit"></i></a>', $row->id);
        $deleteButton = sprintf('<a href="javascript:void(0)" data-toggle="tooltip" data-id="%d" data-original-title="Delete" class="btn btn-xs text-danger delete-item"><i class="fas fa-trash"></i></a>', $row->id);

        return $editButton . ' ' . $deleteButton;
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
