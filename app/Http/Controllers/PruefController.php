<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firebird\GhrPruef;

class PruefController extends Controller
{
    /**
     * Prüfung als erledigt markieren
     */
    public function markAsDone(Request $request, $id)
    {
        $handzeichen = $request->input('handzeichen');

        if (empty($handzeichen)) {
            return redirect()
                ->back()
                ->with('error', 'Kein Handzeichen angegeben.');
        }

        $success = GhrPruef::markAsDone($id, $handzeichen);

        if ($success) {
            return redirect()
                ->route('barcode.index')
                ->with('success', '✅ Prüfung wurde erfolgreich als erledigt markiert.');
        }

        return redirect()
            ->back()
            ->with('error', 'Aktualisierung fehlgeschlagen.');
    }

    /**
     * Formular zum Erstellen einer neuen Prüfung anzeigen (optional separat)
     */
    public function create($ghrIndex)
    {
        $availablePruefungen = GhrPruef::getAvailablePruefungen();
        return view('pruef_create', compact('availablePruefungen', 'ghrIndex'));
    }

    /**
     * Neue Prüfung speichern
     */
    public function store(Request $request)
    {
        $request->validate([
            'ghr_index' => 'required|string',            
            'par_pruef_id' => 'required|string',            
            'handzeichen' => 'required|string|max:7',
        ]);

        $success = GhrPruef::createNewPruefung(
            $request->input('ghr_index'),            
            $request->input('par_pruef_id'),            
            $request->input('handzeichen')
        );

        if ($success) {
            return redirect()
                ->route('barcode.index')
                ->with('success', '✅ Neue Prüfung wurde erfolgreich hinzugefügt.');
        }

        return redirect()
            ->back()
            ->with('error', '❌ Neue Prüfung konnte nicht angelegt werden.');
    }
}
