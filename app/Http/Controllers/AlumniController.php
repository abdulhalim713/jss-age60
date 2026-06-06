<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AlumniController extends Controller
{
    /**
     * Store registration from public landing page (defaults status to 'pending').
     */
    public function publicRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'batch' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        Alumni::create($validated);

        return redirect()->back()->with('success', 'ধন্যবাদ! আপনার নিবন্ধন সফলভাবে জমা হয়েছে। এডমিন অনুমোদনের পর তালিকাভুক্ত হবে।');
    }

    /**
     * Calculate admin metrics and render dashboard.
     */
    public function adminDashboard(): Response
    {
        $total = Alumni::count();
        $pending = Alumni::where('status', 'pending')->count();
        $approved = Alumni::where('status', 'approved')->count();
        $rejected = Alumni::where('status', 'rejected')->count();
        
        $today = Alumni::whereDate('created_at', Carbon::today())->count();

        // Batch-wise stats
        $batchStats = Alumni::select('batch', DB::raw('count(*) as total'))
            ->groupBy('batch')
            ->orderBy('batch', 'asc')
            ->get();

        // Recent registrations (limit to 10)
        $recentAlumni = Alumni::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'metrics' => [
                'total' => $total,
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'today' => $today,
            ],
            'batchStats' => $batchStats,
            'recentAlumni' => $recentAlumni,
        ]);
    }

    /**
     * View registrations list in the admin panel with filters and pagination.
     */
    public function index(Request $request): Response
    {
        $query = Alumni::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('batch', 'like', "%{$search}%");
            });
        }

        // Batch filter
        if ($request->filled('batch')) {
            $query->where('batch', $request->input('batch'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Paginate results
        $alumni = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get distinct batches for dropdown list
        $batches = Alumni::select('batch')
            ->distinct()
            ->orderBy('batch', 'asc')
            ->pluck('batch');

        return Inertia::render('Admin/Alumni/Index', [
            'alumni' => $alumni,
            'filters' => $request->only(['search', 'batch', 'status']),
            'batches' => $batches,
        ]);
    }

    /**
     * Update details of an alumnus.
     */
    public function update(Request $request, Alumni $alumnus): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'batch' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $alumnus->update($validated);

        return redirect()->back()->with('success', 'তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Change status of alumnus (approve/reject).
     */
    public function updateStatus(Request $request, Alumni $alumnus): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $alumnus->update(['status' => $validated['status']]);

        $message = 'আবেদন সফলভাবে আপডেট করা হয়েছে।';
        if ($validated['status'] === 'approved') {
            $message = 'আবেদনটি সফলভাবে অনুমোদন করা হয়েছে।';
        } elseif ($validated['status'] === 'rejected') {
            $message = 'আবেদনটি বাতিল করা হয়েছে।';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete an alumnus registration.
     */
    public function destroy(Alumni $alumnus): RedirectResponse
    {
        $alumnus->delete();

        return redirect()->back()->with('success', 'নিবন্ধন সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
