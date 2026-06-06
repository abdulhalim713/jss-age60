<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CommitteeMemberController extends Controller
{
    /**
     * List all committee members.
     */
    public function index(): Response
    {
        $members = CommitteeMember::orderBy('sort_order')->get();

        return Inertia::render('Admin/Committee/Index', [
            'members' => $members,
        ]);
    }

    /**
     * Show form to create a new member.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Committee/CreateEdit', [
            'member' => null,
        ]);
    }

    /**
     * Store a new committee member.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'icon'       => 'required|string|max:100',
            'sort_order' => 'required|integer|min:0',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'mobile'     => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('committee', 'public');
        }

        CommitteeMember::create($validated);

        return redirect()->route('admin.committee.index')
            ->with('success', 'কমিটি সদস্য সফলভাবে যোগ করা হয়েছে।');
    }

    /**
     * Show form to edit an existing member.
     */
    public function edit(CommitteeMember $committee): Response
    {
        return Inertia::render('Admin/Committee/CreateEdit', [
            'member' => $committee,
        ]);
    }

    /**
     * Update an existing committee member.
     */
    public function update(Request $request, CommitteeMember $committee): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'icon'       => 'required|string|max:100',
            'sort_order' => 'required|integer|min:0',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'mobile'     => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('image')) {
            if ($committee->image && Storage::disk('public')->exists($committee->image)) {
                Storage::disk('public')->delete($committee->image);
            }
            $validated['image'] = $request->file('image')->store('committee', 'public');
        } else {
            // Keep existing image
            unset($validated['image']);
        }

        $committee->update($validated);

        return redirect()->route('admin.committee.index')
            ->with('success', 'কমিটি সদস্য সফলভাবে আপডেট হয়েছে।');
    }

    /**
     * Delete a committee member.
     */
    public function destroy(CommitteeMember $committee): RedirectResponse
    {
        if ($committee->image && Storage::disk('public')->exists($committee->image)) {
            Storage::disk('public')->delete($committee->image);
        }

        $committee->delete();

        return redirect()->route('admin.committee.index')
            ->with('success', 'কমিটি সদস্য মুছে ফেলা হয়েছে।');
    }
}
