<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    /**
     * Show the event settings form.
     *
     * @return \Inertia\Response
     */
    public function edit(): Response
    {
        return Inertia::render('Admin/Settings', [
            'settings' => [
                'event_date' => Setting::get('event_date', '2027-04-10'),
            ],
        ]);
    }

    /**
     * Update the settings in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_date' => 'required|date',
        ]);

        Setting::set('event_date', $validated['event_date']);

        return redirect()->back()->with('success', 'অনুষ্ঠান সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }
}
