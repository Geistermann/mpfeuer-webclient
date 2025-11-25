<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firebird\GhrStamm;
use App\Models\Firebird\KldStamm;
use App\Models\Firebird\GasStamm;
use App\Models\Firebird\GelStamm;
use App\Models\Firebird\GhrPruef;
use App\Models\Firebird\ModuleRegistry;
use Illuminate\Support\Facades\DB;

class BarcodeSearchController extends Controller
{
    /**
     * Zeigt das Suchformular
     */
    public function index()
    {
        return view('barcode.index');
    }

    /**
     * Führt die manuelle Suche aus
     */
    public function searchByUserInput(Request $request)
    {
        $barcode = $request->input('barcode');
        return $this->performSearch($barcode);
    }

    /**
     * Führt die NFC/URL-basierte Suche aus
     */
    public function searchByToken($token)
    {
        // Token vorbereiten
        $originalToken = $token;
        $token = strtoupper(trim(urldecode($token)));

        // Unsichtbare Steuerzeichen entfernen
        $token = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $token);

        // Direkt Fehler anzeigen auf results.blade
        $renderError = function($msg) use ($originalToken) {
            return view('barcode.results', [
                'results' => [],
                'barcode' => $originalToken,
                'error'   => $msg,
            ]);
        };

        // Prüfung auf leer
        if (empty($token)) {
            return $renderError('Der übergebene Token ist leer oder ungültig.');
        }

        // Zeichenvalidierung
        if (!preg_match('/^[A-Z0-9\-_]+$/', $token)) {
            return $renderError('Der Token enthält ungültige Zeichen. Erlaubt sind nur A–Z, 0–9, - und _.');
        }

        // Token gültig → Suche ausführen
        return $this->performSearch($token);
    }



    /**
     * Interne Suchlogik, die bei beiden Varianten verwendet wird
     */
    private function performSearch($barcode)
    {        
        $results = [];                

        foreach (ModuleRegistry::getStammModels() as $model) {

            if ($item = $model::findByBarcode($barcode)) {
                $results[$model] = $item;

                // global sichern
                session(['current_record' => $item]);
                session(['current_model' => $model]);                
            }
        }

        return view('barcode.results', compact('results', 'barcode'));
    }  
}
