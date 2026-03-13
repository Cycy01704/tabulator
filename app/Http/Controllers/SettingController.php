<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update a system setting.
     */
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required',
            'passkey' => 'nullable|string',
        ]);

        // Security check for critical settings
        $criticalKeys = ['triple_layer_security', 'system_passkey', 'leaderboard_passkey'];
        
        if (in_array($request->key, $criticalKeys)) {
            $savedPasskey = Setting::getValue('system_passkey', '1234');
            if ($request->passkey !== $savedPasskey) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid passkey. Critical settings update denied.',
                    ], 403);
                }
                return back()->with('error', 'Invalid passkey. Critical settings update denied.');
            }
        } elseif ($request->key === 'leaderboard_visible' || $request->key === 'leaderboard_filter') {
            $savedPasskey = Setting::getValue('leaderboard_passkey', '1234');
            if ($request->passkey !== $savedPasskey) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid passkey. Leaderboard toggle denied.',
                    ], 403);
                }
                return back()->with('error', 'Invalid passkey. Leaderboard toggle denied.');
            }
        }

        Setting::updateOrCreate(['key' => $request->key], ['value' => $request->value]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully.',
                'setting' => $request->key,
                'value' => $request->value,
            ]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Update multiple system settings at once.
     */
    public function batchUpdate(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'passkey' => 'required|string',
        ]);

        $savedPasskey = Setting::getValue('system_passkey', '1234');
        if ($request->passkey !== $savedPasskey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid administrative passkey. Batch update denied.',
            ], 403);
        }

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Multiple settings updated successfully.',
        ]);
    }
}
