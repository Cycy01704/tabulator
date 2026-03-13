<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JudgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $judges = User::where('role', User::ROLE_JUDGE)->paginate(10);
        $passkey = Setting::getValue('leaderboard_passkey', '1234');
        $securityEnabled = Setting::getValue('triple_layer_security', '1') == '1';
        return view('judges.index', compact('judges', 'passkey', 'securityEnabled'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('judges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_JUDGE,
        ]);

        return redirect()->route('judges.index')
            ->with('success', 'Judge created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $judge)
    {
        if ($judge->role !== User::ROLE_JUDGE) {
            abort(404);
        }
        return view('judges.show', compact('judge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $judge)
    {
        if ($judge->role !== User::ROLE_JUDGE) {
            abort(404);
        }
        return view('judges.edit', compact('judge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $judge)
    {
        if ($judge->role !== User::ROLE_JUDGE) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $judge->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $judge->update($userData);

        return redirect()->route('judges.index')
            ->with('success', 'Judge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $judge)
    {
        if ($judge->role !== User::ROLE_JUDGE) {
            abort(404);
        }

        $judge->delete();

        return redirect()->route('judges.index')
            ->with('success', 'Judge deleted successfully.');
    }
}
