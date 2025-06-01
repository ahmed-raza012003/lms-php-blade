<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;

class ClassEnrollmentsController extends Controller
{
    public function index()
    {
        $enrollments = ClassEnrollment::with(['class', 'student'])
            ->paginate(10);
        return view('admin.class_enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $classes = Classes::all();
        $students = User::where('role', 'student')->get();
        $statuses = ['pending', 'enrolled', 'cancelled'];
        return view('admin.class_enrollments.create', compact('classes', 'students', 'statuses'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'class_id' => 'required|exists:classes,class_id',
                'student_id' => 'required|exists:users,id',
                'enrollment_date' => 'required|date|after_or_equal:today',
                'status' => ['required', Rule::in(['pending', 'enrolled', 'cancelled'])],
            ], [
                'class_id.required' => 'Please select a class.',
                'class_id.exists' => 'The selected class does not exist.',
                'student_id.required' => 'Please select a student.',
                'student_id.exists' => 'The selected student does not exist.',
                'enrollment_date.required' => 'Enrollment date is required.',
                'enrollment_date.date' => 'Enrollment date must be a valid date.',
                'enrollment_date.after_or_equal' => 'Enrollment date cannot be in the past.',
                'status.required' => 'Please select a status.',
                'status.in' => 'Invalid status selected.',
            ]);

            // Check for duplicate enrollment
            $exists = ClassEnrollment::where('class_id', $request->class_id)
                ->where('student_id', $request->student_id)
                ->exists();

            if ($exists) {
                return back()->withInput()->with('error', 'This student is already enrolled in the selected class.');
            }

            ClassEnrollment::create($request->all());

            return redirect()->route('admin.class-enrollments.index')->with('success', 'Enrollment created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating enrollment: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error creating enrollment: ' . $e->getMessage());
        }
    }

    public function show(ClassEnrollment $classEnrollment)
    {
        $classEnrollment->load(['class', 'student']);
        if (!$classEnrollment->student) {
            Log::warning("Enrollment ID {$classEnrollment->enrollment_id} has invalid student_id: {$classEnrollment->student_id}");
        }
        if (!$classEnrollment->class) {
            Log::warning("Enrollment ID {$classEnrollment->enrollment_id} has invalid class_id: {$classEnrollment->class_id}");
        }
        return view('admin.class_enrollments.show', compact('classEnrollment'));
    }

    public function edit(ClassEnrollment $classEnrollment)
    {
        $classes = Classes::all();
        $students = User::where('role', 'student')->get();
        $statuses = ['pending', 'enrolled', 'cancelled'];
        return view('admin.class_enrollments.edit', compact('classEnrollment', 'classes', 'students', 'statuses'));
    }

    public function update(Request $request, ClassEnrollment $classEnrollment)
    {
        try {
            $request->validate([
                'class_id' => 'required|exists:classes,class_id',
                'student_id' => 'required|exists:users,id',
                'enrollment_date' => 'required|date|after_or_equal:today',
                'status' => ['required', Rule::in(['pending', 'enrolled', 'cancelled'])],
            ], [
                'class_id.required' => 'Please select a class.',
                'class_id.exists' => 'The selected class does not exist.',
                'student_id.required' => 'Please select a student.',
                'student_id.exists' => 'The selected student does not exist.',
                'enrollment_date.required' => 'Enrollment date is required.',
                'enrollment_date.date' => 'Enrollment date must be a valid date.',
                'enrollment_date.after_or_equal' => 'Enrollment date cannot be in the past.',
                'status.required' => 'Please select a status.',
                'status.in' => 'Invalid status selected.',
            ]);

            // Check for duplicate enrollment (exclude current enrollment)
            $exists = ClassEnrollment::where('class_id', $request->class_id)
                ->where('student_id', $request->student_id)
                ->where('enrollment_id', '!=', $classEnrollment->enrollment_id)
                ->exists();

            if ($exists) {
                return back()->withInput()->with('error', 'This student is already enrolled in the selected class.');
            }

            $classEnrollment->update($request->all());

            return redirect()->route('admin.class-enrollments.index')->with('success', 'Enrollment updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating enrollment: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error updating enrollment: ' . $e->getMessage());
        }
    }

    public function destroy(ClassEnrollment $classEnrollment)
    {
        try {
            $classEnrollment->delete();
            return redirect()->route('admin.class-enrollments.index')->with('success', 'Enrollment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting enrollment: ' . $e->getMessage());
            return back()->with('error', 'Error deleting enrollment: ' . $e->getMessage());
        }
    }
}
