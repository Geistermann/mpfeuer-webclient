<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebird\GhrController;
use App\Http\Controllers\BarcodeSearchController;
use App\Http\Controllers\NfcSearchController;
use App\Http\Controllers\PruefController;

Route::get('/', function () {
    return redirect()->route('barcode.index');
});

// Manuelle Eingabe über Formular
Route::get('/barcode', [BarcodeSearchController::class, 'index'])->name('barcode.index');

// Suchformular sendet POST
Route::post('/barcode/search', [BarcodeSearchController::class, 'searchByUserInput'])->name('barcode.search');

// NFC / Direkt-URL-Suche
Route::get('/nfc/search/{token}', [BarcodeSearchController::class, 'searchByToken'])->name('barcode.search.token');

// Prüfung erledigen
Route::post('/pruefung/{id}/done', [PruefController::class, 'markAsDone'])->name('pruefung.done');
// Prüfungen erstellen
Route::post('/pruefung/store', [PruefController::class, 'store'])->name('pruefung.store');