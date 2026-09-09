<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CoverageController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Homepage\ShowHomepageController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHomepageController::class)->name('home');

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
