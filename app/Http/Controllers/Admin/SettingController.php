<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private SettingService $settingService
    ) {}

    public function index(): View
    {
        $settings = $this->settingService->all();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subscriptions_enabled'       => ['sometimes', 'boolean'],
            'online_game_free_limit'      => ['required', 'integer', 'min:1'],
            'inperson_game_free_limit'    => ['required', 'integer', 'min:1'],
            'inperson_login_required_after' => ['required', 'integer', 'min:0'],
        ]);

        // Checkbox: not in $request if unchecked
        $data['subscriptions_enabled'] = $request->boolean('subscriptions_enabled') ? '1' : '0';

        $this->settingService->updateMany($data);

        return back()->with('success', 'Settings saved.');
    }
}
