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
<div class="container mx-auto max-w-lg p-6">
    <h1 class="text-2xl font-bold mb-4">🔍 Barcode / NFC-Suche</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 mb-3 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('barcode.search') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium mb-1">Barcode / Token:</label>
            <input type="text" name="barcode" class="w-full border rounded p-2" placeholder="z.B. 123456789ABC" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            Suchen
        </button>
    </form>
</div>
@endsection
