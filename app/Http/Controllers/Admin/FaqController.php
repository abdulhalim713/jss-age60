<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('id')->get();

        return Inertia::render('Admin/Faq/Index', [
            'faqs' => $faqs,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Faq/CreateEdit', [
            'faq' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active']  = $data['is_active']  ?? true;

        Faq::create($data);

        return redirect()->route('admin.faq.index')
            ->with('success', 'প্রশ্নটি সফলভাবে যুক্ত করা হয়েছে।');
    }

    public function edit(Faq $faq)
    {
        return Inertia::render('Admin/Faq/CreateEdit', [
            'faq' => $faq,
        ]);
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active']  = $data['is_active']  ?? true;

        $faq->update($data);

        return redirect()->route('admin.faq.index')
            ->with('success', 'প্রশ্নটি সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faq.index')
            ->with('success', 'প্রশ্নটি মুছে ফেলা হয়েছে।');
    }
}
