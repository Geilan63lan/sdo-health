<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SDO Legazpi Health System</title>

        <link rel="icon" href="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png" type="image/png">
        <link rel="apple-touch-icon" href="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts & Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
                        },
                        animation: {
                            blob: 'blob 7s infinite',
                        },
                        keyframes: {
                            blob: {
                                '0%, 100%': { transform: 'translate(0, 0) scale(1)' },
                                '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                                '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            },
                        },
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="{{ asset('css/flux.css') }}">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@@livewire/flux@@1.x.x/dist/flux.min.js" defer></script>
    </head>
    <body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased">
        <div class="relative min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="relative z-10 border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md">
                <div class="mx-auto max-w-7xl px-6 py-6 lg:px-8 flex items-center justify-between">
                    <div class="flex lg:flex-1 items-center gap-3">
                        <img src="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png" alt="SDO Legazpi Logo" class="h-10 w-auto">
                        <span class="text-xl font-bold tracking-tight">School Division Office - Legazpi</span>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 hover:text-blue-600 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full bg-slate-900 dark:bg-white dark:text-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-slate-700 dark:hover:bg-slate-200 transition-all">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <main class="relative isolate grow">
                <!-- Background Decoration -->
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                    <div class="relative left-[calc(50%-11rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>

                <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:flex lg:items-center lg:gap-x-10 lg:px-8">
                    <div class="mx-auto max-w-2xl lg:mx-0 lg:flex-auto">
                        <div class="flex flex-col items-start gap-4">
                            <div class="relative rounded-full px-3 py-1 text-sm leading-6 text-slate-600 dark:text-slate-400 ring-1 ring-slate-900/10 dark:ring-slate-100/10 hover:ring-slate-900/20 dark:hover:ring-slate-100/20">
                                Official Health Management Portal.
                            </div>
                        </div>
                        <h1 class="mt-10 text-5xl font-bold tracking-tight sm:text-7xl">
                            SDO - Legazpi <span class="text-blue-600 dark:text-blue-400">Health System</span>
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">
                            A centralized platform for managing student health records, vaccinations, and health programs within the Division of Legazpi City. Ensuring every student's health and wellness is prioritized.
                        </p>
                        <div class="mt-10 flex items-center gap-x-6">
                            @auth
                                <a href="{{ route('filament.admin.pages.dashboard') }}" class="rounded-md bg-blue-600 px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all">
                                    Access System
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-md bg-blue-600 px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all">
                                    Get Started
                                </a>
                                <a href="#features" class="text-lg font-semibold leading-6 hover:text-blue-600 transition-colors">Learn more <span aria-hidden="true">→</span></a>
                            @endauth
                        </div>
                    </div>
                    <div class="mt-16 sm:mt-24 lg:mt-0 lg:flex-shrink-0 lg:flex-grow">
                        <div class="relative mx-auto w-full max-w-lg">
                            <div class="absolute top-0 -left-4 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob dark:bg-blue-900"></div>
                            <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000 dark:bg-purple-900"></div>
                            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000 dark:bg-pink-900"></div>
                            <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl p-8 border border-slate-200 dark:border-slate-800 opacity-80">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="size-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 dark:text-white">Health Dashboard</h3>
                                        <p class="text-sm text-slate-500">Real-time monitoring</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-3/4"></div>
                                    <div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-full"></div>
                                    <div class="h-4 bg-slate-100 dark:bg-slate-800 rounded w-5/6"></div>
                                    <div class="grid grid-cols-3 gap-4 mt-6">
                                        <div class="h-20 bg-blue-50 dark:bg-blue-950 rounded-lg"></div>
                                        <div class="h-20 bg-green-50 dark:bg-green-950 rounded-lg"></div>
                                        <div class="h-20 bg-purple-50 dark:bg-purple-950 rounded-lg"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div id="features" class="py-24 sm:py-32 bg-white dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-2xl lg:text-center">
                            <h2 class="text-base font-semibold leading-7 text-blue-600">Health Services</h2>
                            <p class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl text-slate-900 dark:text-white text-balance">
                                Everything you need to manage student health
                            </p>
                            <p class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-400">
                                A comprehensive toolset for school health coordinators and division personnel.
                            </p>
                        </div>
                        <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                            <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                                <div class="flex flex-col">
                                    <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-slate-900 dark:text-white">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-blue-600">
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </div>
                                        Health Records
                                    </dt>
                                    <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-slate-600 dark:text-slate-400">
                                        <p class="flex-auto">Maintain digital records of heights, weights, BMIs, and medical conditions for all students.</p>
                                    </dd>
                                </div>
                                <div class="flex flex-col">
                                    <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-slate-900 dark:text-white">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-blue-600">
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                            </svg>
                                        </div>
                                        Vaccination Tracking
                                    </dt>
                                    <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-slate-600 dark:text-slate-400">
                                        <p class="flex-auto">Track student immunization statuses and manage vaccination programs effectively.</p>
                                    </dd>
                                </div>
                                <div class="flex flex-col">
                                    <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-slate-900 dark:text-white">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-blue-600">
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0h.5m-1.5 0h-10.5m0 0h-.5" />
                                            </svg>
                                        </div>
                                        Health Programs
                                    </dt>
                                    <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-slate-600 dark:text-slate-400">
                                        <p class="flex-auto">Monitor student participation in various health and wellness programs across the division.</p>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Footer Decoration -->
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                    <div class="relative left-[calc(50%+3rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
            </main>

            <footer class="border-t border-slate-200 dark:border-slate-800 py-10 bg-white dark:bg-slate-900">
                <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center text-sm text-slate-500">
                    <p>&copy; {{ date('Y') }} SDO Legazpi - Health Management System. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
