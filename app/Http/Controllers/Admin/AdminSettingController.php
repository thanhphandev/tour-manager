<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        return view('admin.settings.index', compact('settings'));
    }

    public function edit()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log(
            'Updated system settings',
            null,
            auth()->user(),
            ['settings_count' => count($validated['settings'])]
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cài đặt đã được cập nhật.');
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|unique:settings,key',
            'label' => 'required|string|max:255',
            'value' => 'nullable',
            'type' => 'required|in:string,integer,boolean,json',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $setting = Setting::create($validated);

        ActivityLog::log(
            "Created new setting: {$setting->key}",
            $setting,
            auth()->user()
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Đã tạo cài đặt mới.');
    }

    public function destroy(Setting $setting)
    {
        $key = $setting->key;
        $setting->delete();
        Setting::forget($key);

        ActivityLog::log(
            "Deleted setting: {$key}",
            null,
            auth()->user()
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Đã xóa cài đặt.');
    }
}
