<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\Setting;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $this->authorize('manage-settings');

        return view('settings.index', [
            'values' => array_merge(Setting::defaults(), Setting::query()->pluck('value', 'key')->all()),
        ]);
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        $this->authorize('manage-settings');

        foreach ($request->validated() as $key => $value) {
            Setting::put($key, $value === null ? null : (string) $value);
        }

        AuditLogger::log('update', 'settings', 'Pengaturan bisnis diperbarui.');

        return redirect()->route('settings.index')->with('success', 'Pengaturan bisnis berhasil disimpan.');
    }
}
