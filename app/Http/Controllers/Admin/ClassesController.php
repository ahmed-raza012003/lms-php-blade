<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Room;
use App\Models\Teacher;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = Classes::with(['workspace', 'teacher', 'room'])->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $workspaces = Workspace::all();
        $teachers = Teacher::all();
        $rooms = Room::all();
        $school_levels = ['Primary', 'Secondary', 'Higher Secondary'];
        $recurrence_patterns = ['Daily', 'Weekly', 'Monthly'];
        return view('admin.classes.create', compact('workspaces', 'teachers', 'rooms', 'school_levels', 'recurrence_patterns'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'workspace_id' => 'required|exists:workspaces,workspace_id',
                'teacher_id' => 'required|exists:teachers,teacher_id',
                'room_id' => 'required|exists:rooms,room_id',
                'subject' => 'required|string|max:255',
                'school_level' => ['required', Rule::in(['Primary', 'Secondary', 'Higher Secondary'])],
                'price' => 'required|numeric|min:0',
                'start_time' => 'required|date|after:now',
                'end_time' => 'required|date|after:start_time',
                'is_recurring' => 'required|boolean',
                'recurrence_pattern' => 'required_if:is_recurring,1|nullable|in:Daily,Weekly,Monthly',
                'status' => ['required', Rule::in(['pending', 'approved', 'cancelled'])],
            ], [
                'workspace_id.required' => 'Please select a workspace.',
                'workspace_id.exists' => 'The selected workspace does not exist.',
                'teacher_id.required' => 'Please select a teacher.',
                'teacher_id.exists' => 'The selected teacher does not exist.',
                'room_id.required' => 'Please select a room.',
                'room_id.exists' => 'The selected room does not exist.',
                'subject.required' => 'The subject is required.',
                'subject.max' => 'The subject must not exceed 255 characters.',
                'school_level.required' => 'Please select a school level.',
                'school_level.in' => 'Invalid school level selected.',
                'price.required' => 'The price is required.',
                'price.numeric' => 'The price must be a number.',
                'price.min' => 'The price cannot be negative.',
                'start_time.required' => 'Start time is required.',
                'start_time.date' => 'Start time must be a valid date.',
                'start_time.after' => 'Start time must be in the future.',
                'end_time.required' => 'End time is required.',
                'end_time.date' => 'End time must be a valid date.',
                'end_time.after' => 'End time must be after start time.',
                'is_recurring.required' => 'Please specify if the class is recurring.',
                'is_recurring.boolean' => 'Recurring status must be yes or no.',
                'recurrence_pattern.required_if' => 'Recurrence pattern is required when the class is recurring.',
                'recurrence_pattern.in' => 'Invalid recurrence pattern selected.',
                'status.required' => 'Please select a status.',
                'status.in' => 'Invalid status selected.',
            ]);

            // Check room availability
            $conflict = Classes::where('room_id', $request->room_id)
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
                return back()->withInput()->with('error', 'The room is already booked for the selected time slot.');
            }

            // Check teacher availability
            $teacher_conflict = Classes::where('teacher_id', $request->teacher_id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                          ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                          });
                })
                ->exists();

            if ($teacher_conflict) {
                return back()->withInput()->with('error', 'The teacher is already assigned to another class for the selected time slot.');
            }

            Classes::create($request->all());

            return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating class: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error creating class: ' . $e->getMessage());
        }
    }

    public function show(Classes $class)
    {
        $class->load(['workspace', 'teacher', 'room']);
        return view('admin.classes.show', compact('class'));
    }

    public function edit(Classes $class)
    {
        $workspaces = Workspace::all();
        $teachers = Teacher::all();
        $rooms = Room::all();
        $school_levels = ['Primary', 'Secondary', 'Higher Secondary'];
        $recurrence_patterns = ['Daily', 'Weekly', 'Monthly'];
        return view('admin.classes.edit', compact('class', 'workspaces', 'teachers', 'rooms', 'school_levels', 'recurrence_patterns'));
    }

    public function update(Request $request, Classes $class)
    {
        try {
            $request->validate([
                'workspace_id' => 'required|exists:workspaces,workspace_id',
                'teacher_id' => 'required|exists:teachers,teacher_id',
                'room_id' => 'required|exists:rooms,room_id',
                'subject' => 'required|string|max:255',
                'school_level' => ['required', Rule::in(['Primary', 'Secondary', 'Higher Secondary'])],
                'price' => 'required|numeric|min:0',
                'start_time' => 'required|date|after:now',
                'end_time' => 'required|date|after:start_time',
                'is_recurring' => 'required|boolean',
                'recurrence_pattern' => 'required_if:is_recurring,1|nullable|in:Daily,Weekly,Monthly',
                'status' => ['required', Rule::in(['pending', 'approved', 'cancelled'])],
            ], [
                'workspace_id.required' => 'Please select a workspace.',
                'teacher_id.required' => 'Please select a teacher.',
                'teacher_id.exists' => 'The selected teacher does not exist.',
                'room_id.required' => 'Please select a room.',
                'subject.required' => 'The subject is required.',
                'subject.max' => 'The subject must not exceed 255 characters.',
                'school_level.required' => 'Please select a school level.',
                'school_level.in' => 'Invalid school level selected.',
                'price.required' => 'The price is required.',
                'price.numeric' => 'The price must be a number.',
                'price.min' => 'The price cannot be negative.',
                'start_time.required' => 'Start time is required.',
                'start_time.date' => 'Start time must be a valid date.',
                'start_time.after' => 'Start time must be in the future.',
                'end_time.required' => 'End time is required.',
                'end_time.date' => 'End time must be a valid date.',
                'end_time.after' => 'End time must be after start time.',
                'is_recurring.required' => 'Please specify if the class is recurring.',
                'is_recurring.boolean' => 'Recurring status must be yes or no.',
                'recurrence_pattern.required_if' => 'Recurrence pattern is required when the class is recurring.',
                'recurrence_pattern.in' => 'Invalid recurrence pattern selected.',
                'status.required' => 'Please select a status.',
                'status.in' => 'Invalid status selected.',
            ]);

            // Check room availability (exclude current class)
            $conflict = Classes::where('room_id', $request->room_id)
                ->where('class_id', '!=', $class->class_id)
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
                return back()->withInput()->with('error', 'The room is already booked for the selected time slot.');
            }

            // Check teacher availability (exclude current class)
            $teacher_conflict = Classes::where('teacher_id', $request->teacher_id)
                ->where('class_id', '!=', $class->class_id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                          ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                          });
                })
                ->exists();

            if ($teacher_conflict) {
                return back()->withInput()->with('error', 'The teacher is already assigned to another class for the selected time slot.');
            }

            $class->update($request->all());

            return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating class: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error updating class: ' . $e->getMessage());
        }
    }

    public function destroy(Classes $class)
    {
        try {
            $class->delete();
            return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting class: ' . $e->getMessage());
            return back()->with('error', 'Error deleting class: ' . $e->getMessage());
        }
    }
}