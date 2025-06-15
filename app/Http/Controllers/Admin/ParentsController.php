<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ParentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $parents = Parents::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        $users = User::all(); // Users not already parents
        return view('admin.parents.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('parents', 'user_id'),
            ],
            'contact_phone' => 'nullable|string|max:15',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'payment_info' => 'nullable|string|max:255',
        ], [
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'The selected user is invalid.',
            'user_id.unique' => 'This user is already registered as a parent.',
            'contact_phone.max' => 'Contact phone cannot exceed 15 characters.',
            'bio.max' => 'Bio cannot exceed 1000 characters.',
            'profile_photo.image' => 'Profile photo must be an image.',
            'profile_photo.mimes' => 'Profile photo must be a JPG, JPEG, or PNG file.',
            'profile_photo.max' => 'Profile photo cannot exceed 2MB.',
            'payment_info.max' => 'Payment info cannot exceed 255 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $data = [
                'user_id' => $request->user_id,
                'contact_phone' => $request->contact_phone,
                'bio' => $request->bio,
                'payment_info' => $request->payment_info,
            ];

            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $request->file('profile_photo')->store('parents/photos', 'public');
            }

            Parents::create($data);
            DB::commit();
            return redirect()->route('admin.parents.index')
                ->with('success', 'Parent created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Parent Creation Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to create parent. Please try again.')
                ->withInput();
        }
    }

    public function show(Parents $parent)
    {
        $parent->load('user');
        return view('admin.parents.show', compact('parent'));
    }

    public function edit(Parents $parent)
    {
         $users = User::all();
        return view('admin.parents.edit', compact('parent', 'users'));
    }

    public function update(Request $request, Parents $parent)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('parents', 'user_id')->ignore($parent->parent_id, 'parent_id'),
            ],
            'contact_phone' => 'nullable|string|max:15',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'payment_info' => 'nullable|string|max:255',
        ], [
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'The selected user is invalid.',
            'user_id.unique' => 'This user is already registered as a parent.',
            'contact_phone.max' => 'Contact phone cannot exceed 15 characters.',
            'bio.max' => 'Bio cannot exceed 1000 characters.',
            'profile_photo.image' => 'Profile photo must be an image.',
            'profile_photo.mimes' => 'Profile photo must be a JPG, JPEG, or PNG file.',
            'profile_photo.max' => 'Profile photo cannot exceed 2MB.',
            'payment_info.max' => 'Payment info cannot exceed 255 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $data = [
                'user_id' => $request->user_id,
                'contact_phone' => $request->contact_phone,
                'bio' => $request->bio,
                'payment_info' => $request->payment_info,
            ];

            if ($request->hasFile('profile_photo')) {
                if ($parent->profile_photo) {
                    Storage::disk('public')->delete($parent->profile_photo);
                }
                $data['profile_photo'] = $request->file('profile_photo')->store('parents/photos', 'public');
            }

            $parent->update($data);
            DB::commit();
            return redirect()->route('admin.parents.index')
                ->with('success', 'Parent updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Parent Update Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to update parent. Please try again.')
                ->withInput();
        }
    }

    public function destroy(Parents $parent)
    {
        try {
            DB::beginTransaction();
            if ($parent->profile_photo) {
                Storage::disk('public')->delete($parent->profile_photo);
            }
            $parent->delete();
            DB::commit();
            return redirect()->route('admin.parents.index')
                ->with('success', 'Parent deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Parent Deletion Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to delete parent. Please try again.');
        }
    }
}