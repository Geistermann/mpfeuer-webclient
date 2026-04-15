<!doctype html>
<html lang="de" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>MPFeuer-WebClient</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen flex flex-col">
  <nav class="bg-gray-800 shadow p-4">
    <div class="container mx-auto flex items-center justify-between">
      <div class="text-xl font-bold">
        <a href="/" class="hover:text-blue-400 transition-colors">MPFeuer-WebClient</a>
      </div>
      <div class="space-x-4">        
        <a href="/barcode" class="px-3 py-2 bg-blue-600 text-white rounded shadow hover:opacity-90">Barcode/NFC-Suche</a>
        <!-- weitere Menüpunkte für andere Modelle hier -->
      </div>
    </div>
  </nav>

  <main class="container mx-auto p-6 flex-grow">
    @yield('content')
  </main>

  {{-- Sticky Footer --}}
  <footer class="w-full py-6 text-center text-sm text-gray-400 border-t border-gray-700">
    <p>&copy; {{ date('Y') }} MPFeuer-WebClient. Alle Rechte vorbehalten.</p>
  </footer>

</body>

<style>
/* Tabelle auf mobilen Geräten als Cards darstellen */
@media (max-width: 640px) {
    table.responsive-table thead {
        display: none;
    }

    table.responsive-table tr {
        display: block;
        margin-bottom: 0.75rem;
        border: 1px solid #4b5563;
        border-radius: 0.5rem;
        padding: 0.5rem;
        background-color: #374151;
    }

    table.responsive-table td {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem;
        border: none !important;
        border-bottom: 1px solid #4b5563 !important;
    }

    table.responsive-table td:last-child {
        border-bottom: none !important;
    }

    table.responsive-table td::before {
        content: attr(data-label);
        font-weight: bold;
        color: #9ca3af;
        margin-right: 1rem;
    }
}
</style>

</html>