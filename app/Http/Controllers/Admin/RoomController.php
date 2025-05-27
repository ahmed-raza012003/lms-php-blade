<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Log;
use Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('workspace')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $workspaces = Workspace::all();
        return view('admin.rooms.create', compact('workspaces'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'workspace_id' => 'required|exists:workspaces,workspace_id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'size' => 'nullable|string|max:255',
                'price_per_hour' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:1',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'profile_video' => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:10240',
                'status' => 'nullable|in:active,inactive',
            ], [
                'workspace_id.required' => __('Please select a workspace.'),
                'workspace_id.exists' => __('The selected workspace does not exist.'),
                'name.required' => __('The room name is required.'),
                'price_per_hour.required' => __('The price per hour is required.'),
                'price_per_hour.numeric' => __('The price per hour must be a number.'),
                'capacity.required' => __('The capacity is required.'),
                'capacity.integer' => __('The capacity must be an integer.'),
                'profile_photo.image' => __('The profile photo must be an image.'),
                'profile_photo.mimes' => __('The profile photo must be a JPEG, PNG, JPG, or GIF.'),
                'profile_photo.max' => __('The profile photo may not be larger than 2MB.'),
                'profile_video.mimetypes' => __('The profile video must be an MP4, MPEG, or QuickTime file.'),
                'profile_video.max' => __('The profile video may not be larger than 10MB.'),
                'status.in' => __('The status must be either active or inactive.'),
            ]);

            $data = $request->all();

            if ($request->hasFile('profile_photo')) {
                $data['profile_photo'] = $request->file('profile_photo')->store('rooms/photos', 'public');
            }
            if ($request->hasFile('profile_video')) {
                $data['profile_video'] = $request->file('profile_video')->store('rooms/videos', 'public');
            }

            Room::create($data);

            return redirect()->route('admin.rooms.index')->with('success', __('Room created successfully.'));
        } catch (\Exception $e) {
            Log::error('Error creating room: ' . $e->getMessage());
            return back()->withInput()->with('error', __('Error creating room: ') . $e->getMessage());
        }
    }

    public function show($room_id)
    {
        $room = Room::with('workspace')->findOrFail($room_id);
        return view('admin.rooms.show', compact('room'));
    }

    public function edit($room_id)
    {
        $room = Room::findOrFail($room_id);
        $workspaces = Workspace::all();
        return view('admin.rooms.edit', compact('room', 'workspaces'));
    }

    public function update(Request $request, $room_id)
    {
        try {
            $request->validate([
                'workspace_id' => 'required|exists:workspaces,workspace_id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'size' => 'nullable|string|max:255',
                'price_per_hour' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:1',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'profile_video' => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:10240',
                'status' => 'nullable|in:active,inactive',
            ], [
                'workspace_id.required' => __('Please select a workspace.'),
                'workspace_id.exists' => __('The selected workspace does not exist.'),
                'name.required' => __('The room name is required.'),
                'price_per_hour.required' => __('The price per hour is required.'),
                'price_per_hour.numeric' => __('The price per hour must be a number.'),
                'capacity.required' => __('The capacity is required.'),
                'capacity.integer' => __('The capacity must be an integer.'),
                'profile_photo.image' => __('The profile photo must be an image.'),
                'profile_photo.mimes' => __('The profile photo must be a JPEG, PNG, JPG, or GIF.'),
                'profile_photo.max' => __('The profile photo may not be larger than 2MB.'),
                'profile_video.mimetypes' => __('The profile video must be an MP4, MPEG, or QuickTime file.'),
                'profile_video.max' => __('The profile video may not be larger than 10MB.'),
                'status.in' => __('The status must be either active or inactive.'),
            ]);

            $room = Room::findOrFail($room_id);
            $data = $request->all();

            if ($request->hasFile('profile_photo')) {
                if ($room->profile_photo) {
                    Storage::disk('public')->delete($room->profile_photo);
                }
                $data['profile_photo'] = $request->file('profile_photo')->store('rooms/photos', 'public');
            }
            if ($request->hasFile('profile_video')) {
                if ($room->profile_video) {
                    Storage::disk('public')->delete($room->profile_video);
                }
                $data['profile_video'] = $request->file('profile_video')->store('rooms/videos', 'public');
            }

            $room->update($data);

            return redirect()->route('admin.rooms.index')->with('success', __('Room updated successfully.'));
        } catch (\Exception $e) {
            Log::error('Error updating room: ' . $e->getMessage());
            return back()->withInput()->with('error', __('Error updating room: ') . $e->getMessage());
        }
    }

    public function destroy($room_id)
    {
        try {
            $room = Room::findOrFail($room_id);

            if ($room->profile_photo) {
                Storage::disk('public')->delete($room->profile_photo);
            }
            if ($room->profile_video) {
                Storage::disk('public')->delete($room->profile_video);
            }

            $room->delete();

            return redirect()->route('admin.rooms.index')->with('success', __('Room deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Error deleting room: ' . $e->getMessage());
            return back()->with('error', __('Error deleting room: ') . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            Log::info('Updating room status', ['room_id' => $request->room_id, 'status' => $request->status]);

            $request->validate([
                'room_id' => 'required|exists:rooms,room_id',
                'status' => 'required|in:active,inactive',
            ], [
                'room_id.required' => __('Room ID is required.'),
                'room_id.exists' => __('The selected room does not exist.'),
                'status.required' => __('Please select a valid status.'),
                'status.in' => __('The status must be either active or inactive.'),
            ]);

            $room = Room::findOrFail($request->room_id);
            $current_status = $room->status;
            $room->status = $request->status;
            $room->save();

            Log::info('Room status updated', ['room_id' => $room->room_id, 'new_status' => $room->status]);

            return response()->json([
                'success' => true,
                'message' => __('Status updated successfully.'),
                'current_status' => $current_status
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating room status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Error updating status: ') . $e->getMessage(),
                'current_status' => $current_status ?? ''
            ], 500);
        }
    }
}
