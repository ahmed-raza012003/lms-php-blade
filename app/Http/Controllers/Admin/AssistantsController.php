<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AssistantsController extends Controller
{
    public function index()
    {
        $assistants = Assistant::with(['assistantUser', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.assistants.index', compact('assistants'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.assistants.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assistant_user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('assistants')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id);
                }),
                function ($attribute, $value, $fail) use ($request) {
                    if ($value == $request->user_id) {
                        $fail('A user cannot be their own assistant.');
                    }
                },
            ],
            'user_id' => 'required|exists:users,id',
            'authority_level' => 'required|in:full,limited',
        ], [
            'assistant_user_id.required' => 'Please select an assistant.',
            'assistant_user_id.exists' => 'The selected assistant is invalid.',
            'assistant_user_id.unique' => 'This assistant is already assigned to the selected user.',
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'The selected user is invalid.',
            'authority_level.required' => 'Authority level is required.',
            'authority_level.in' => 'Invalid authority level selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            Assistant::create([
                'assistant_user_id' => $request->assistant_user_id,
                'user_id' => $request->user_id,
                'authority_level' => $request->authority_level,
            ]);
            DB::commit();
            return redirect()->route('admin.assistants.index')
                ->with('success', 'Assistant assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assistant Creation Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to assign assistant. Please try again.')
                ->withInput();
        }
    }

    public function show(Assistant $assistant)
    {
        $assistant->load(['assistantUser', 'user']);
        return view('admin.assistants.show', compact('assistant'));
    }

    public function edit(Assistant $assistant)
    {
        $users = User::all();
        return view('admin.assistants.edit', compact('assistant', 'users'));
    }

    public function update(Request $request, Assistant $assistant)
    {
        $validator = Validator::make($request->all(), [
            'assistant_user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('assistants')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id);
                })->ignore($assistant->assistant_id, 'assistant_id'),
                function ($attribute, $value, $fail) use ($request) {
                    if ($value == $request->user_id) {
                        $fail('A user cannot be their own assistant.');
                    }
                },
            ],
            'user_id' => 'required|exists:users,id',
            'authority_level' => 'required|in:full,limited',
        ], [
            'assistant_user_id.required' => 'Please select an assistant.',
            'assistant_user_id.exists' => 'The selected assistant is invalid.',
            'assistant_user_id.unique' => 'This assistant is already assigned to the selected user.',
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'The selected user is invalid.',
            'authority_level.required' => 'Authority level is required.',
            'authority_level.in' => 'Invalid authority level selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $assistant->update([
                'assistant_user_id' => $request->assistant_user_id,
                'user_id' => $request->user_id,
                'authority_level' => $request->authority_level,
            ]);
            DB::commit();
            return redirect()->route('admin.assistants.index')
                ->with('success', 'Assistant updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assistant Update Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to update assistant. Please try again.')
                ->withInput();
        }
    }

    public function destroy(Assistant $assistant)
    {
        try {
            DB::beginTransaction();
            $assistant->delete();
            DB::commit();
            return redirect()->route('admin.assistants.index')
                ->with('success', 'Assistant removed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assistant Deletion Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to remove assistant. Please try again.');
        }
    }
}