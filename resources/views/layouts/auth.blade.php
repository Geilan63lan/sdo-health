<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} - SDO Health</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/flux.css') }}">
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 to-blue-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-zinc-900">SDO Health</h1>
                <p class="text-zinc-600 mt-2">Legazpi Health System</p>
            </div>

            <!-- Auth Card -->
            <div class="card">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 text-sm text-zinc-600">
                <p>&copy; 2026 Legazpi District Health System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@@livewire/flux@@1.x.x/dist/flux.min.js" defer></script>
    @livewireScripts
    @fluxScripts
</body>
</html>
