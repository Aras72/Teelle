<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\ChildProfileController;
use App\Http\Controllers\Account\PrivacyController;
use App\Http\Controllers\Account\SavedGameController;
use App\Http\Controllers\Account\SupportTicketController as AccountSupportTicketController;
use App\Http\Controllers\Admin\ArticleCategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CoverageController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PrivacyAccountController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\SupportTicketController as AdminSupportTicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\OnboardingController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Homepage\ShowHomepageController;
use App\Http\Controllers\Jigari\JigariController;
use App\Http\Controllers\Jigari\JigariGameController;
use App\Http\Controllers\Jigari\JigariGameCoverController;
use App\Http\Controllers\Jigari\JigariSearchController;
use App\Http\Controllers\MagazineController;
use App\Http\Controllers\Match\QuickMatchController;
use App\Http\Controllers\Play\GameDetailController;
use App\Http\Controllers\Play\MatchResultsController;
use App\Http\Controllers\Play\PlayController;
use App\Http\Controllers\Play\ResultCoverController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHomepageController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::view('/privacy', 'privacy')->name('privacy');
Route::get('/magazine', [MagazineController::class, 'index'])->name('magazine.index');
Route::get('/magazine/{article:slug}', [MagazineController::class, 'show'])->name('magazine.show');
Route::get('/magazine/{article:slug}/cover', [MagazineController::class, 'cover'])->name('magazine.cover');
Route::get('/jigari', JigariController::class)->name('jigari.show');
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
        Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
        Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    });
    Route::middleware(['verified', 'onboarded'])->group(function (): void {
        Route::get('/account', [AccountController::class, 'show'])->name('account.show');
        Route::put('/account/settings', [AccountController::class, 'update'])->name('account.update');
        Route::post('/account/tickets', [AccountSupportTicketController::class, 'store'])->middleware('throttle:6,1')->name('account.tickets.store');
        Route::prefix('/account/privacy')->name('account.privacy.')->middleware('throttle:privacy')->group(function (): void {
            Route::post('/export', [PrivacyController::class, 'export'])->name('export');
            Route::post('/deletion', [PrivacyController::class, 'requestDeletion'])->name('deletion.store');
            Route::delete('/deletion', [PrivacyController::class, 'cancelDeletion'])->name('deletion.destroy');
        });
        Route::post('/plays/{play:public_id}/save', [SavedGameController::class, 'store'])->name('plays.save');
        Route::delete('/account/saved/{game:public_id}', [SavedGameController::class, 'destroy'])->name('account.saved.destroy');
        Route::get('/account/saved/{game:public_id}/cover', [SavedGameController::class, 'cover'])->name('account.saved.cover');
        Route::prefix('/account/children')->name('account.children.')->middleware('jigari')->group(function (): void {
            Route::get('/', [ChildProfileController::class, 'index'])->name('index');
            Route::get('/new', [ChildProfileController::class, 'create'])->name('create');
            Route::post('/', [ChildProfileController::class, 'store'])->name('store');
            Route::get('/{child}/edit', [ChildProfileController::class, 'edit'])->name('edit');
            Route::put('/{child}', [ChildProfileController::class, 'update'])->name('update');
            Route::post('/{child}/archive', [ChildProfileController::class, 'archive'])->name('archive');
        });
        Route::prefix('/jigari/games')->name('jigari.games.')->middleware(['jigari', 'throttle:search'])->group(function (): void {
            Route::get('/', JigariSearchController::class)->name('index');
            Route::get('/{game:public_id}', JigariGameController::class)->name('show');
            Route::get('/{game:public_id}/cover', JigariGameCoverController::class)->name('cover');
        });
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

if (app()->environment('local')) {
    Route::get('/_preview/errors/{code}', function (string $code) {
        abort_unless(in_array($code, ['403', '404', '419', '429', '500', '503'], true), 404);

        return response()->view("errors.{$code}");
    })->name('preview.errors');
}

Route::get('/teelle-asset-index.txt', function () {
    return response(config('teelle.deploy_version', '0'), 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8')
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
})->name('asset.index');

Route::prefix('admin/content')->name('admin.content.')->middleware('content.staff')->group(function (): void {
    Route::get('/', [ContentController::class, 'index'])->name('index');
    Route::get('/coverage', CoverageController::class)->name('coverage');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports.csv', [ReportController::class, 'csv'])->name('reports.csv');
    Route::get('/reports.pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::put('/plans/{plan:code}', [PlanController::class, 'update'])->name('plans.update');
    Route::get('/tickets', [AdminSupportTicketController::class, 'index'])->name('tickets.index');
    Route::put('/tickets/{supportTicket:public_id}', [AdminSupportTicketController::class, 'update'])->name('tickets.update');
    Route::post('/tickets/deletions/{privacyRequest:public_id}/reactivate', [PrivacyAccountController::class, 'reactivate'])->name('tickets.reactivate');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/site-content', [SiteContentController::class, 'edit'])->name('site.edit');
    Route::put('/site-content', [SiteContentController::class, 'update'])->name('site.update');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/new', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article:slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article:slug}', [ArticleController::class, 'update'])->name('articles.update');
    Route::post('/articles/{article:slug}/submit', [ArticleController::class, 'submit'])->name('articles.submit');
    Route::post('/articles/{article:slug}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
    Route::post('/articles/{article:slug}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');
    Route::get('/articles/{article:slug}/cover', [ArticleController::class, 'cover'])->name('articles.cover');
    Route::get('/article-categories', [ArticleCategoryController::class, 'index'])->name('article-categories.index');
    Route::post('/article-categories', [ArticleCategoryController::class, 'store'])->name('article-categories.store');
    Route::put('/article-categories/{category:slug}', [ArticleCategoryController::class, 'update'])->name('article-categories.update');
    Route::delete('/article-categories/{category:slug}', [ArticleCategoryController::class, 'destroy'])->name('article-categories.destroy');
    Route::get('/new', [ContentController::class, 'create'])->name('create');
    Route::post('/', [ContentController::class, 'store'])->name('store');
    Route::get('/versions/{version}/edit', [ContentController::class, 'edit'])->name('edit');
    Route::get('/versions/{version}/review', [ContentController::class, 'showReview'])->name('review.show');
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
    Route::get('/imports/template', [ImportController::class, 'template'])->name('imports.template');
    Route::post('/imports/excel/preview', [ImportController::class, 'previewExcel'])->name('imports.excel.preview');
    Route::post('/imports/form/preview', [ImportController::class, 'previewForm'])->name('imports.form.preview');
    Route::get('/imports/{batch:public_id}', [ImportController::class, 'show'])->name('imports.show');
    Route::post('/imports/{batch:public_id}/confirm', [ImportController::class, 'confirm'])->name('imports.confirm');
    Route::post('/imports/{batch:public_id}/rollback', [ImportController::class, 'rollback'])->name('imports.rollback');
});
