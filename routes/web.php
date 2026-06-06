<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\Admin\HeroSettingController;
use App\Http\Controllers\Admin\CommitteeMemberController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Alumni;
use App\Models\HeroSetting;
use App\Models\CommitteeMember;
use App\Models\MediaGallery;
use App\Models\Setting;
use App\Models\Sponsor;
use App\Http\Controllers\Admin\SponsorController;


Route::get('/', function () {
    $approvedAlumni = Alumni::where('status', 'approved')
        ->orderBy('created_at', 'desc')
        ->get();
    $totalCount    = Alumni::count();
    $approvedCount = Alumni::where('status', 'approved')->count();
    $heroes        = HeroSetting::orderBy('sort_order')->get();
    $committee     = CommitteeMember::orderBy('sort_order')->get();
    $gallery       = MediaGallery::orderBy('sort_order')->get();
    $eventDate     = Setting::get('event_date', '2027-04-10');
    $sponsors      = Sponsor::orderBy('sort_order')->get();

    return Inertia::render('JssAge60', [
        'initialAlumni'         => $approvedAlumni,
        'initialRegisteredCount'=> $totalCount,
        'approvedCount'         => $approvedCount,
        'heroSettings'          => $heroes,
        'committeeMembers'      => $committee,
        'galleryItems'          => $gallery,
        'eventDate'             => $eventDate,
        'sponsors'              => $sponsors,
    ]);
});

// Public friend list page with simple filters
Route::get('/friends', function (Request $request) {
    $query = Alumni::query()->where('status', 'approved');

    if ($request->filled('search')) {
        $q = $request->input('search');
        $query->where(function ($sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%");
        });
    }

    if ($request->filled('batch')) {
        $query->where('batch', $request->input('batch'));
    }

    $alumni = $query->orderBy('name')->paginate(15)->withQueryString();
    $batches = Alumni::select('batch')->distinct()->orderBy('batch')->pluck('batch')->toArray();

    return Inertia::render('Friends/Index', [
        'alumni' => $alumni,
        'filters' => [
            'search' => $request->input('search', ''),
            'batch' => $request->input('batch', ''),
        ],
        'batches' => $batches,
    ]);
})->name('friends.index');

Route::post('/alumni/register', [AlumniController::class, 'publicRegister'])->name('alumni.register');

Route::get('/dashboard', [AlumniController::class, 'adminDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Alumni management
    Route::get('/admin/alumni', [AlumniController::class, 'index'])->name('admin.alumni.index');
    Route::put('/admin/alumni/{alumnus}', [AlumniController::class, 'update'])->name('admin.alumni.update');
    Route::delete('/admin/alumni/{alumnus}', [AlumniController::class, 'destroy'])->name('admin.alumni.destroy');
    Route::patch('/admin/alumni/{alumnus}/status', [AlumniController::class, 'updateStatus'])->name('admin.alumni.status');

    // Hero settings management
    Route::resource('/admin/hero', HeroSettingController::class)->names('admin.hero');

    // Gallery management
    Route::resource('/admin/gallery', GalleryController::class)->names('admin.gallery');

    // Sponsors management
    Route::resource('/admin/sponsors', SponsorController::class)->names('admin.sponsors');

    // Committee members management
    Route::get('/admin/committee', [CommitteeMemberController::class, 'index'])->name('admin.committee.index');
    Route::get('/admin/committee/create', [CommitteeMemberController::class, 'create'])->name('admin.committee.create');
    Route::post('/admin/committee', [CommitteeMemberController::class, 'store'])->name('admin.committee.store');
    Route::get('/admin/committee/{committee}/edit', [CommitteeMemberController::class, 'edit'])->name('admin.committee.edit');
    Route::put('/admin/committee/{committee}', [CommitteeMemberController::class, 'update'])->name('admin.committee.update');
    Route::delete('/admin/committee/{committee}', [CommitteeMemberController::class, 'destroy'])->name('admin.committee.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings
    Route::get('/admin/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // User management
    Route::resource('/admin/users', UserController::class)->names('admin.users');
});

require __DIR__.'/auth.php';
