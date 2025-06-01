<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomBooking;
use App\Models\Room;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomBookingsController extends Controller
{
    public function index()
    {
        $bookings = RoomBooking::with(['room', 'teacher'])
            ->orderBy('start_time', 'desc')
            ->paginate(10);
        return view('admin.room-bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::all();
        $teachers = Teacher::all();
        return view('admin.room-bookings.create', compact('rooms', 'teachers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'teacher_id' => 'required|exists:teachers,teacher_id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pending,approved,rejected,cancelled',
        ], [
            'room_id.required' => 'Please select a room.',
            'room_id.exists' => 'The selected room is invalid.',
            'teacher_id.required' => 'Please select a teacher.',
            'teacher_id.exists' => 'The selected teacher is invalid.',
            'start_time.required' => 'Start time is required.',
            'start_time.after' => 'Start time must be in the future.',
            'end_time.required' => 'End time is required.',
            'end_time.after' => 'End time must be after start time.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for booking conflicts
        $conflict = RoomBooking::where('room_id', $request->room_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withErrors(['start_time' => 'This room is already booked for the selected time slot.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();
            RoomBooking::create([
                'room_id' => $request->room_id,
                'teacher_id' => $request->teacher_id,
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
                'status' => $request->status,
            ]);
            DB::commit();
            return redirect()->route('admin.room-bookings.index')
                ->with('success', 'Room booking created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Creation Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to create booking. Please try again.')
                ->withInput();
        }
    }

    public function show(RoomBooking $roomBooking)
    {
        $roomBooking->load(['room', 'teacher']);
        return view('admin.room-bookings.show', compact('roomBooking'));
    }

    public function edit(RoomBooking $roomBooking)
    {
        $rooms = Room::all();
        $teachers = Teacher::all();
        return view('admin.room-bookings.edit', compact('roomBooking', 'rooms', 'teachers'));
    }

    public function update(Request $request, RoomBooking $roomBooking)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'teacher_id' => 'required|exists:teachers,teacher_id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pending,approved,rejected,cancelled',
        ], [
            'room_id.required' => 'Please select a room.',
            'room_id.exists' => 'The selected room is invalid.',
            'teacher_id.required' => 'Please select a teacher.',
            'teacher_id.exists' => 'The selected teacher is invalid.',
            'start_time.required' => 'Start time is required.',
            'start_time.after' => 'Start time must be in the future.',
            'end_time.required' => 'End time is required.',
            'end_time.after' => 'End time must be after start time.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for booking conflicts, excluding current booking
        $conflict = RoomBooking::where('room_id', $request->room_id)
            ->where('booking_id', '!=', $roomBooking->booking_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withErrors(['start_time' => 'This room is already booked for the selected time slot.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $roomBooking->update([
                'room_id' => $request->room_id,
                'teacher_id' => $request->teacher_id,
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
                'status' => $request->status,
            ]);
            DB::commit();
            return redirect()->route('admin.room-bookings.index')
                ->with('success', 'Room booking updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Update Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to update booking. Please try again.')
                ->withInput();
        }
    }

    public function destroy(RoomBooking $roomBooking)
    {
        try {
            DB::beginTransaction();
            $roomBooking->delete();
            DB::commit();
            return redirect()->route('admin.room-bookings.index')
                ->with('success', 'Room booking deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Deletion Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to delete booking. Please try again.');
        }
    }
}