<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HeroSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $heroes = HeroSetting::orderBy('sort_order')->get();

        return Inertia::render('Admin/Hero/Index', [
            'heroes' => $heroes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Hero/CreateEdit', [
            'hero' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'subtitle'   => 'nullable|string|max:1000',
            'bg_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('bg_image')) {
            $validated['bg_image'] = $request->file('bg_image')->store('hero', 'public');
        }

        HeroSetting::create($validated);

        return redirect()->route('admin.hero.index')->with('success', 'হিরো স্লাইড সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroSetting $hero): Response
    {
        return Inertia::render('Admin/Hero/CreateEdit', [
            'hero' => $hero,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeroSetting $hero): RedirectResponse
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'subtitle'   => 'nullable|string|max:1000',
            'bg_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('bg_image')) {
            // Delete old image if exists
            if ($hero->bg_image && Storage::disk('public')->exists($hero->bg_image)) {
                Storage::disk('public')->delete($hero->bg_image);
            }
            $validated['bg_image'] = $request->file('bg_image')->store('hero', 'public');
        } else {
            // Keep existing image
            unset($validated['bg_image']);
        }

        $hero->update($validated);

        return redirect()->route('admin.hero.index')->with('success', 'হিরো স্লাইড সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSetting $hero): RedirectResponse
    {
        if ($hero->bg_image && Storage::disk('public')->exists($hero->bg_image)) {
            Storage::disk('public')->delete($hero->bg_image);
        }

        $hero->delete();

        return redirect()->route('admin.hero.index')->with('success', 'হিরো স্লাইড মুছে ফেলা হয়েছে।');
    }
}
