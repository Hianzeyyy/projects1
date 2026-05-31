
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('splash');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Student/Faculty Features
Route::middleware(['auth'])->group(function () {
    // Report Abuse
    Route::get('/report', [App\Http\Controllers\ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [App\Http\Controllers\ReportController::class, 'store'])->name('report.store');
    // Evidence Vault
    Route::get('/evidence-vault', [App\Http\Controllers\EvidenceVaultController::class, 'index'])->name('evidence.index');
    Route::post('/evidence-vault/upload', [App\Http\Controllers\EvidenceVaultController::class, 'upload'])->name('evidence.upload');
    Route::delete('/evidence-vault/{id}', [App\Http\Controllers\EvidenceVaultController::class, 'destroy'])->name('evidence.destroy');
    // Suggestions
    Route::get('/suggestions', [App\Http\Controllers\SuggestionController::class, 'index'])->name('suggestions.index');
    Route::post('/suggestions', [App\Http\Controllers\SuggestionController::class, 'store'])->name('suggestions.store');
    Route::post('/suggestions/{id}/upvote', [App\Http\Controllers\SuggestionController::class, 'upvote'])->name('suggestions.upvote');
    // Safe-Walk Timer
    Route::get('/safewalk', [App\Http\Controllers\SafeWalkController::class, 'index'])->name('safewalk.index');
    Route::post('/safewalk/start', [App\Http\Controllers\SafeWalkController::class, 'start'])->name('safewalk.start');
    Route::post('/safewalk/panic', [App\Http\Controllers\SafeWalkController::class, 'panic'])->name('safewalk.panic');
    // Wellness Resources
    Route::get('/wellness', [App\Http\Controllers\WellnessController::class, 'index'])->name('wellness.index');
});

// User Dashboard Route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Admin Features
Route::prefix('admin')->middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/inbox', [App\Http\Controllers\Admin\InboxController::class, 'index'])->name('admin.inbox');
    Route::post('/inbox/{id}/status', [App\Http\Controllers\Admin\InboxController::class, 'updateStatus'])->name('admin.inbox.status');
    Route::post('/inbox/{id}/assign', [App\Http\Controllers\Admin\InboxController::class, 'assign'])->name('admin.inbox.assign');
    Route::post('/inbox/{id}/note', [App\Http\Controllers\Admin\InboxController::class, 'addNote'])->name('admin.inbox.note');
    Route::get('/suggestions', [App\Http\Controllers\Admin\SuggestionAdminController::class, 'index'])->name('admin.suggestions');
    Route::post('/suggestions/{id}/approve', [App\Http\Controllers\Admin\SuggestionAdminController::class, 'approve'])->name('admin.suggestions.approve');
    Route::post('/suggestions/{id}/reject', [App\Http\Controllers\Admin\SuggestionAdminController::class, 'reject'])->name('admin.suggestions.reject');
    Route::post('/suggestions/{id}/respond', [App\Http\Controllers\Admin\SuggestionAdminController::class, 'respond'])->name('admin.suggestions.respond');
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/users/{id}/update', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});

// Stealth Exit (Library Catalog)
Route::get('/library-catalog', function () {
    return view('library-catalog');
})->name('library.catalog');
