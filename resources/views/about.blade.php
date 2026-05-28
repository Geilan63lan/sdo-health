<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About Us - SDO Legazpi Health System</title>
        <link rel="icon" href="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="/css/flux.css">
        <script>
            function initDarkMode() {
                if (localStorage.getItem('dark-mode') === 'true' ||
                    (!localStorage.getItem('dark-mode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            }
            initDarkMode();

            function toggleDarkMode() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('dark-mode', isDark);
                const sunIcon = document.getElementById('sun-icon');
                const moonIcon = document.getElementById('moon-icon');
                if (sunIcon && moonIcon) {
                    sunIcon.classList.toggle('hidden', !isDark);
                    moonIcon.classList.toggle('hidden', isDark);
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const isDark = document.documentElement.classList.contains('dark');
                const sunIcon = document.getElementById('sun-icon');
                const moonIcon = document.getElementById('moon-icon');
                if (sunIcon && moonIcon) {
                    sunIcon.classList.toggle('hidden', !isDark);
                    moonIcon.classList.toggle('hidden', isDark);
                }
            });
        </script>
    </head>
    <body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased">
        <div class="relative min-h-screen flex flex-col">
            <nav class="sticky top-0 z-10 border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md">
                <div class="mx-auto max-w-7xl px-6 py-6 lg:px-8 flex items-center justify-between">
                    <div class="flex lg:flex-1 items-center gap-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-3">
                            <img src="https://sdolegazpicity.com/wp-content/uploads/2025/12/cropped-LOGO-sdo-leg-1-1.png" alt="SDO Legazpi Logo" class="h-10 w-auto">
                            <span class="text-xl font-bold tracking-tight">School Division Office - Legazpi</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-6">
                        <a href="{{ route('about') }}" class="text-sm font-semibold leading-6 text-blue-600 transition-colors">About</a>
                        <a href="{{ route('contact') }}" class="text-sm font-semibold leading-6 hover:text-blue-600 transition-colors">Contact</a>

                        <button onclick="toggleDarkMode()" class="rounded-full p-2 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" aria-label="Toggle dark mode">
                            <svg id="sun-icon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg id="moon-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        @auth
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 transition-all">
                                Dashboard
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

            <main class="relative isolate grow">
                <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                    <div class="mx-auto max-w-3xl">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="size-14 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">About Us</h1>
                                <p class="mt-1 text-lg text-slate-600 dark:text-slate-400">SDO Legazpi Health Management System</p>
                            </div>
                        </div>

                        <div class="prose prose-slate dark:prose-invert max-w-none">
                            <h2 class="text-2xl font-bold mt-10 text-slate-900 dark:text-white">Our Mission</h2>
                            <p class="mt-4 text-lg leading-8 text-slate-600 dark:text-slate-400">
                                To provide a centralized, efficient, and accessible platform for managing student health records, 
                                vaccinations, and wellness programs across all schools in the Division of Legazpi City.
                            </p>

                            <h2 class="text-2xl font-bold mt-10 text-slate-900 dark:text-white">Our Vision</h2>
                            <p class="mt-4 text-lg leading-8 text-slate-600 dark:text-slate-400">
                                A division where every student's health and wellness are prioritized through data-driven 
                                decision-making, streamlined health monitoring, and collaborative health program management.
                            </p>

                            <h2 class="text-2xl font-bold mt-10 text-slate-900 dark:text-white">What We Do</h2>
                            <p class="mt-4 text-lg leading-8 text-slate-600 dark:text-slate-400">
                                The SDO Legazpi Health Management System empowers school health coordinators, clinic personnel, 
                                and division administrators to track student health metrics, monitor immunization compliance, 
                                and manage health programs — all in one integrated platform.
                            </p>

                            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-3">
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-900">
                                    <div class="text-3xl font-bold text-blue-600">50+</div>
                                    <div class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">Schools</div>
                                    <div class="mt-1 text-sm text-slate-500">Across Legazpi City</div>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-900">
                                    <div class="text-3xl font-bold text-blue-600">30,000+</div>
                                    <div class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">Students</div>
                                    <div class="mt-1 text-sm text-slate-500">Health records managed</div>
                                </div>
                                <div class="rounded-xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-900">
                                    <div class="text-3xl font-bold text-blue-600">100%</div>
                                    <div class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">Digital</div>
                                    <div class="mt-1 text-sm text-slate-500">Paperless health tracking</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex items-center gap-x-6">
                            <a href="{{ route('contact') }}" class="rounded-md bg-blue-800 px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-blue-500 transition-all">
                                Contact Us
                            </a>
                            <a href="{{ route('home') }}" class="text-lg font-semibold leading-6 hover:text-blue-600 transition-colors">
                                Back to Home <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="border-t border-slate-200 dark:border-slate-800 py-12 bg-white dark:bg-slate-900">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">About</h3>
                            <ul class="mt-4 space-y-3">
                                <li><a href="{{ route('about') }}" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">About Us</a></li>
                                <li><a href="{{ route('home') }}#features" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Features</a></li>
                                <li><a href="{{ route('home') }}#about" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Our Mission</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Contact</h3>
                            <ul class="mt-4 space-y-3 text-sm text-slate-500">
                                <li>SDO Legazpi Rd, Bgy. 42 - Rawis (Bgy. 65), Legazpi City, Albay</li>
                                <li>(052) 123-4567</li>
                                <li>health@sdolegazpicity.com</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Legal</h3>
                            <ul class="mt-4 space-y-3">
                                <li><a href="#" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Privacy Policy</a></li>
                                <li><a href="#" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Terms of Service</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-10 border-t border-slate-200 dark:border-slate-800 pt-8 text-center text-sm text-slate-500">
                        <p>&copy; {{ date('Y') }} SDO Legazpi - Health Management System. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
