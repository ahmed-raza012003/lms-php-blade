<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\Student;
use App\Models\User;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentsController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'parent'])->latest()->paginate(10);
        Log::info('Fetching students list', ['count' => $students->count()]);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        // Filter users who are not already linked to any student
        $users = User::all();
        $parents = Parents::with('user')->get()->map(function ($parent) {
            return [
                'parent_id' => $parent->parent_id,
                'name' => $parent->user ? $parent->user->name : 'N/A',
            ];
        })->filter(function ($parent) {
            return $parent['name'] !== 'N/A'; // Exclude parents with no valid user
        })->values();
        Log::info('Fetching data for create student view', [
            'users_count' => $users->count(),
            'parents_count' => $parents->count(),
        ]);

        return view('admin.students.create', compact('users', 'parents'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Attempting to store new student', [
                'request_data' => $request->except('profile_photo'),
                'user_id' => auth()->id(),
            ]);

            $request->validate([
                'user_id' => [
                    'required',
                    'exists:users,id',
                ],
                'grade_level' => 'nullable|string|max:50',
                'parent_id' => 'nullable|exists:parents,parent_id',
                'bio' => 'nullable|string|max:1000',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'payment_info' => 'nullable|string|max:255',
                'status' => 'nullable|in:active,inactive',
            ], [
                'user_id.required' => 'Please select a user.',
                'user_id.exists' => 'The selected user does not exist.',
                'grade_level.max' => 'Grade level cannot exceed 50 characters.',
                'parent_id.exists' => 'The selected parent does not exist.',
                'bio.max' => 'Bio cannot exceed 1000 characters.',
                'profile_photo.image' => 'Profile photo must be an image.',
                'profile_photo.mimes' => 'Profile photo must be a JPEG, PNG, JPG, or GIF.',
                'profile_photo.max' => 'Profile photo cannot exceed 2MB.',
                'payment_info.max' => 'Payment info cannot exceed 255 characters.',
                'status.in' => 'Invalid status selected.',
            ]);

            $data = $request->only(['user_id', 'grade_level', 'parent_id', 'bio', 'payment_info', 'status']);

            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
            }

            $student = Student::create($data);

            Log::info('Student created successfully', [
                'student_id' => $student->student_id,
                'user_id' => $student->user_id,
            ]);

            return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for student creation', [
                'errors' => $e->errors(),
                'request_data' => $request->except('profile_photo'),
            ]);
            return back()->withInput()->withErrors($e->errors())->with('error', 'Validation failed. Please check the form inputs.');
        } catch (\Exception $e) {
            Log::error('Error creating student', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except('profile_photo'),
            ]);
            return back()->withInput()->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }

    public function show(Student $student)
    {
        $student->load(['user', 'parent', 'enrollments']);
        if (!$student->user) {
            Log::warning("Student ID {$student->student_id} has invalid user_id: {$student->user_id}");
        }
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $users = User::all();
        $parents = Parents::with('user')->get()->map(function ($parent) {
            return [
                'parent_id' => $parent->parent_id,
                'name' => $parent->user ? $parent->user->name : 'N/A',
            ];
        })->filter(function ($parent) {
            return $parent['name'] !== 'N/A';
        })->values();

        Log::info('Fetching data for edit student view', [
            'student_id' => $student->student_id,
            'users_count' => $users->count(),
            'parents_count' => $parents->count(),
            'parents_data' => $parents->toArray(), // Debug parent data
        ]);

        return view('admin.students.edit', compact('student', 'users', 'parents'));
    }

    public function update(Request $request, Student $student)
    {
        try {
            Log::info('Attempting to update student', [
                'student_id' => $student->student_id,
                'request_data' => $request->except('profile_photo'),
                'user_id' => auth()->id(),
            ]);

            $request->validate([
                'user_id' => [
                    'required',
                    'exists:users,id',
                ],
                'grade_level' => 'nullable|string|max:50',
                'parent_id' => 'nullable|exists:parents,parent_id',
                'bio' => 'nullable|string|max:1000',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'payment_info' => 'nullable|string|max:255',
                'status' => 'nullable|in:active,inactive',
            ], [
                'user_id.required' => 'Please select a user.',
                'user_id.exists' => 'The selected user does not exist.',
                'grade_level.max' => 'Grade level cannot exceed 50 characters.',
                'parent_id.exists' => 'The selected parent does not exist.',
                'bio.max' => 'Bio cannot exceed 1000 characters.',
                'profile_photo.image' => 'Profile photo must be an image.',
                'profile_photo.mimes' => 'Profile photo must be a JPEG, PNG, JPG, or GIF.',
                'profile_photo.max' => 'Profile photo cannot exceed 2MB.',
                'payment_info.max' => 'Payment info cannot exceed 255 characters.',
                'status.in' => 'Invalid status selected.',
            ]);

            $data = $request->only(['user_id', 'grade_level', 'parent_id', 'bio', 'payment_info', 'status']);

            if ($request->hasFile('profile_photo')) {
                if ($student->profile_photo) {
                    Storage::disk('public')->delete($student->profile_photo);
                }
                $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
            }

            $student->update($data);

            Log::info('Student updated successfully', [
                'student_id' => $student->student_id,
                'user_id' => $student->user_id,
            ]);

            return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for student update', [
                'student_id' => $student->student_id,
                'errors' => $e->errors(),
                'request_data' => $request->except('profile_photo'),
                'available_parents' => Parents::pluck('parent_id')->toArray(), // Debug available parent_ids
            ]);
            return back()->withInput()->withErrors($e->errors())->with('error', 'Validation failed. Please check the form inputs.');
        } catch (\Exception $e) {
            Log::error('Error updating student', [
                'student_id' => $student->student_id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except('profile_photo'),
            ]);
            return back()->withInput()->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    public function destroy(Student $student)
    {
        try {
            Log::info('Attempting to delete student', [
                'student_id' => $student->student_id,
                'user_id' => auth()->id(),
            ]);

            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }

            $student->delete();

            Log::info('Student deleted successfully', [
                'student_id' => $student->student_id,
            ]);

            return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting student', [
                'student_id' => $student->student_id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|exists:students,student_id',
                'status' => 'required|in:active,inactive',
            ]);

            $student = Student::findOrFail($request->student_id);
            $student->update(['status' => $request->status]);

            Log::info('Student status updated', [
                'student_id' => $student->student_id,
                'status' => $student->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'current_status' => $student->status,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for student status update', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()[array_key_first($e->errors())]),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating student status', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500);
        }
    }
}