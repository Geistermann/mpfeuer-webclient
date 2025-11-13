<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firebird\GhrStamm;
use App\Models\Firebird\KldStamm;
use App\Models\Firebird\GasStamm;
use App\Models\Firebird\GelStamm;
use App\Models\Firebird\GhrPruef;
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
    public function search(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = trim($request->input('barcode'));
        return $this->performSearch($barcode);
    }

    /**
     * Führt die NFC/URL-basierte Suche aus
     */
    public function searchByToken($token)
    {
        return $this->performSearch($token);
    }

    /**
     * Interne Suchlogik, die bei beiden Varianten verwendet wird
     */
    private function performSearch($barcode)
    {
        $models = [
            GhrStamm::class,
            KldStamm::class,
            GasStamm::class,
            GelStamm::class,
        ];

        $results = [];
        $allPruefungen = [];

        foreach ($models as $modelClass) {
            $item = $modelClass::findByBarcode($barcode);
            if ($item) {
                $results[$modelClass] = $item;

                // Wenn GhrStamm → zugehörige Prüfungen laden
                if ($modelClass === GhrStamm::class && isset($item->GHR_ID)) {
                    $pruefungen = GhrPruef::forGhr($item->GHR_ID);
                    $allPruefungen = array_merge($allPruefungen, $pruefungen);
                }
            }
        }

        return view('barcode.results', compact('barcode', 'results', 'allPruefungen'));
    }    
}
