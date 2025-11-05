<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firebird\GhrStamm;
use App\Models\Firebird\KldStamm;

class NfcSearchController extends Controller
{
    public function search($token)
    {
        $models = [
            GhrStamm::class,
            KldStamm::class,
        ];

        $results = [];

        foreach ($models as $modelClass) {
            if (method_exists($modelClass, 'findByBarcode')) {
                $item = $modelClass::findByBarcode($token);
                if ($item) {
                    // falls es ein GhrStamm-Datensatz ist → lade Prüfungen
                    $pruefungen = [];
                    if ($modelClass === GhrStamm::class && isset($item->GHR_ID)) {
                        $pruefungen = GhrStamm::getPruefungen($item->GHR_ID);
                    }

                    $results[] = [
                        'model' => $modelClass,
                        'item' => $item,
                        'pruefungen' => $pruefungen,
                    ];
                }
            }
        }

        return view('nfc_search_results', [
            'token' => $token,
            'results' => $results,
        ]);
    }
}
