<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Log;
use Storage;

class WorkspaceController extends Controller
{
   public function index()
    {
        $workspaces = Workspace::with('user')->get();
        return view('admin.workspace.index', compact('workspaces'));
    }

    public function searchWorkspaces(Request $request)
    {
        // Get the search term from the request, default to empty string if not provided
        $searchTerm = $request->input('search', '');

        // Log the search attempt
        Log::info('Workspace search initiated', [
            'search_term' => $searchTerm,
            'user_id' => auth()->id() ?? 'guest',
            'timestamp' => now()->toDateTimeString()
        ]);

        // Initialize the query
        $query = Workspace::with('user');

        // Apply search filter if search term is provided
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('country', 'like', '%' . $searchTerm . '%')
                  ->orWhere('workspace_type', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get paginated results
        $workspaces = $query->paginate(9);

        // Log the number of results
        Log::info('Workspace search results', [
            'search_term' => $searchTerm,
            'result_count' => $workspaces->count(),
            'total_results' => $workspaces->total(),
            'timestamp' => now()->toDateTimeString()
        ]);

        // If no results found and search term was provided, fetch all approved workspaces
        if ($workspaces->isEmpty() && !empty($searchTerm)) {
            Log::warning('No workspaces found for search term, returning all approved workspaces', [
                'search_term' => $searchTerm,
                'timestamp' => now()->toDateTimeString()
            ]);
            $workspaces = Workspace::with('user')->where('status', 'approved')->paginate(9);
            $noResultsMessage = 'No workspaces found matching your search. Showing all approved workspaces instead.';
        } else {
            $noResultsMessage = null;
        }

        // Return the view with the workspaces data
        return view('admin.workspace.search', compact('workspaces', 'noResultsMessage'));
    }
    public function create()
    {
        $users = User::all();
        return view('admin.workspace.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'workspace_type' => 'required|in:Educational Center,Co-work Space',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'rating' => 'nullable|numeric|between:0,5',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_video' => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:10240',
            'status' => 'nullable|in:active,inactive',
        ], [
            'name.required' => __('The workspace name is required.'),
            'workspace_type.required' => __('Please select a workspace type.'),
            'location.required' => __('The location is required.'),
            'address.required' => __('The address is required.'),
            'city.required' => __('The city is required.'),
            'country.required' => __('The country is required.'),
            'user_id.required' => __('Please select an owner.'),
            'latitude.between' => __('Latitude must be between -90 and 90.'),
            'longitude.between' => __('Longitude must be between -180 and 180.'),
            'rating.between' => __('Rating must be between 0 and 5.'),
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.mimes' => __('The profile photo must be a JPEG, PNG, JPG, or GIF.'),
            'profile_photo.max' => __('The profile photo may not be larger than 2MB.'),
            'profile_video.mimetypes' => __('The profile video must be an MP4, MPEG, or QuickTime file.'),
            'profile_video.max' => __('The profile video may not be larger than 10MB.'),
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('workspaces/photos', 'public');
        }
        if ($request->hasFile('profile_video')) {
            $data['profile_video'] = $request->file('profile_video')->store('workspaces/videos', 'public');
        }

        Workspace::create($data);

        return redirect()->route('admin.workspace.index')->with('success', __('Workspace created successfully.'));
    }

    public function edit($workspace_id)
    {
        $workspace = Workspace::findOrFail($workspace_id);
        $users = User::all();
        return view('admin.workspace.edit', compact('workspace', 'users'));
    }

    public function update(Request $request, $workspace_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'workspace_type' => 'required|in:Educational Center,Co-work Space',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'rating' => 'nullable|numeric|between:0,5',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_video' => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:10240',
            'status' => 'nullable|in:active,inactive',
        ], [
            'name.required' => __('The workspace name is required.'),
            'workspace_type.required' => __('Please select a workspace type.'),
            'location.required' => __('The location is required.'),
            'address.required' => __('The address is required.'),
            'city.required' => __('The city is required.'),
            'country.required' => __('The country is required.'),
            'user_id.required' => __('Please select an owner.'),
            'latitude.between' => __('Latitude must be between -90 and 90.'),
            'longitude.between' => __('Longitude must be between -180 and 180.'),
            'rating.between' => __('Rating must be between 0 and 5.'),
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.mimes' => __('The profile photo must be a JPEG, PNG, JPG, or GIF.'),
            'profile_photo.max' => __('The profile photo may not be larger than 2MB.'),
            'profile_video.mimetypes' => __('The profile video must be an MP4, MPEG, or QuickTime file.'),
            'profile_video.max' => __('The profile video may not be larger than 10MB.'),
        ]);

        $workspace = Workspace::findOrFail($workspace_id);
        $data = $request->all();

        if ($request->hasFile('profile_photo')) {
            if ($workspace->profile_photo) {
                Storage::disk('public')->delete($workspace->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('workspaces/photos', 'public');
        }
        if ($request->hasFile('profile_video')) {
            if ($workspace->profile_video) {
                Storage::disk('public')->delete($workspace->profile_video);
            }
            $data['profile_video'] = $request->file('profile_video')->store('workspaces/videos', 'public');
        }

        $workspace->update($data);

        return redirect()->route('admin.workspace.index')->with('success', __('Workspace updated successfully.'));
    }

    public function destroy($workspace_id)
    {
        $workspace = Workspace::findOrFail($workspace_id);

        if ($workspace->profile_photo) {
            Storage::disk('public')->delete($workspace->profile_photo);
        }
        if ($workspace->profile_video) {
            Storage::disk('public')->delete($workspace->profile_video);
        }

        $workspace->delete();

        return redirect()->route('admin.workspace.index')->with('success', __('Workspace deleted successfully.'));
    }

    public function updateStatus(Request $request)
    {
        try {
            Log::info('Updating status', ['workspace_id' => $request->workspace_id, 'status' => $request->status]);

            $request->validate([
                'workspace_id' => 'required|exists:workspaces,workspace_id',
                'status' => 'required|in:active,inactive',
            ], [
                'status.required' => __('Please select a valid status.'),
                'status.in' => __('The status must be either active or inactive.'),
            ]);

            $workspace = Workspace::findOrFail($request->workspace_id);
            $current_status = $workspace->status;
            $workspace->status = $request->status;
            $workspace->save();

            Log::info('Status updated successfully', ['workspace_id' => $workspace->workspace_id, 'new_status' => $workspace->status]);

            return response()->json([
                'success' => true,
                'message' => __('Status updated successfully.'),
                'current_status' => $current_status
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating status', ['error' => $e->getMessage(), 'workspace_id' => $request->workspace_id]);
            return response()->json([
                'success' => false,
                'message' => __('Error updating status: ') . $e->getMessage(),
                'current_status' => $current_status ?? ''
            ], 500);
        }
    }
}
