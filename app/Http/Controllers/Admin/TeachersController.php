<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherDegree;
use App\Models\TeacherCertification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeachersController extends Controller
{
   public function index()
    {
        $teachers = Teacher::with(['user', 'degrees', 'certifications'])->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.teachers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:teachers,user_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'subjects' => 'nullable|string',
            'school_levels' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'availability' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'payment_info' => 'nullable|string',
            'highest_qualification' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'degrees.*.name' => 'required_with:degrees.*.file|string|max:255',
            'degrees.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certifications.*.name' => 'required_with:certifications.*.file|string|max:255',
            'certifications.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'user_id', 'first_name', 'last_name', 'bio', 'subjects',
            'school_levels', 'hourly_rate', 'availability', 'rating',
            'payment_info', 'highest_qualification', 'years_of_experience'
        ]);

        // Convert comma-separated strings to arrays
        if ($data['subjects']) {
            $data['subjects'] = explode(',', $data['subjects']);
        }
        if ($data['school_levels']) {
            $data['school_levels'] = explode(',', $data['school_levels']);
        }
        if ($data['availability']) {
            $data['availability'] = explode(',', $data['availability']);
        }

        $teacher = Teacher::create($data);

        // Store degrees
        if ($request->has('degrees')) {
            foreach ($request->degrees as $degreeData) {
                if (!empty($degreeData['name'])) {
                    $degree = new TeacherDegree(['name' => $degreeData['name']]);
                    if (!empty($degreeData['file'])) {
                        $path = $degreeData['file']->store('degrees', 'public');
                        $degree->file_path = $path;
                    }
                    $teacher->degrees()->save($degree);
                }
            }
        }

        // Store certifications
        if ($request->has('certifications')) {
            foreach ($request->certifications as $certData) {
                if (!empty($certData['name'])) {
                    $cert = new TeacherCertification(['name' => $certData['name']]);
                    if (!empty($certData['file'])) {
                        $path = $certData['file']->store('certifications', 'public');
                        $cert->file_path = $path;
                    }
                    $teacher->certifications()->save($cert);
                }
            }
        }

        return redirect()->route('admin.teachers.index')->with('success', __('Teacher created successfully.'));
    }

    public function show($id)
    {
        $teacher = Teacher::with(['user', 'degrees', 'certifications'])->findOrFail($id);
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = Teacher::with(['user', 'degrees', 'certifications'])->findOrFail($id);
        $users = User::all();
        return view('admin.teachers.edit', compact('teacher', 'users'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:teachers,user_id,' . $teacher->teacher_id . ',teacher_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'subjects' => 'nullable|string',
            'school_levels' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'availability' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'payment_info' => 'nullable|string',
            'highest_qualification' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'degrees.*.name' => 'required_with:degrees.*.file|string|max:255',
            'degrees.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certifications.*.name' => 'required_with:certifications.*.file|string|max:255',
            'certifications.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'user_id', 'first_name', 'last_name', 'bio', 'subjects',
            'school_levels', 'hourly_rate', 'availability', 'rating',
            'payment_info', 'highest_qualification', 'years_of_experience'
        ]);

        // Convert comma-separated strings to arrays
        if ($data['subjects']) {
            $data['subjects'] = explode(',', $data['subjects']);
        } else {
            $data['subjects'] = [];
        }
        if ($data['school_levels']) {
            $data['school_levels'] = explode(',', $data['school_levels']);
        } else {
            $data['school_levels'] = [];
        }
        if ($data['availability']) {
            $data['availability'] = explode(',', $data['availability']);
        } else {
            $data['availability'] = [];
        }

        $teacher->update($data);

        // Update degrees
        $existingDegreeIds = $teacher->degrees->pluck('id')->toArray();
        $submittedDegreeIds = [];

        if ($request->has('degrees')) {
            foreach ($request->degrees as $index => $degreeData) {
                if (!empty($degreeData['name'])) {
                    $degreeData['id'] = $degreeData['id'] ?? null;
                    if ($degreeData['id']) {
                        // Update existing degree
                        $degree = TeacherDegree::find($degreeData['id']);
                        if ($degree) {
                            $degree->update(['name' => $degreeData['name']]);
                            if (!empty($degreeData['file'])) {
                                if ($degree->file_path) {
                                    Storage::disk('public')->delete($degree->file_path);
                                }
                                $path = $degreeData['file']->store('degrees', 'public');
                                $degree->update(['file_path' => $path]);
                            }
                            $submittedDegreeIds[] = $degree->id;
                        }
                    } else {
                        // Create new degree
                        $degree = new TeacherDegree(['name' => $degreeData['name']]);
                        if (!empty($degreeData['file'])) {
                            $path = $degreeData['file']->store('degrees', 'public');
                            $degree->file_path = $path;
                        }
                        $teacher->degrees()->save($degree);
                        $submittedDegreeIds[] = $degree->id;
                    }
                }
            }
        }

        // Delete degrees not in submitted data
        $degreesToDelete = array_diff($existingDegreeIds, $submittedDegreeIds);
        foreach ($degreesToDelete as $degreeId) {
            $degree = TeacherDegree::find($degreeId);
            if ($degree) {
                if ($degree->file_path) {
                    Storage::disk('public')->delete($degree->file_path);
                }
                $degree->delete();
            }
        }

        // Update certifications
        $existingCertIds = $teacher->certifications->pluck('id')->toArray();
        $submittedCertIds = [];

        if ($request->has('certifications')) {
            foreach ($request->certifications as $index => $certData) {
                if (!empty($certData['name'])) {
                    $certData['id'] = $certData['id'] ?? null;
                    if ($certData['id']) {
                        // Update existing certification
                        $cert = TeacherCertification::find($certData['id']);
                        if ($cert) {
                            $cert->update(['name' => $certData['name']]);
                            if (!empty($certData['file'])) {
                                if ($cert->file_path) {
                                    Storage::disk('public')->delete($cert->file_path);
                                }
                                $path = $certData['file']->store('certifications', 'public');
                                $cert->update(['file_path' => $path]);
                            }
                            $submittedCertIds[] = $cert->id;
                        }
                    } else {
                        // Create new certification
                        $cert = new TeacherCertification(['name' => $certData['name']]);
                        if (!empty($certData['file'])) {
                            $path = $certData['file']->store('certifications', 'public');
                            $cert->file_path = $path;
                        }
                        $teacher->certifications()->save($cert);
                        $submittedCertIds[] = $cert->id;
                    }
                }
            }
        }

        // Delete certifications not in submitted data
        $certsToDelete = array_diff($existingCertIds, $submittedCertIds);
        foreach ($certsToDelete as $certId) {
            $cert = TeacherCertification::find($certId);
            if ($cert) {
                if ($cert->file_path) {
                    Storage::disk('public')->delete($cert->file_path);
                }
                $cert->delete();
            }
        }

        return redirect()->route('admin.teachers.index')->with('success', __('Teacher updated successfully.'));
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        // Delete associated degrees
        foreach ($teacher->degrees as $degree) {
            if ($degree->file_path) {
                Storage::disk('public')->delete($degree->file_path);
            }
            $degree->delete();
        }

        // Delete associated certifications
        foreach ($teacher->certifications as $cert) {
            if ($cert->file_path) {
                Storage::disk('public')->delete($cert->file_path);
            }
            $cert->delete();
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', __('Teacher deleted successfully.'));
    }
}
