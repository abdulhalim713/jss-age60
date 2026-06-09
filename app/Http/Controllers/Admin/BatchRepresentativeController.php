<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\BatchRepresentative;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BatchRepresentativeController extends Controller
{
    /**
     * List all batch representatives.
     */
    public function index(): Response
    {
        $representatives = BatchRepresentative::orderBy('batch')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/BatchRepresentative/Index', [
            'representatives' => $representatives,
        ]);
    }

    /**
     * Show create form with alumni search.
     */
    public function create(Request $request): Response
    {
        $query = Alumni::where('status', 'approved');

        if ($request->filled('batch')) {
            $query->where('batch', $request->input('batch'));
        }

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $alumni = $query->orderBy('batch')->orderBy('name')->paginate(20)->withQueryString();

        // All distinct batches for the dropdown filter
        $batches = Alumni::where('status', 'approved')
            ->select('batch')->distinct()->orderBy('batch')->pluck('batch');

        return Inertia::render('Admin/BatchRepresentative/Create', [
            'alumni'  => $alumni,
            'batches' => $batches,
            'filters' => $request->only(['batch', 'search']),
        ]);
    }

    /**
     * Store multiple batch representatives.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'alumni_ids'   => 'required|array|min:1',
            'alumni_ids.*' => 'exists:alumni,id',
        ]);

        $alumniList = Alumni::whereIn('id', $validated['alumni_ids'])->get();

        foreach ($alumniList as $alumni) {
            // Check if already a representative
            $exists = BatchRepresentative::where('alumni_id', $alumni->id)->exists();
            if (!$exists) {
                BatchRepresentative::create([
                    'alumni_id'  => $alumni->id,
                    'name'       => $alumni->name,
                    'batch'      => $alumni->batch,
                    'mobile'     => $alumni->phone,
                    'address'    => $alumni->address,
                    'is_active'  => true,
                    'sort_order' => 0,
                ]);
            }
        }

        return redirect()->route('admin.batch-representatives.index')
            ->with('success', count($alumniList) . ' জন ব্যাচ ভিত্তিক প্রতিনিধি সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Show form to edit an existing representative.
     */
    public function edit(BatchRepresentative $representative): Response
    {
        return Inertia::render('Admin/BatchRepresentative/Edit', [
            'representative' => $representative,
        ]);
    }

    /**
     * Update an existing representative.
     */
    public function update(Request $request, BatchRepresentative $representative): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'batch'      => 'required|string|max:20',
            'mobile'     => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:1000',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($representative->image && Storage::disk('public')->exists($representative->image)) {
                Storage::disk('public')->delete($representative->image);
            }
            $validated['image'] = $request->file('image')->store('batch-representatives', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active']  = $validated['is_active'] ?? true;

        $representative->update($validated);

        return redirect()->route('admin.batch-representatives.index')
            ->with('success', 'প্রতিনিধির তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(BatchRepresentative $representative): RedirectResponse
    {
        $representative->update(['is_active' => !$representative->is_active]);

        return back()->with('success', 'প্রতিনিধির অবস্থা পরিবর্তন করা হয়েছে।');
    }

    /**
     * Delete a batch representative.
     */
    public function destroy(BatchRepresentative $representative): RedirectResponse
    {
        if ($representative->image && Storage::disk('public')->exists($representative->image)) {
            Storage::disk('public')->delete($representative->image);
        }

        $representative->delete();

        return redirect()->route('admin.batch-representatives.index')
            ->with('success', 'প্রতিনিধি মুছে ফেলা হয়েছে।');
    }
}
