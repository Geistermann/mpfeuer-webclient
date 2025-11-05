<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>MPFeuer-WebClient</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
  <nav class="bg-white shadow p-4">
    <div class="container mx-auto flex items-center justify-between">
      <div class="text-xl font-bold">MPFeuer-WebClient</div>
      <div class="space-x-4">        
        <a href="/barcode" class="px-3 py-2 bg-blue-600 text-white rounded shadow hover:opacity-90">Barcode/NFC-Suche</a>
        <!-- weitere Menüpunkte für andere Modelle hier -->
      </div>
    </div>
  </nav>

  <main class="container mx-auto p-6">
    @yield('content')
  </main>
</body>
</html>
