<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FeedbackController extends Controller
{
    /**
     * Store feedback from frontend.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'type' => 'required|in:suggestion,complaint',
            'message' => 'required|string|max:5000',
        ]);

        Feedback::create($validated);

        return redirect()->back()->with('success', 'আপনার মতামত/অভিযোগ সফলভাবে গ্রহণ করা হয়েছে। ধন্যবাদ!');
    }

    /**
     * Display feedback listing in admin.
     */
    public function index(Request $request): Response
    {
        $query = Feedback::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Type filter (complaint or suggestion)
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $feedbacks = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Feedback/Index', [
            'feedbacks' => $feedbacks,
            'filters' => $request->only(['search', 'type']),
        ]);
    }

    /**
     * Delete a feedback message.
     */
    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return redirect()->back()->with('success', 'বার্তাটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
