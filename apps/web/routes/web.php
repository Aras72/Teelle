<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\SavedGameController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CoverageController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Homepage\ShowHomepageController;
use App\Http\Controllers\Match\QuickMatchController;
use App\Http\Controllers\Play\GameDetailController;
use App\Http\Controllers\Play\MatchResultsController;
use App\Http\Controllers\Play\PlayController;
use App\Http\Controllers\Play\ResultCoverController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHomepageController::class)->name('home');
Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:auth')->name('register.store');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:auth')->name('login.store');
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->middleware('throttle:recovery')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:recovery')->name('password.update');
});
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
    Route::middleware('verified')->group(function (): void {
        Route::get('/account', [AccountController::class, 'show'])->name('account.show');
        Route::put('/account/settings', [AccountController::class, 'update'])->name('account.update');
        Route::post('/plays/{play:public_id}/save', [SavedGameController::class, 'store'])->name('plays.save');
        Route::delete('/account/saved/{game:public_id}', [SavedGameController::class, 'destroy'])->name('account.saved.destroy');
        Route::get('/account/saved/{game:public_id}/cover', [SavedGameController::class, 'cover'])->name('account.saved.cover');
    });
});
Route::get('/match', [QuickMatchController::class, 'show'])->name('match.show');
Route::middleware('throttle:match')->group(function (): void {
    Route::post('/match/answer', [QuickMatchController::class, 'answer'])->name('match.answer');
    Route::post('/match/back', [QuickMatchController::class, 'back'])->name('match.back');
    Route::post('/match/restart', [QuickMatchController::class, 'restart'])->name('match.restart');
});
Route::get('/matches/{match:public_id}', MatchResultsController::class)->name('matches.show');
Route::get('/matches/{match:public_id}/games/{rank}', GameDetailController::class)->whereNumber('rank')->name('matches.games.show');
Route::get('/matches/{match:public_id}/games/{rank}/cover', ResultCoverController::class)->whereNumber('rank')->name('matches.games.cover');
Route::middleware('throttle:play')->group(function (): void {
    Route::post('/matches/{match:public_id}/games/{rank}/start', [PlayController::class, 'start'])->whereNumber('rank')->name('matches.games.start');
    Route::post('/plays/{play:public_id}/complete', [PlayController::class, 'complete'])->name('plays.complete');
    Route::post('/plays/{play:public_id}/rate', [PlayController::class, 'rate'])->name('plays.rate');
});
Route::get('/plays/{play:public_id}', [PlayController::class, 'show'])->name('plays.show');

Route::prefix('admin/content')->name('admin.content.')->middleware('content.staff')->group(function (): void {
    Route::get('/', [ContentController::class, 'index'])->name('index');
    Route::get('/coverage', CoverageController::class)->name('coverage');
    Route::get('/new', [ContentController::class, 'create'])->name('create');
    Route::post('/', [ContentController::class, 'store'])->name('store');
    Route::get('/versions/{version}/edit', [ContentController::class, 'edit'])->name('edit');
    Route::put('/versions/{version}', [ContentController::class, 'update'])->name('update');
    Route::post('/versions/{version}/structured-metadata', [ContentController::class, 'structuredMetadata'])->name('structured-metadata');
    Route::post('/versions/{version}/submit', [ContentController::class, 'submit'])->name('submit');
    Route::post('/versions/{version}/review', [ContentController::class, 'review'])->name('review');
    Route::post('/versions/{version}/publish', [ContentController::class, 'publish'])->name('publish');
    Route::post('/versions/{version}/media', [MediaController::class, 'store'])->name('media.store');
    Route::post('/media/{asset:public_id}/review', [MediaController::class, 'review'])->name('media.review');
    Route::get('/media/{asset:public_id}/preview', [MediaController::class, 'show'])->name('media.show');
    Route::post('/games/{game:public_id}/unpublish', [ContentController::class, 'unpublish'])->name('unpublish');
    Route::get('/games/{game:public_id}/revision', [ContentController::class, 'revision'])->name('revision');
    Route::post('/games/{game:public_id}/revision', [ContentController::class, 'storeRevision'])->name('revision.store');
    Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
    Route::post('/imports/preview', [ImportController::class, 'preview'])->name('imports.preview');
    Route::post('/imports/pilot/preview', [ImportController::class, 'previewPilot'])->name('imports.pilot.preview');
    Route::get('/imports/{batch:public_id}', [ImportController::class, 'show'])->name('imports.show');
    Route::post('/imports/{batch:public_id}/confirm', [ImportController::class, 'confirm'])->name('imports.confirm');
    Route::post('/imports/{batch:public_id}/rollback', [ImportController::class, 'rollback'])->name('imports.rollback');
});
