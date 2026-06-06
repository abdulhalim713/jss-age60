<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $items = Sponsor::orderBy('sort_order')->get();

        return Inertia::render('Admin/Sponsor/Index', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Sponsor/CreateEdit', [
            'item' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'logo'       => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'link'       => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        Sponsor::create($validated);

        return redirect()->route('admin.sponsors.index')->with('success', 'সহযোগী ও স্পন্সর সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sponsor $sponsor): Response
    {
        return Inertia::render('Admin/Sponsor/CreateEdit', [
            'item' => $sponsor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sponsor $sponsor): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'link'       => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($sponsor->logo && Storage::disk('public')->exists($sponsor->logo)) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            $validated['logo'] = $request->file('logo')->store('sponsors', 'public');
        } else {
            $validated['logo'] = $sponsor->logo;
        }

        $sponsor->update($validated);

        return redirect()->route('admin.sponsors.index')->with('success', 'সহযোগী ও স্পন্সর সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sponsor $sponsor): RedirectResponse
    {
        if ($sponsor->logo && Storage::disk('public')->exists($sponsor->logo)) {
            Storage::disk('public')->delete($sponsor->logo);
        }

        $sponsor->delete();

        return redirect()->route('admin.sponsors.index')->with('success', 'সহযোগী ও স্পন্সর মুছে ফেলা হয়েছে।');
    }
}
