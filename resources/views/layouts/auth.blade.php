<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} - SDO Health</title>

    <!-- CDN Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flux@2.4.0/dist/flux.min.css">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        /* Filament login override */
        .fi-login min-h-screen {
            min-height: 100vh;
        }
    </style>
    @livewireStyles
</head>
<body class="font-sans antialiased min-h-screen relative">
    <!-- Background -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900"></div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="/images/sdo-logo.png" alt="SDO Logo" class="h-24 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white">SDO Health</h1>
                <p class="text-blue-200 mt-1 font-medium">Legazpi City Health System</p>
            </div>

            <!-- Auth Card -->
            <div class="card rounded-2xl shadow-2xl p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="text-center mt-6 text-sm text-blue-200">
                <p>&copy; 2026 Legazpi District Health System. All rights reserved.</p>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
