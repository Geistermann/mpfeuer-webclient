@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Barcode-Suche</h1>

    <form method="POST" action="{{ route('barcode.search.post') }}" class="mb-6">
        @csrf
        <div class="flex items-center space-x-2">
            <input type="text" name="barcode" value="{{ old('barcode', $barcode ?? '') }}" 
                   placeholder="Barcode eingeben"
                   class="border rounded px-3 py-2 flex-1" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Suchen
            </button>
        </div>
        @error('barcode')
            <p class="text-red-500 mt-2">{{ $message }}</p>
        @enderror
    </form>

    @isset($results)
        <h2 class="text-xl font-semibold mb-2">Ergebnisse für Barcode: {{ $barcode }}</h2>

        @if(count($results) === 0)
            <p class="text-gray-600">Keine Einträge gefunden.</p>
        @else
            @foreach($results as $modelClass => $item)
                <div class="mb-4 p-4 border rounded">
                    <h3 class="font-bold text-lg">{{ class_basename($modelClass) }}</h3>
                    <ul class="mt-2">
                        @foreach((array)$item as $key => $value)
                            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endif
    @endisset
</div>
@endsection
