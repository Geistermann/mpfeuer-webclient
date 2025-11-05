@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-8 px-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">🔍 Barcode-Suche</h1>

    {{-- Suchformular --}}
    <form method="GET" action="{{ route('barcode.search') }}" class="mb-6 flex items-center gap-2">
        <input type="text" name="token" value="{{ request('token') }}" placeholder="Barcode oder NFC-Token eingeben"
               class="border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
            Suchen
        </button>
    </form>

    {{-- Suchergebnisse --}}
    @if(isset($results) && count($results) > 0)
        @dd($results)
        @foreach($results as $modelName => $entries)
            @if(count($entries) > 0)
                <div class="mb-10 bg-white shadow rounded-xl p-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">
                        Ergebnisse aus <span class="text-blue-600">{{ strtoupper($modelName) }}</span>
                    </h2>

                    @foreach($entries as $entry)
                        <div class="border border-gray-200 rounded-lg p-3 mb-4">
                            {{-- Basisdaten --}}
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="font-semibold text-gray-700">{{ $entry->GHR_BEZEICHNUNG ?? $entry->KLD_NAME ?? '—' }}</p>
                                    <p class="text-sm text-gray-500">{{ $entry->GHR_ZUTEIL_NAME ?? '' }}</p>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span class="font-medium">Barcode:</span> {{ $entry->GHR_BARCODE_NR ?? $entry->BARCODE ?? '—' }}
                                </div>
                            </div>

                            {{-- Prüfungen --}}
                            @if(!empty($entry->pruefungen))
                                <div class="mt-3">
                                    <h3 class="text-md font-semibold mb-2">📋 Prüfungen</h3>
                                    <table class="table-auto border-collapse w-full text-sm">
                                        <thead>
                                            <tr class="border-b bg-gray-100 text-left">
                                                <th class="py-1 px-2">Datum</th>
                                                <th class="py-1 px-2">Art</th>
                                                <th class="py-1 px-2">OK</th>
                                                <th class="py-1 px-2">Bearbeiter</th>
                                                <th class="py-1 px-2">Aktion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entry->pruefungen as $p)
                                                @php
                                                    $offen = ($p->GHR_PRUEF_HDZ == null || $p->GHR_PRUEF_OK == 0 || \Carbon\Carbon::parse($p->GHR_PRUEF_DAT)->isFuture());
                                                @endphp
                                                <tr class="border-b {{ $offen ? 'bg-red-50' : 'bg-green-50' }}">
                                                    <td class="py-1 px-2">{{ $p->GHR_PRUEF_DAT ?? '—' }}</td>
                                                    <td class="py-1 px-2">{{ $p->GHR_PRUEF_ART ?? '—' }}</td>
                                                    <td class="py-1 px-2 text-center">{{ $p->GHR_PRUEF_OK == 1 ? '✅' : '❌' }}</td>
                                                    <td class="py-1 px-2">{{ $p->GHR_PRUEF_HDZ ?? '—' }}</td>
                                                    <td class="py-1 px-2">
                                                        @if($offen)
                                                            <form method="POST" action="{{ route('pruefung.done', $p->GHR_PRUEF_ID) }}" class="inline-flex items-center">
                                                                @csrf
                                                                <input type="text" name="handzeichen" placeholder="HDZ"
                                                                    class="border rounded p-1 text-xs w-16" required>
                                                                <button type="submit"
                                                                    class="bg-green-600 text-white text-xs px-2 py-1 rounded ml-1 hover:bg-green-700">
                                                                    Erledigen
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-green-600 font-semibold">Erledigt</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic mt-2">Keine Prüfungen gefunden.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    @else
        @if(request('token'))
            <div class="text-gray-600 text-center py-8 border rounded-lg bg-gray-50">
                🚫 Keine Ergebnisse für den Barcode <strong>{{ request('token') }}</strong> gefunden.
            </div>
        @endif
    @endif
</div>
@endsection
