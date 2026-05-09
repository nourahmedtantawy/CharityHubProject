<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home → redirect to campaigns listing
Route::get('/', fn () => redirect()->route('campaigns.index'))->name('home');

// Campaigns
Route::prefix('campaigns')->name('campaigns.')->group(function () {
    Route::get('/',        [CampaignController::class, 'index'])->name('index');
    Route::get('/{slug}',  [CampaignController::class, 'show'])->name('show');
});

// Donations
Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'store'])->name('donations.store');
Route::get('/donations/success',            [DonationController::class, 'success'])->name('donations.success');

// Certificate verification
Route::get('/certificates/verify/{token}', function (string $token) {
    $cert = \App\Models\DonorCertificate::where('verification_token', $token)
        ->with('donation.campaign')
        ->firstOrFail();
    return view('certificates.verify', compact('cert'));
})->name('certificates.verify');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Donor dashboard (replaces the missing "dashboard" route)
    Route::get('/dashboard', function () {
        return redirect()->route('campaigns.index');
    })->name('dashboard');

    // Profile (Breeze profile routes)
    Route::get('/profile',    [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Webhooks (excluded from CSRF in bootstrap/app.php)
|--------------------------------------------------------------------------
*/

Route::post('/webhook/{gateway}', [DonationController::class, 'webhook'])->name('webhook');

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';