<?php

namespace App\Http\Controllers;

use App\Models\Essay;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EssayController extends Controller
{
    /**
     * Public: Display a listing of approved essays.
     */
    public function publicIndex(Request $request): Response
    {
        $query = Essay::where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $essays = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Essays/Index', [
            'seo' => [
                'title' => 'স্মৃতিচারণ প্রবন্ধ | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়',
                'description' => 'জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের প্রাক্তন শিক্ষার্থীদের স্মৃতিচারণমূলক প্রবন্ধ ও রচনা সমূহ।',
            ],
            'essays' => $essays,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Public: Show the form for writing a new essay.
     */
    public function publicCreate(): Response
    {
        return Inertia::render('Essays/Create', [
            'seo' => [
                'title' => 'স্মৃতিচারণ প্রবন্ধ লিখুন | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়',
                'description' => 'বিদ্যালয়ের ৬০ বছরের গৌরবগাথা নিয়ে আপনার মধুর স্মৃতি বা স্মৃতিচারণমূলক প্রবন্ধ লিখুন।',
            ]
        ]);
    }

    /**
     * Public: Store a newly submitted essay.
     */
    public function publicStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'author_batch' => 'required|string|max:50',
            'author_phone' => 'required|string|max:20',
            'author_email' => 'nullable|email|max:255',
            'content' => 'required|string|max:50000',
        ]);

        $validated['status'] = 'pending'; // Requires approval

        Essay::create($validated);

        return redirect()->route('essays.index')->with('success', 'ধন্যবাদ! আপনার স্মৃতিচারণ প্রবন্ধটি সফলভাবে জমা হয়েছে। এডমিন অনুমোদনের পর তা ওয়েবসাইটে প্রকাশিত হবে।');
    }

    /**
     * Public: Show a single approved essay.
     */
    public function publicShow(Essay $essay): Response
    {
        if ($essay->status !== 'approved') {
            abort(403, 'এই প্রবন্ধটি এখনও অনুমোদিত হয়নি।');
        }

        return Inertia::render('Essays/Show', [
            'seo' => [
                'title' => $essay->title . ' - স্মৃতিচারণ প্রবন্ধ',
                'description' => mb_substr(strip_tags($essay->content), 0, 150) . '...',
            ],
            'essay' => $essay,
        ]);
    }

    /**
     * Admin: Display list of all essays.
     */
    public function index(Request $request): Response
    {
        $query = Essay::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('author_batch', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $essays = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Essays/Index', [
            'essays' => $essays,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Admin: Change status of an essay (approve/reject).
     */
    public function updateStatus(Request $request, Essay $essay): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $essay->update(['status' => $validated['status']]);

        $statusBn = $validated['status'] === 'approved' ? 'অনুমোদন' : ($validated['status'] === 'rejected' ? 'বাতিল' : 'পেন্ডিং');

        return redirect()->back()->with('success', "প্রবন্ধটি সফলভাবে {$statusBn} করা হয়েছে।");
    }

    /**
     * Admin: Delete an essay registration.
     */
    public function destroy(Essay $essay): RedirectResponse
    {
        $essay->delete();

        return redirect()->back()->with('success', 'প্রবন্ধটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
