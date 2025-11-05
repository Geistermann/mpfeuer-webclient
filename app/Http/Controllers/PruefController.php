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
}
