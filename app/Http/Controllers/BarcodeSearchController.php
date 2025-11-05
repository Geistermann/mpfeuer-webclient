<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firebird\GhrStamm;
use App\Models\Firebird\KldStamm;
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

    /**
     * Markiert eine Prüfung als erledigt
     */
    /*public function markDone(Request $request, $id)
    {
        $request->validate([
            'handzeichen' => 'required|string|max:10',
        ]);

        $handzeichen = strtoupper(trim($request->input('handzeichen')));

        try {
            DB::connection('firebird')->update("
                UPDATE GHR_PRUEF 
                SET GHR_PRUEF_HDZ = ?, 
                    GHR_PRUEF_OK = 1 
                WHERE GHR_ID = ?
            ", [$handzeichen, $id]);

            return back()->with('success', 'Prüfung erfolgreich erledigt.');
        } catch (\Exception $e) {
            return back()->with('error', 'Fehler beim Aktualisieren der Prüfung: ' . $e->getMessage());
        }
    }*/
}
