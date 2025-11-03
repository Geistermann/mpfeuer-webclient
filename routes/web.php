<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebird\GhrController;
use App\Http\Controllers\BarcodeSearchController;

Route::get('/', function () {
    return redirect()->route('firebird.ghr.index');
});

Route::prefix('firebird')->name('firebird.')->group(function () {
    Route::resource('ghr', GhrController::class)->only(['index', 'show']);
    // Für weitere Modelle: Route::resource('tabellenname', Controller::class)->only(['index','show']);
});

Route::get('/barcode-search', [BarcodeSearchController::class, 'index'])->name('barcode.search');
Route::post('/barcode-search', [BarcodeSearchController::class, 'search'])->name('barcode.search.post');

