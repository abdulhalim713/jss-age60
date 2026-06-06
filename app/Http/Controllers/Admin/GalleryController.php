<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaGallery;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $items = MediaGallery::orderBy('sort_order')->get();

        return Inertia::render('Admin/Gallery/Index', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Gallery/CreateEdit', [
            'item' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type'       => 'required|string|in:image,video',
            'title'      => 'nullable|string|max:255',
            'file_path'  => 'required_if:type,image|nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'video_url'  => 'required_if:type,video|nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($validated['type'] === 'image' && $request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('gallery', 'public');
            $validated['video_url'] = null;
        } elseif ($validated['type'] === 'video') {
            $validated['file_path'] = null;
            $validated['video_url'] = $this->getYoutubeEmbedUrl($validated['video_url']);
        }

        MediaGallery::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'গ্যালারি আইটেম সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MediaGallery $gallery): Response
    {
        return Inertia::render('Admin/Gallery/CreateEdit', [
            'item' => $gallery,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MediaGallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'type'       => 'required|string|in:image,video',
            'title'      => 'nullable|string|max:255',
            'file_path'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'video_url'  => 'required_if:type,video|nullable|string|max:500',
            'sort_order' => 'required|integer|min:0',
        ]);

        if ($validated['type'] === 'image') {
            if ($request->hasFile('file_path')) {
                // Delete old image if exists
                if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
                    Storage::disk('public')->delete($gallery->file_path);
                }
                $validated['file_path'] = $request->file('file_path')->store('gallery', 'public');
            } else {
                // Keep existing file path
                $validated['file_path'] = $gallery->file_path;
            }
            $validated['video_url'] = null;
        } elseif ($validated['type'] === 'video') {
            // If switched from image to video, delete image file
            if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
            $validated['file_path'] = null;
            $validated['video_url'] = $this->getYoutubeEmbedUrl($validated['video_url']);
        }

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'গ্যালারি আইটেম সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaGallery $gallery): RedirectResponse
    {
        if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'গ্যালারি আইটেম মুছে ফেলা হয়েছে।');
    }

    /**
     * Helper to extract YouTube ID and convert to embed URL.
     */
    private function getYoutubeEmbedUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        $regExp = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
        preg_match($regExp, $url, $matches);

        if (isset($matches[2]) && strlen($matches[2]) == 11) {
            return 'https://www.youtube.com/embed/' . $matches[2];
        }

        return $url;
    }
}
