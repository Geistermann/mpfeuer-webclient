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
