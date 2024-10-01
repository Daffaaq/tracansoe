<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }


    public function list(Request $request)
    {
        if ($request->ajax()) {
            $dataPromosi = User::select("uuid", "name", "email", "role")->get();
            // dd($dataPromosi);
            return DataTables::of($dataPromosi)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|string|in:superadmin,karyawan',
        ]);

        $validated['password'] = Hash::make($validated['password']); // Use Hash facade

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        // Validate and update the user
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|string|in:superadmin,karyawan',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']); // Use Hash facade
        } else {
            unset($validated['password']); // Don't update password if not provided
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
