@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    {{-- Erfolg / Fehler --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Ergebnis-Karten --}}
    <h1 class="text-2xl font-bold mb-4">Suchergebnisse für <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $barcode }}</span></h1>

    @if(empty($results))
        <p class="text-gray-600">Keine Datensätze gefunden.</p>
    @else
        @foreach($results as $modelClass => $item)
            @php
                $friendlyName = $modelClass::getFriendlyName();
                $pruefungen = \App\Models\Firebird\GhrPruef::forGhr($item->GHR_ID);
            @endphp

            <div class="bg-white rounded-2xl shadow mb-6 border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between cursor-pointer" 
                    onclick="toggleSection('details-{{ $loop->index }}')">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ $friendlyName }}
                    </h2>
                    <span class="text-gray-500 text-sm">(Klicken zum Ein-/Ausklappen)</span>
                </div>

                {{-- DETAILS – standardmäßig eingeklappt --}}
                <div id="details-{{ $loop->index }}" class="hidden p-4">
                    <table class="table-auto w-full text-sm border-collapse border border-gray-300">
                        @foreach((array) $item as $key => $value)
                            <tr>
                                <td class="border px-3 py-1 font-semibold w-1/3 bg-gray-50">{{ $key }}</td>
                                <td class="border px-3 py-1">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{-- PRÜFUNGEN – standardmäßig geöffnet --}}
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleSection('pruefungen-{{ $loop->index }}')">
                        <h3 class="text-lg font-semibold text-gray-800">Vorhandene Prüfungen</h3>
                        <span class="text-gray-500 text-sm">(Ein-/Ausklappen)</span>
                    </div>
                                        
                    <div id="pruefungen-{{ $loop->index }}" class="block">
                        <table class="table-auto w-full border-collapse border border-gray-300 mb-4">
                            <thead class="bg-gray-100">
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
                                        $isDone = $p->GHR_PRUEF_OK == 1 && !empty($p->GHR_PRUEF_HDZ);
                                        $isFuture = strtotime($p->GHR_PRUEF_DAT) > time();
                                    @endphp
                                    <tr class="{{ $isDone ? 'bg-green-50' : ($isFuture ? 'bg-yellow-50' : 'bg-red-50') }}">
                                        <td class="p-2 border">{{ $p->GHR_PRUEF_DAT }}</td>
                                        <td class="p-2 border text-center">{{ $p->GHR_PRUEF_LANG }}</td>
                                        <td class="p-2 border text-center">{{ $p->GHR_PRUEF_HDZ ?? '—' }}</td>
                                        <td class="p-2 border text-center">
                                            @if(!$isDone)                                
                                                <form action="{{ route('pruefung.done', $p->GHR_ID) }}" 
                                                    method="POST" 
                                                    class="inline-flex space-x-2"
                                                    onsubmit="return confirm('Bist du sicher, dass diese Prüfung als erledigt markiert werden soll?');">
                                                    @csrf
                                                    <input type="text" name="handzeichen" class="border rounded px-2 py-1 text-xs w-20" placeholder="HDZ" required>
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
                                        <td colspan="4" class="text-center border p-3 text-gray-500">Keine Prüfungen vorhanden</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>                    
                </div>

                {{-- NEUE PRÜFUNG --}}
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleSection('newpruefung-{{ $loop->index }}')">
                        <h3 class="text-lg font-semibold text-gray-800">Neue Prüfung anlegen</h3>
                        <span class="text-gray-500 text-sm">(Ein-/Ausklappen)</span>
                    </div>

                    <div id="newpruefung-{{ $loop->index }}" class="block">
                        <form action="{{ route('pruefung.store') }}" method="POST" class="bg-gray-50 p-3 rounded">
                            @csrf
                            <input type="hidden" name="ghr_index" value="{{ $item->GHR_ID }}">

                            <div class="mb-3">
                                <label for="par_pruef_id" class="block font-semibold mb-1">Prüfung auswählen:</label>
                                <select name="par_pruef_id" id="par_pruef_id" class="border rounded w-full p-2" required>
                                    @foreach(\App\Models\Firebird\GhrPruef::getAvailablePruefungen() as $option)
                                        <option value="{{ $option->PAR_ID }}">{{ $option->PAR_PRUEF_LANG }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="handzeichen" class="block font-semibold mb-1">Handzeichen:</label>
                                <input type="text" name="handzeichen" id="handzeichen" class="border rounded w-full p-2" placeholder="z. B. MS" required>
                            </div>

                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                ➕ Prüfung hinzufügen
                            </button>
                        </form>
                    </div>
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
        document.querySelectorAll('.bg-green-100, .bg-red-100')
            .forEach(el => el.style.display = 'none');
    }, 3000);
</script>
@endsection
