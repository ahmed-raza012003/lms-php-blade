<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalCenter;
use App\Models\User;
use Illuminate\Http\Request;

class EducationalCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centers = EducationalCenter::with('owner')->get();
        return view('admin.educational_center.index', compact('centers'));
    }

    public function create()
    {
        $users = User::all(); // Fetch all users for the owner dropdown
        return view('admin.educational_center.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'verification_status' => 'nullable|in:pending,approved,rejected',
        ]);

        EducationalCenter::create($request->all());

        return redirect()->route('admin.educenter.index')->with('success', __('Educational Center created successfully.'));
    }

    public function edit($id)
    {
        $center = EducationalCenter::findOrFail($id);
        $users = User::all(); // Fetch all users for the owner dropdown
        return view('admin.educational_center.edit', compact('center', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'verification_status' => 'nullable|in:pending,approved,rejected',
        ]);

        $center = EducationalCenter::findOrFail($id);
        $center->update($request->all());

        return redirect()->route('admin.educenter.index')->with('success', __('Educational Center updated successfully.'));
    }

    public function destroy($id)
    {
        $center = EducationalCenter::findOrFail($id);
        $center->delete();

        return redirect()->route('admin.educenter.index')->with('success', __('Educational Center deleted successfully.'));
    }
}
