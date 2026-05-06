@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    {{-- Benachrichtigungen --}}
    @if(session('success'))
        <div class="bg-green-600 border border-green-400 text-green-100 px-4 py-3 rounded mb-4 alert-auto-hide">
            ☑️ {{ session('success') }}
        </div>
    @endif

    @if(session('error') || isset($error))
        <div class="bg-red-600 border border-red-400 text-red-100 px-4 py-3 rounded mb-4">
            ⚠️ {{ session('error') ?? $error }}
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4">
        Suchergebnisse für <span class="font-mono bg-gray-700 px-2 py-1 rounded">{{ Str::upper($barcode) }}</span>
    </h1>

    @forelse($results as $modelClass => $item)
        @php                                
            $module = $modelClass::getModule();
            $friendlyName = $modelClass::getFriendlyName();
            $itemId = $item->{$module . '_ID'};

            // Archiv-Daten Logik
            $archivHdz = $item->{$module . '_ARCHIV_HDZ'};
            $archivDatRaw = $item->{$module . '_ARCHIV_DAT'};
            $hasArchiv = !empty($archivDatRaw) && !empty($archivHdz);
            $archivDat = $hasArchiv ? \Carbon\Carbon::parse($archivDatRaw)->format('d.m.Y') : null;

            // Zuteilung Logik
            $zuteilName = $item->{$module . '_ZUTEIL_NAME'};
            $zuteilDatRaw = $item->{$module . '_ZUTEIL_DAT'};
            $hasZuteilung = !empty($zuteilName) && !empty($zuteilDatRaw);

            // Prüfungen laden
            $pruefModel = \App\Models\Firebird\ModuleRegistry::findPruefModelForStamm($modelClass);
            $pruefungen = $pruefModel ? $pruefModel::getByStammIndex($itemId) : [];
        @endphp      
        
        {{-- Warnbanner bei Archivierung --}}
        @if($hasArchiv)
            <div class="text-2xl mb-4 bg-red-700 py-2 px-4 rounded shadow-lg">
                <strong>Achtung:</strong> Dieser Datensatz wurde am {{ $archivDat }} von {{ $archivHdz }} archiviert.
            </div>
        @endif            

        <div class="bg-gray-800 rounded-2xl shadow mb-6 border border-gray-600 overflow-hidden">                
            
            {{-- SEKTION: DETAILS (Standardmäßig geschlossen) --}}
            <div class="p-4 border-b border-gray-700 flex items-center justify-between cursor-pointer hover:bg-gray-700 transition" 
                 onclick="toggleSection('details-{{ $loop->index }}', 'icon-details-{{ $loop->index }}')">
                <h2 class="text-lg font-semibold text-gray-200">Details zu {{ $friendlyName }}</h2>
                <svg id="icon-details-{{ $loop->index }}" class="w-6 h-6 text-gray-500 transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>                

            <div id="details-{{ $loop->index }}" class="hidden p-4 bg-gray-900/50">
                <table class="table-auto w-full text-sm border-collapse border border-gray-700 text-gray-300">
                    @foreach((array) $item as $key => $value)
                        <tr>
                            <td class="border border-gray-700 px-3 py-1 font-semibold w-1/3 bg-gray-800 text-gray-400">{{ $key }}</td>
                            <td class="border border-gray-700 px-3 py-1">{{ $value ?? '—' }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

            {{-- SEKTION: ZUTEILUNG (Standardmäßig offen) --}}
            @if($hasZuteilung)
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleSection('zuteilung-{{ $loop->index }}', 'icon-zuteil-{{ $loop->index }}')">
                        <h3 class="text-lg font-semibold text-gray-200">Zuteilung</h3>
                        <svg id="icon-zuteil-{{ $loop->index }}" class="w-6 h-6 text-gray-500 transition-transform duration-300 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                                        
                    <div id="zuteilung-{{ $loop->index }}" class="block">
                        <table class="w-full border-collapse border border-gray-600 bg-gray-700 text-gray-300">
                            <thead>
                                <tr class="bg-gray-900 text-gray-400 text-left">
                                    <th class="border border-gray-600 p-2">Name</th>
                                    <th class="border border-gray-600 p-2">Datum</th>                                    
                                </tr>
                            </thead>
                            <tbody>                                
                                <tr>
                                    <td class="p-2 border border-gray-600 bg-green-700 font-bold">{{ $zuteilName }}</td>
                                    <td class="p-2 border border-gray-600 text-center">
                                        {{ \Carbon\Carbon::parse($zuteilDatRaw)->format('d.m.Y') }}
                                    </td>                                    
                                </tr>                                
                            </tbody>
                        </table>
                    </div>                    
                </div>
            @endif

            {{-- SEKTION: PRÜFUNGEN (Standardmäßig offen) --}}
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleSection('pruefungen-{{ $loop->index }}', 'icon-pruef-{{ $loop->index }}')">
                    <h3 class="text-lg font-semibold text-gray-200">Vorhandene Prüfungen</h3>
                    <svg id="icon-pruef-{{ $loop->index }}" class="w-6 h-6 text-gray-500 transition-transform duration-300 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                                    
                <div id="pruefungen-{{ $loop->index }}" class="block overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-600 bg-gray-700 text-gray-300">
                        <thead class="bg-gray-900 text-gray-400">
                            <tr>
                                <th class="border border-gray-600 p-2">Datum</th>
                                <th class="border border-gray-600 p-2 text-center">Name</th>
                                <th class="border border-gray-600 p-2 text-center">Handzeichen</th>
                                <th class="border border-gray-600 p-2 text-center">Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pruefungen as $p)
                                @php                                        
                                    $isDone = ($p->{$module . '_PRUEF_OK'} == 1) && !empty($p->{$module . '_PRUEF_HDZ'});
                                    $isFuture = strtotime($p->{$module . '_PRUEF_DAT'}) > time();
                                    $rowClass = $isDone ? 'bg-green-800/30' : ($isFuture ? 'bg-yellow-800/30' : 'bg-red-800/30');
                                @endphp
                                <tr class="{{ $rowClass }} hover:bg-gray-600/30 transition">
                                    <td class="p-2 border border-gray-600">{{ $p->{$module . '_PRUEF_DAT'} }}</td>
                                    <td class="p-2 border border-gray-600 text-center">{{ $p->{$module . '_PRUEF_LANG'} }}</td>
                                    <td class="p-2 border border-gray-600 text-center font-mono">{{ $p->{$module . '_PRUEF_HDZ'} ?? '—' }}</td>
                                    <td class="p-2 border border-gray-600 text-center">
                                        @if(!$isDone)
                                            <form action="{{ route('pruefung.done', $p->{$module . '_ID'}) }}" method="POST" class="flex items-center justify-center space-x-2" onsubmit="return confirm('Prüfung als erledigt markieren?');">
                                                @csrf
                                                <input type="hidden" name="pruefModel" value="{{ $pruefModel }}">
                                                <input type="text" name="handzeichen" required maxlength="2" pattern="[A-Za-z]{2}"
                                                       class="w-12 rounded bg-gray-800 border-gray-500 text-white text-center uppercase text-xs p-1"
                                                       placeholder="HDZ" oninput="this.value = this.value.toUpperCase()">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-2 py-1 rounded text-xs transition">OK</button>
                                            </form>
                                        @else
                                            <span class="text-green-400 font-bold">✅</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center p-4 text-gray-500 italic">Keine Prüfungen in der Historie</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>                    
            </div>

            {{-- SEKTION: NEUE PRÜFUNG (Standardmäßig offen) --}}
            @if($pruefModel)
                <div class="p-4 border-t border-gray-700 bg-gray-800/40">
                    <div class="flex items-center justify-between mb-2 cursor-pointer" onclick="toggleSection('newpruefung-{{ $loop->index }}', 'icon-new-{{ $loop->index }}')">
                        <h3 class="text-lg font-semibold text-gray-200">Neue Prüfung anlegen</h3>
                        <svg id="icon-new-{{ $loop->index }}" class="w-6 h-6 text-gray-500 transition-transform duration-300 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    
                    <div id="newpruefung-{{ $loop->index }}" class="block">
                        @php
                            $availableOptions = $pruefModel::getAvailablePruefungen();
                        @endphp

                        <form action="{{ route('pruefung.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end bg-gray-700 p-4 rounded-xl border border-gray-600" onsubmit="return confirm('Prüfung wirklich anlegen?');">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $itemId }}">
                            <input type="hidden" name="module" value="{{ $module }}">
                            <input type="hidden" name="pruefModel" value="{{ $pruefModel }}">

                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Prüfungsart</label>
                                <select name="par_pruef_lang" class="w-full rounded bg-gray-800 border-gray-600 text-white p-2 text-sm focus:border-blue-500 outline-none" required>
                                    @forelse($availableOptions as $option)
                                        <option value="{{ $option->PAR_PRUEF_LANG }}">
                                            {{ $option->PAR_PRUEF_LANG }} {{ $option->PAR_PRUEF_GRUPPE ? "({$option->PAR_PRUEF_GRUPPE})" : '' }}
                                        </option>
                                    @empty
                                        <option value="">Keine Vorlagen verfügbar</option>
                                    @endforelse
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Mein Handzeichen</label>
                                <input type="text" name="handzeichen" required maxlength="2" pattern="[A-Za-z]{2}"
                                       class="w-full rounded bg-gray-800 border-gray-600 text-white p-2 text-sm uppercase focus:border-blue-500 outline-none"
                                       placeholder="z.B. MS" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded transition shadow-md flex items-center justify-center">
                                <span class="mr-2">➕</span> Prüfung speichern
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @empty        
        <div class="text-gray-400 bg-gray-800 p-8 rounded-2xl border border-gray-700 text-center">
            <p class="text-xl">Keine Datensätze zu diesem Barcode gefunden.</p>
        </div>
    @endforelse
</div>

<script>
    /**
     * Schaltet Sichtbarkeit einer Sektion um und rotiert das zugehörige Icon.
     */
    function toggleSection(sectionId, iconId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(iconId);

        if (section) {
            section.classList.toggle('hidden');
            // Falls die Sektion nicht 'hidden' ist, muss sie 'block' oder einfach gar nichts sein
            if (!section.classList.contains('hidden')) {
                section.classList.add('block');
            } else {
                section.classList.remove('block');
            }
        }

        if (icon) {
            icon.classList.toggle('rotate-180');
        }
    }

    // Automatische Ausblendung von Erfolgsmeldungen
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-hide').forEach(el => {
            el.style.transition = 'all 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
</script>
@endsection