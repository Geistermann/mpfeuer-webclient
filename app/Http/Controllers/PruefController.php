<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pruef\BasePruef;
use App\Models\Firebird\ModuleRegistry;
use Illuminate\Support\Facades\Session;

class PruefController extends Controller
{
    /**
     * Prüfung als erledigt markieren
     */
    public function markAsDone(Request $request, $pruef_id)
    {                        
        $handzeichen = $request->input('handzeichen');

        if (empty($handzeichen)) {
            return redirect()
                ->back()
                ->with('error', 'Kein Handzeichen angegeben.');
        }

        $pruefModel = $request->input('pruefModel');

        if (empty($pruefModel)) {
            return redirect()
                ->back()
                ->with('error', 'Kein Pruef Model gefunden.');
        }        

        $success = $pruefModel::markAsDone($pruef_id, $handzeichen);

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
     * Neue Prüfung speichern
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|string',
            'module' => 'required|string',
            'pruefModel' => 'required|string',            
            'par_pruef_lang' => 'required|string',            
            'handzeichen' => 'required|string|max:7',
        ]);

        $pruefModel = $request->input('pruefModel');
        if (!$pruefModel) {
            return back()->with('error', 'Konnte Prüftabelle für das Modul nicht finden.');
        }

        $success = $pruefModel::createNewPruefung(
            $request->input('module'),
            $request->input('pruefModel'),
            $request->input('item_id'),            
            $request->input('par_pruef_lang'),            
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
