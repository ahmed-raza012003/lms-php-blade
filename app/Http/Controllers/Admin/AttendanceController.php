<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['class', 'student', 'markedBy'])->paginate(10);
        return view('admin.attendance.index', compact('attendances'));
    }

    public function create()
    {
        Log::info('Entering create method for attendance');
        try {
            $classes = Classes::all();
            $students = Student::with('user')->get();
            $teachers = Teacher::with('user')->get();
            Log::info('Successfully loaded classes, students, and teachers for attendance create view', [
                'classes_count' => $classes->count(),
                'students_count' => $students->count(),
                'teachers_count' => $teachers->count(),
            ]);
            return view('admin.attendance.create', compact('classes', 'students', 'teachers'));
        } catch (\Exception $e) {
            Log::error('Error in create method for attendance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to load attendance creation form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        Log::info('Entering store method for attendance', [
            'input' => $request->all(),
        ]);

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,class_id',
            'student_id' => 'required|exists:students,student_id',
            'date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:present,absent,late',
            'marked_by' => 'required|exists:teachers,teacher_id',
        ]);

        try {
            Log::debug('Validated data for attendance creation', [
                'validated' => $validated,
            ]);

            $attendance = Attendance::create($validated);

            Log::info('Attendance record created successfully', [
                'attendance_id' => $attendance->attendance_id,
                'class_id' => $attendance->class_id,
                'student_id' => $attendance->student_id,
                'date' => $attendance->date,
                'status' => $attendance->status,
                'marked_by' => $attendance->marked_by,
            ]);

            return redirect()->route('admin.attendance.index')->with('success', 'Attendance recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating attendance record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return back()->with('error', 'Failed to record attendance. Please try again.')->withInput();
        }
    }

    public function show(Attendance $attendance)
    {
        Log::info('Entering show method for attendance', [
            'attendance_id' => $attendance->attendance_id,
        ]);
        try {
            $attendance->load(['class', 'student', 'markedBy']);
            Log::info('Successfully loaded attendance details', [
                'attendance_id' => $attendance->attendance_id,
                'class_id' => $attendance->class_id,
                'student_id' => $attendance->student_id,
                'date' => $attendance->date,
                'status' => $attendance->status,
                'marked_by' => $attendance->marked_by,
            ]);
            return view('admin.attendance.show', compact('attendance'));
        } catch (\Exception $e) {
            Log::error('Error in show method for attendance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attendance_id' => $attendance->attendance_id,
            ]);
            return back()->with('error', 'Failed to load attendance details. Please try again.');
        }
    }

    public function edit(Attendance $attendance)
    {
        Log::info('Entering edit method for attendance', [
            'attendance_id' => $attendance->attendance_id,
        ]);
        try {
            $classes = Classes::all();
            $students = Student::with('user')->get();
            $teachers = Teacher::with('user')->get();
            Log::info('Successfully loaded classes, students, and teachers for attendance edit view', [
                'attendance_id' => $attendance->attendance_id,
                'classes_count' => $classes->count(),
                'students_count' => $students->count(),
                'teachers_count' => $teachers->count(),
            ]);
            return view('admin.attendance.edit', compact('attendance', 'classes', 'students', 'teachers'));
        } catch (\Exception $e) {
            Log::error('Error in edit method for attendance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attendance_id' => $attendance->attendance_id,
            ]);
            return back()->with('error', 'Failed to load attendance edit form. Please try again.');
        }
    }

    public function update(Request $request, Attendance $attendance)
    {
        Log::info('Entering update method for attendance', [
            'attendance_id' => $attendance->attendance_id,
            'input' => $request->all(),
        ]);

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,class_id',
            'student_id' => 'required|exists:students,student_id',
            'date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:present,absent,late',
            'marked_by' => 'required|exists:teachers,teacher_id',
        ]);

        try {
            Log::debug('Validated data for attendance update', [
                'attendance_id' => $attendance->attendance_id,
                'validated' => $validated,
            ]);

            $attendance->update($validated);

            Log::info('Attendance record updated successfully', [
                'attendance_id' => $attendance->attendance_id,
                'class_id' => $attendance->class_id,
                'student_id' => $attendance->student_id,
                'date' => $attendance->date,
                'status' => $attendance->status,
                'marked_by' => $attendance->marked_by,
            ]);

            return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating attendance record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attendance_id' => $attendance->attendance_id,
                'input' => $request->all(),
            ]);
            return back()->with('error', 'Failed to update attendance. Please try again.')->withInput();
        }
    }

    public function destroy(Attendance $attendance)
    {
        Log::info('Entering destroy method for attendance', [
            'attendance_id' => $attendance->attendance_id,
        ]);

        try {
            $attendanceData = [
                'attendance_id' => $attendance->attendance_id,
                'class_id' => $attendance->class_id,
                'student_id' => $attendance->student_id,
                'date' => $attendance->date,
                'status' => $attendance->status,
                'marked_by' => $attendance->marked_by,
            ];

            $attendance->delete();

            Log::info('Attendance record deleted successfully', [
                'attendance_data' => $attendanceData,
            ]);

            return redirect()->route('admin.attendance.index')->with('success', 'Attendance deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting attendance record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attendance_id' => $attendance->attendance_id,
            ]);
            return back()->with('error', 'Failed to delete attendance. Please try again.');
        }
    }
}