@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">GHR Stammdaten</h1>
<table class="table-auto border-collapse w-full text-sm mb-4">
    <thead>
        <tr class="border-b bg-gray-100">
            <th class="py-1 px-2 text-left">ID</th>
            <th class="py-1 px-2 text-left">Eigentümer</th>
            <th class="py-1 px-2 text-left">Standort</th>
            <th class="py-1 px-2 text-left">Gruppe</th>
            <th class="py-1 px-2 text-left">Bezeichnung</th>
            <th class="py-1 px-2 text-left">Barcode</th>
            <!-- weitere Spalten -->
        </tr>
    </thead>
    <tbody>
        @foreach($daten as $datensatz)
            <tr class="border-b">
                <td class="py-1 px-2">{{ $datensatz->GHR_ID ?? '' }}</td>
                <td class="py-1 px-2">{{ $datensatz->GHR_EIGENTUM ?? '' }}</td>
                <td class="py-1 px-2">{{ $datensatz->GHR_STANDORT ?? '' }}</td>
                <td class="py-1 px-2">{{ $datensatz->GHR_GRUPPE ?? '' }}</td>
                <td class="py-1 px-2">{{ $datensatz->GHR_BEZEICHNUNG ?? '' }}</td>
                <td class="py-1 px-2">{{ $datensatz->GHR_BARCODE_NR ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection