<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebird\GhrController;
use App\Http\Controllers\BarcodeSearchController;
use App\Http\Controllers\NfcSearchController;
use App\Http\Controllers\PruefController;

/*
Route::get('/', function () {
    return redirect()->route('firebird.ghr.index');
});

Route::prefix('firebird')->name('firebird.')->group(function () {
    Route::resource('ghr', GhrController::class)->only(['index', 'show']);
    // Für weitere Modelle: Route::resource('tabellenname', Controller::class)->only(['index','show']);
});

Route::get('/testbarcode-search', [BarcodeSearchController::class, 'index'])->name('testbarcode.search');
Route::post('/testbarcode-search', [BarcodeSearchController::class, 'search'])->name('testbarcode.search.post');

Route::get('/nfc/search/{token}', [NfcSearchController::class, 'search'])->name('nfc.search');

Route::post('/pruefung/{id}/done', [PruefController::class, 'markAsDone'])->name('pruefung.done');
*/

Route::get('/', function () {
    return redirect()->route('barcode.index');
});

// Manuelle Eingabe über Formular
Route::get('/barcode', [BarcodeSearchController::class, 'index'])->name('barcode.index');

// Suchformular sendet POST
Route::post('/barcode/search', [BarcodeSearchController::class, 'search'])->name('barcode.search');

// NFC / Direkt-URL-Suche
Route::get('/nfc/search/{token}', [BarcodeSearchController::class, 'searchByToken'])->name('barcode.search.token');

// Prüfung erledigen
Route::post('/pruefung/{id}/done', [PruefController::class, 'markAsDone'])->name('pruefung.done');
// Prüfungen erstellen
Route::post('/pruefung/store', [PruefController::class, 'store'])->name('pruefung.store');