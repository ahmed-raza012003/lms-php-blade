<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        Log::info('Entering user index method', ['admin_id' => Auth::guard('admin')->id()]);

        try {
            $query = User::query()->select('id', 'name', 'email', 'role', 'created_at', 'updated_at');
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            }
            if ($request->filled('role') && $request->role !== 'admin') {
                $query->where('role', $request->role);
            }

            $users = $query->get();
            $admins = Admin::query()->select('id', 'name', 'email', 'created_at', 'updated_at');
            if ($request->filled('search')) {
                $admins->where('name', 'like', '%' . $request->search . '%')
                       ->orWhere('email', 'like', '%' . $request->search . '%');
            }
            $admins = $admins->get()->map(function ($admin) {
                $admin->role = 'admin';
                return $admin;
            });

            $allUsers = $users->merge($admins);
            if ($request->filled('role') && $request->role === 'admin') {
                $allUsers = $allUsers->filter(function ($user) {
                    return $user->role === 'admin';
                });
            }

            $perPage = 10;
            $currentPage = $request->input('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $paginatedUsers = $allUsers->slice($offset, $perPage);
            $total = $allUsers->count();
            $users = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedUsers,
                $total,
                $perPage,
                $currentPage,
                ['path' => route('admin.users.index')]
            );

            Log::info('Successfully loaded users and admins', ['total_count' => $total]);

            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error in user index method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to load users. Please try again.');
        }
    }

    public function create()
    {
        Log::info('Entering user create method', ['admin_id' => Auth::guard('admin')->id()]);

        try {
            return view('admin.users.create');
        } catch (\Exception $e) {
            Log::error('Error in user create method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to load user creation form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        Log::info('Entering user store method', [
            'admin_id' => Auth::guard('admin')->id(),
            'input' => $request->except('password'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,parent,teacher',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except('password'),
            ]);
            return back()->with('error', 'Failed to create user. Please try again.')->withInput();
        }
    }

    public function show($id)
    {
        Log::info('Entering user show method', [
            'admin_id' => Auth::guard('admin')->id(),
            'target_id' => $id,
        ]);

        try {
            $user = User::find($id);
            if ($user) {
                $isAdmin = false;
            } else {
                $user = Admin::findOrFail($id);
                $user->role = 'admin';
                $isAdmin = true;
            }

            return view('admin.users.show', compact('user', 'isAdmin'));
        } catch (\Exception $e) {
            Log::error('Error in user show method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'target_id' => $id,
            ]);
            return back()->with('error', 'Failed to load user details. Please try again.');
        }
    }

    public function edit($id)
    {
        Log::info('Entering user edit method', [
            'admin_id' => Auth::guard('admin')->id(),
            'target_id' => $id,
        ]);

        try {
            $user = User::find($id);
            if ($user) {
                $isAdmin = false;
            } else {
                $user = Admin::findOrFail($id);
                $user->role = 'admin';
                $isAdmin = true;
            }

            return view('admin.users.edit', compact('user', 'isAdmin'));
        } catch (\Exception $e) {
            Log::error('Error in user edit method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'target_id' => $id,
            ]);
            return back()->with('error', 'Failed to load user edit form. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Entering user update method', [
            'admin_id' => Auth::guard('admin')->id(),
            'target_id' => $id,
            'input' => $request->except('password'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:student,parent,teacher',
        ]);

        try {
            $user = User::find($id);
            if ($user) {
                $emailExists = User::where('email', $validated['email'])->where('id', '!=', $id)->exists() ||
                               Admin::where('email', $validated['email'])->exists();
                if ($emailExists) {
                    return back()->with('error', 'Email already in use.')->withInput();
                }
                $data = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                ];
                if (!empty($validated['password'])) {
                    $data['password'] = Hash::make($validated['password']);
                }
                $user->update($data);
            } else {
                $admin = Admin::findOrFail($id);
                $emailExists = User::where('email', $validated['email'])->exists() ||
                               Admin::where('email', $validated['email'])->where('id', '!=', $id)->exists();
                if ($emailExists) {
                    return back()->with('error', 'Email already in use.')->withInput();
                }
                $admin->delete();
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $admin->password,
                    'role' => $validated['role'],
                ]);
            }

            Log::info('User updated successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $validated['role'],
                'table' => 'users',
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'target_id' => $id,
                'input' => $request->except('password'),
            ]);
            return back()->with('error', 'Failed to update user. Please try again.')->withInput();
        }
    }

    public function destroy($id)
    {
        Log::info('Entering user destroy method', [
            'admin_id' => Auth::guard('admin')->id(),
            'target_id' => $id,
        ]);

        try {
            $user = User::find($id);
            if ($user) {
                $userData = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role ?? 'N/A',
                ];
                $user->delete();
            } else {
                $admin = Admin::findOrFail($id);
                if ($admin->id === Auth::guard('admin')->id()) {
                    return back()->with('error', 'You cannot delete your own account.');
                }
                $userData = [
                    'id' => $admin->id,
                    'email' => $admin->email,
                    'role' => 'admin',
                ];
                $admin->delete();
            }

            Log::info('User deleted successfully', [
                'user_data' => $userData,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'target_id' => $id,
            ]);
            return back()->with('error', 'Failed to delete user. Please try again.');
        }
    }
}