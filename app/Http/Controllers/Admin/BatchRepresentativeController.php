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
     * Store a new batch representative.
     * The selected alumni_id + pre-filled data come from the form.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'alumni_id'  => 'nullable|exists:alumni,id',
            'name'       => 'required|string|max:255',
            'batch'      => 'required|string|max:20',
            'mobile'     => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:1000',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('batch-representatives', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active']  = $validated['is_active']  ?? true;

        BatchRepresentative::create($validated);

        return redirect()->route('admin.batch-representatives.index')
            ->with('success', 'ব্যাচ ভিত্তিক প্রতিনিধি সফলভাবে যোগ করা হয়েছে।');
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
