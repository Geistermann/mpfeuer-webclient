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

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4">Suchergebnisse für <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $barcode }}</span></h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 mb-3 rounded">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 mb-3 rounded">{{ session('error') }}</div>
    @endif

    @if(empty($results))
        <p class="text-gray-600">Keine Datensätze gefunden.</p>
    @else
        @foreach($results as $model => $item)
            <div class="border rounded-lg p-4 mb-6 bg-white shadow">
                <h2 class="text-xl font-semibold mb-3">{{ class_basename($model) }}</h2>
                <table class="table-auto w-full text-sm">
                    @foreach((array)$item as $key => $value)
                        <tr class="border-b">
                            <td class="font-medium py-1 pr-4 w-1/3">{{ $key }}</td>
                            <td class="py-1">{{ $value ?? '—' }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    @endif

    <h2 class="text-xl font-bold mt-10 mb-3">🧾 Zugehörige Prüfungen</h2>

    @if(empty($allPruefungen))
        <p class="text-gray-600">Keine Prüfungen vorhanden.</p>
    @else        
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Datum</th>
                    <th class="p-2 border">Beschreibung</th>
                    <th class="p-2 border">HDZ</th>
                    <th class="p-2 border">Aktion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allPruefungen as $p)
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
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
