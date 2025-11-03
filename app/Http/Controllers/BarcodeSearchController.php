<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firebird\GhrStamm;
use App\Models\Firebird\KldStamm;

class BarcodeSearchController extends Controller
{
    /**
     * Zeigt das Suchformular
     */
    public function index()
    {
        return view('barcode_search');
    }

    /**
     * Führt die Barcode-Suche aus
     */
    public function search(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = $request->input('barcode');

        // Liste aller Firebird-Models mit findByBarcode
        $models = [
            GhrStamm::class,
            KldStamm::class,
        ];

        $results = [];

        foreach ($models as $modelClass) {
            $item = $modelClass::findByBarcode($barcode);
            if ($item) {
                $results[$modelClass] = $item;
            }
        }

        return view('barcode_search', compact('results', 'barcode'));
    }
}
