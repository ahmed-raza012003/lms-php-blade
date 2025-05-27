<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;

class RoomScheduleController extends Controller
{
     public function index()
    {
        $schedules = RoomSchedule::with('room')->paginate(10);
        return view('admin.room_schedules.index', compact('schedules'));
    }

    public function create()
    {
        $rooms = Room::all();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.room_schedules.create', compact('rooms', 'days'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:rooms,room_id',
                'day_of_week' => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'is_available' => 'required|boolean',
            ], [
                'room_id.required' => __('Please select a room.'),
                'room_id.exists' => __('The selected room does not exist.'),
                'day_of_week.required' => __('Please select a day of the week.'),
                'day_of_week.in' => __('Invalid day of the week.'),
                'start_time.required' => __('Start time is required.'),
                'start_time.date_format' => __('Start time must be in HH:MM format.'),
                'end_time.required' => __('End time is required.'),
                'end_time.date_format' => __('End time must be in HH:MM format.'),
                'end_time.after' => __('End time must be after start time.'),
                'is_available.required' => __('Availability status is required.'),
            ]);

            // Check for overlapping schedules
            $overlap = RoomSchedule::where('room_id', $request->room_id)
                ->where('day_of_week', $request->day_of_week)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                          ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                          });
                })
                ->exists();

            if ($overlap) {
                return back()->withInput()->with('error', __('This schedule overlaps with an existing schedule for the same room and day.'));
            }

            RoomSchedule::create($request->all());

            return redirect()->route('admin.room_schedules.index')->with('success', __('Schedule created successfully.'));
        } catch (\Exception $e) {
            Log::error('Error creating schedule: ' . $e->getMessage());
            return back()->withInput()->with('error', __('Error creating schedule: ') . $e->getMessage());
        }
    }

    public function show($schedule_id)
    {
        $schedule = RoomSchedule::with('room')->findOrFail($schedule_id);
        return view('admin.room_schedules.show', compact('schedule'));
    }

    public function edit($schedule_id)
    {
        $schedule = RoomSchedule::findOrFail($schedule_id);
        $rooms = Room::all();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.room_schedules.edit', compact('schedule', 'rooms', 'days'));
    }

    public function update(Request $request, $schedule_id)
    {
        try {
            $request->validate([
                'room_id' => 'required|exists:rooms,room_id',
                'day_of_week' => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'is_available' => 'required|boolean',
            ], [
                'room_id.required' => __('Please select a room.'),
                'room_id.exists' => __('The selected room does not exist.'),
                'day_of_week.required' => __('Please select a day of the week.'),
                'day_of_week.in' => __('Invalid day of the week.'),
                'start_time.required' => __('Start time is required.'),
                'start_time.date_format' => __('Start time must be in HH:MM format.'),
                'end_time.required' => __('End time is required.'),
                'end_time.date_format' => __('End time must be in HH:MM format.'),
                'end_time.after' => __('End time must be after start time.'),
                'is_available.required' => __('Availability status is required.'),
            ]);

            $schedule = RoomSchedule::findOrFail($schedule_id);

            // Check for overlapping schedules, excluding current schedule
            $overlap = RoomSchedule::where('room_id', $request->room_id)
                ->where('day_of_week', $request->day_of_week)
                ->where('schedule_id', '!=', $schedule_id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                          ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                          });
                })
                ->exists();

            if ($overlap) {
                return back()->withInput()->with('error', __('This schedule overlaps with an existing schedule for the same room and day.'));
            }

            $schedule->update($request->all());

            return redirect()->route('admin.room_schedules.index')->with('success', __('Schedule updated successfully.'));
        } catch (\Exception $e) {
            Log::error('Error updating schedule: ' . $e->getMessage());
            return back()->withInput()->with('error', __('Error updating schedule: ') . $e->getMessage());
        }
    }

    public function destroy($schedule_id)
    {
        try {
            $schedule = RoomSchedule::findOrFail($schedule_id);
            $schedule->delete();

            return redirect()->route('admin.room_schedules.index')->with('success', __('Schedule deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error deleting schedule: ' . $e->getMessage());
            return back()->with('error', __('Error deleting schedule: ') . $e->getMessage());
        }
    }
}
