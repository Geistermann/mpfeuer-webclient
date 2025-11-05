@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">NFC Suche</h1>

    <div class="mb-6">
        <p class="text-lg">
            🔍 Gesuchter Token: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $token }}</span>
        </p>
    </div>

    @if(empty($results))
        <p class="text-gray-600">Keine Datensätze gefunden.</p>
    @else
        @foreach($results as $result)
            <div class="mb-6 border rounded-lg shadow-sm p-4 bg-white">
                <h2 class="text-xl font-semibold mb-2">
                    {{ class_basename($result['model']) }}
                </h2>
                
                <table class="table-auto border-collapse w-full text-sm mb-4">
                    <tbody>
                        @foreach((array)$result['item'] as $key => $value)
                            <tr class="border-b">
                                <td class="font-medium py-1 pr-4 w-1/3">{{ $key }}</td>
                                <td class="py-1 break-all">{{ $value ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if(!empty($result['pruefungen']))
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">🔧 Prüfungen</h3>
                        <table class="table-auto border-collapse w-full text-sm">
                            <thead>
                                <tr class="border-b bg-gray-100">
                                    <th class="py-1 px-2 text-left">Datum</th>
                                    <th class="py-1 px-2 text-left">Art</th>
                                    <th class="py-1 px-2 text-left">Ergebnis</th>
                                    <th class="py-1 px-2 text-left">Bearbeiter</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['pruefungen'] as $p)
                                    <tr class="border-b">
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_DAT ?? '—' }}</td>
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_KURZ ?? '—' }}</td>
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_LANG ?? '—' }}</td>
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_HDZ ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>                            
                        </table>
                    </div>                    
                @endif

                @if(!empty($result['pruefungen']))
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">🔧 Prüfungen</h3>
                        <table class="table-auto border-collapse w-full text-sm">
                            <thead>
                                <tr class="border-b bg-gray-100">
                                    <th class="py-1 px-2 text-left">Datum</th>
                                    <th class="py-1 px-2 text-left">Art</th>
                                    <th class="py-1 px-2 text-left">Ergebnis</th>
                                    <th class="py-1 px-2 text-left">Bearbeiter</th>
                                    <th class="py-1 px-2 text-left">Aktion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['pruefungen'] as $p)
                                    @php
                                        $offen = ($p->GHR_PRUEF_HDZ == null || $p->GHR_PRUEF_OK == 0 || \Carbon\Carbon::parse($p->GHR_PRUEF_DAT)->isFuture());
                                    @endphp
                                    <tr class="border-b {{ $offen ? 'bg-red-50' : '' }}">
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_DAT ?? '—' }}</td>
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_KURZ ?? '—' }}</td>
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_LANG ?? '—' }}</td>
                                        <td class="py-1 px-2">{{ $p->GHR_PRUEF_HDZ ?? '—' }}</td>
                                        <td class="py-1 px-2">
                                            @if($offen)
                                                <form method="POST" action="{{ route('pruefung.done', $p->GHR_ID) }}" class="inline">
                                                    @csrf
                                                    <input type="text" name="handzeichen" placeholder="HDZ" class="border rounded p-1 text-xs w-16" required>
                                                    <button type="submit" class="bg-green-600 text-white text-xs px-2 py-1 rounded ml-1">
                                                        Erledigen
                                                    </button>
                                                </form>
                                            @else
                                                ✅
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
