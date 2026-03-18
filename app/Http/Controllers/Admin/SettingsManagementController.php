<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use App\Support\ActivityLogger;
use App\Support\SettingStore;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingsManagementController extends Controller
{
    public function edit(): Response
    {
        $this->authorize('viewAny', Setting::class);

        return Inertia::render('admin/Settings/Edit', [
            'settingGroups' => SettingStore::groupsWithValues(),
        ]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->authorize('update', new Setting);

        SettingStore::sync($request->validated());

        ActivityLogger::record(
            actor: $request->user(),
            event: 'settings.platform-updated',
            description: 'Updated shared application settings.',
            subject: null,
            properties: [
                'keys' => array_keys($request->validated()),
            ],
            request: $request,
        );

        return to_route('admin-settings.edit')->with('success', 'Settings updated successfully.');
    }
}
