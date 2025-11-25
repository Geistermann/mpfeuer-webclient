@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    {{-- Erfolg / Fehler --}}
    @if(session('success'))
        <div class="bg-green-600 border border-green-400 text-green-100 px-4 py-3 rounded mb-4">
            ☑️ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 border border-red-400 text-red-100 px-4 py-3 rounded mb-4">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if(isset($error))
        <div class="bg-red-700 text-white p-3 rounded mb-4">
            ⚠️ {{ $error }}
        </div>
    @endif


    {{-- Ergebnis-Karten --}}
    <h1 class="text-2xl font-bold mb-4">Suchergebnisse für <span class="font-mono bg-gray-700 px-2 py-1 rounded">{{ Str::upper($barcode) }}</span></h1>

    @if(empty($results))
        <p class="text-gray-400">Keine Datensätze gefunden.</p>
    @else        
        @foreach($results as $modelClass => $item)
            @php                                
                $friendlyName = $modelClass::getFriendlyName();
                $module = $modelClass::getModule();
                $pruefModel = \App\Models\Firebird\ModuleRegistry::findPruefModelForStamm($modelClass);
                if ($pruefModel) {
                    $pruefungen = $pruefModel::getByStammIndex($item->{$module . '_ID'});
                } else {
                    $pruefungen = [];
                }
            @endphp            

            <div class="bg-gray-800 rounded-2xl shadow mb-6 border border-gray-600">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between cursor-pointer" 
                    onclick="toggleSection('details-{{ $loop->index }}')">
                    <h2 class="text-lg font-semibold text-gray-200">
                        Details zu {{ $friendlyName }}
                    </h2>
                    <span class="text-gray-500 text-sm">(Klicken zum Ein-/Ausklappen)</span>
                </div>                

                {{-- DETAILS – standardmäßig eingeklappt --}}
                <div id="details-{{ $loop->index }}" class="hidden p-4">
                    <table class="table-auto w-full text-sm border-collapse border border-gray-300 bg-gray-700 text-gray-300">
                        @foreach((array) $item as $key => $value)
                            <tr>
                                <td class="border px-3 py-1 font-semibold w-1/3 bg-gray-900 text-gray-500">{{ $key }}</td>
                                <td class="border px-3 py-1">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{-- PRÜFUNGEN – standardmäßig geöffnet --}}
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleSection('pruefungen-{{ $loop->index }}')">
                        <h3 class="text-lg font-semibold text-gray-200">Vorhandene Prüfungen</h3>
                        <span class="text-gray-500 text-sm">(Klicken zum Ein-/Ausklappen)</span>
                    </div>
                                        
                    <div id="pruefungen-{{ $loop->index }}" class="block">
                        <table class="responsive-table table-auto w-full border-collapse border border-gray-600 bg-gray-700 text-gray-300 mb-4">
                            <thead class="bg-gray-900 text-gray-400">
                                <tr>
                                    <th class="border p-2">Datum</th>
                                    <th class="border p-2">Name</th>
                                    <th class="border p-2">Handzeichen</th>
                                    <th class="border p-2">Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pruefungen as $p)
                                    @php                                        
                                        $colId   = $module . '_ID';
                                        $colOk   = $module . '_PRUEF_OK';
                                        $colHdz  = $module . '_PRUEF_HDZ';
                                        $colDat  = $module . '_PRUEF_DAT';
                                        $colLang = $module . '_PRUEF_LANG';

                                        $isDone   = $p->$colOk == 1 && !empty($p->$colHdz);
                                        $isFuture = strtotime($p->$colDat) > time();
                                    @endphp

                                    <tr class="{{ $isDone ? 'bg-green-700' : ($isFuture ? 'bg-yellow-700' : 'bg-red-700') }}">
                                        <td class="p-2 border" data-label="Datum">
                                            {{ $p->$colDat }}
                                        </td>

                                        <td class="p-2 border text-center" data-label="Name">
                                            {{ $p->$colLang }}
                                        </td>

                                        <td class="p-2 border text-center" data-label="Handzeichen">
                                            {{ $p->$colHdz ?? '—' }}
                                        </td>

                                        <td class="p-2 border text-center" data-label="Aktion">
                                            @if(!$isDone)
                                                <form action="{{ route('pruefung.done', $p->$colId) }}"
                                                    method="POST"
                                                    class="inline-flex space-x-2"
                                                    onsubmit="return confirm('Bist du sicher, dass diese Prüfung als erledigt markiert werden soll?');">
                                                    @csrf
                                                    <input type="hidden" name="pruefModel" value="{{ $pruefModel }}">

                                                    <input type="text"
                                                        name="handzeichen"
                                                        class="border rounded px-2 py-1 text-xs w-20 text-gray-900 uppercase"
                                                        placeholder="HDZ"
                                                        required maxlength="2"
                                                        pattern="[A-Za-z]{2}"
                                                        oninput="this.value = this.value.toUpperCase().slice(0,2)">

                                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                                        Erledigen
                                                    </button>
                                                </form>
                                            @else
                                                ✅ Erledigt
                                            @endif
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center border p-3 text-gray-500">
                                            Keine Prüfungen vorhanden
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>                    
                </div>

                {{-- NEUE PRÜFUNG --}}
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleSection('newpruefung-{{ $loop->index }}')">
                        <h3 class="text-lg font-semibold text-gray-200">Neue Prüfung anlegen</h3>
                        <span class="text-gray-500 text-sm">(Klicken zum Ein-/Ausklappen)</span>
                    </div>
                    @if(empty($pruefModel))
                        <p class="text-gray-600">Fehler: Kein Prüfmodel gefunden.</p>
                    @else
                        <div id="newpruefung-{{ $loop->index }}" class="block">
                            <form action="{{ route('pruefung.store') }}"
                                method="POST"
                                class="bg-gray-700 p-3 rounded"
                                onsubmit="return confirm('Bist du sicher, dass du diese Prüfung anlegen willst?');">

                                @csrf

                                @php
                                    // dynamische Spaltennamen
                                    $colId = $module . '_ID';

                                    // prüfen, ob Prüfungen vorhanden sind
                                    $availableOptions = $pruefModel::getAvailablePruefungen();                                    
                                    $hasOptions = !empty($availableOptions);
                                @endphp

                                <input type="hidden" name="item_id" value="{{ $item->$colId }}">
                                <input type="hidden" name="module" value="{{ $module }}">
                                <input type="hidden" name="pruefModel" value="{{ $pruefModel }}">

                                <div class="mb-3">
                                    <label for="par_pruef_id" class="block font-semibold mb-1">Prüfung auswählen:</label>
                                    <select name="par_pruef_lang"
                                            id="par_pruef_id"
                                            class="border rounded w-full p-2 text-gray-900"
                                            {{ $hasOptions ? '' : 'disabled' }}
                                            required>
                                        @forelse($availableOptions as $option)
                                            <option value="{{ $option->PAR_PRUEF_LANG }}">
                                                {{ $option->PAR_PRUEF_LANG }}
                                                @if(!empty($option->PAR_PRUEF_GRUPPE))
                                                    für {{ $option->PAR_PRUEF_GRUPPE }}
                                                @endif
                                            </option>
                                        @empty
                                            <option value="">(Keine Prüfung vorhanden)</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div class="mb-3" {{ $hasOptions ? '' : 'hidden' }}>
                                    <label for="handzeichen" class="block font-semibold mb-1">Handzeichen:</label>
                                    <input type="text"
                                        name="handzeichen"
                                        id="handzeichen"
                                        class="border rounded w-full p-2 text-gray-900 uppercase"
                                        placeholder="z. B. MS"
                                        required
                                        maxlength="2"
                                        pattern="[A-Za-z]{2}"
                                        oninput="this.value = this.value.toUpperCase().slice(0,2)">
                                </div>

                                <button type="submit"
                                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-500 disabled:cursor-not-allowed"
                                        {{ $hasOptions ? '' : 'hidden' }}>
                                    ➕ Prüfung hinzufügen
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
    function toggleSection(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('hidden');
            el.classList.toggle('block');
        }
    }

    // Erfolgsmeldungen nach 3 Sekunden ausblenden
    setTimeout(() => {
        document.querySelectorAll('.bg-green-600, .bg-red-600')
            .forEach(el => el.style.display = 'none');
    }, 3000);
</script>
@endsection
