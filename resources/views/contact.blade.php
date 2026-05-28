<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Contact Us - SDO Legazpi Health System</title>
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
                        <a href="{{ route('about') }}" class="text-sm font-semibold leading-6 hover:text-blue-600 transition-colors">About</a>
                        <a href="{{ route('contact') }}" class="text-sm font-semibold leading-6 text-blue-600 transition-colors">Contact</a>

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
                @if (session('success'))
                    <div class="mx-auto max-w-7xl px-6 pt-6 lg:px-8">
                        <div class="rounded-lg bg-green-50 dark:bg-green-900/50 p-4 text-sm text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                    <div class="mx-auto max-w-3xl">
                        <div class="flex items-center gap-4 mb-12">
                            <div class="size-14 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">Contact Us</h1>
                                <p class="mt-1 text-lg text-slate-600 dark:text-slate-400">Get in touch with SDO Legazpi</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Send us a message</h2>
                                <p class="mt-2 text-slate-600 dark:text-slate-400">
                                    Have a question or concern? Fill out the form and we'll respond as soon as possible.
                                </p>

                                <form method="POST" action="{{ route('contact.store') }}" class="mt-8 space-y-6">
                                    @csrf

                                    <div>
                                        <label for="name" class="block text-sm font-medium text-slate-900 dark:text-slate-100">Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                            class="mt-2 block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Your full name">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-slate-900 dark:text-slate-100">Email</label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            class="mt-2 block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="your@email.com">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="subject" class="block text-sm font-medium text-slate-900 dark:text-slate-100">Subject</label>
                                        <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                                            class="mt-2 block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="What is this about?">
                                        @error('subject')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="message" class="block text-sm font-medium text-slate-900 dark:text-slate-100">Message</label>
                                        <textarea name="message" id="message" rows="5"
                                            class="mt-2 block w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Your message...">{{ old('message') }}</textarea>
                                        @error('message')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="rounded-md bg-blue-800 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-blue-500 transition-all">
                                        Send Message
                                    </button>
                                </form>
                            </div>

                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Contact Information</h2>
                                <p class="mt-2 text-slate-600 dark:text-slate-400">
                                    Reach us through any of the following channels.
                                </p>

                                <div class="mt-8 space-y-6">
                                    <div class="flex items-start gap-4">
                                        <div class="size-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white">Address</h3>
                                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                                SDO Legazpi Rd, Bgy. 42 - Rawis (Bgy. 65)<br>
                                                Legazpi City, Albay, Philippines
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="size-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white">Phone</h3>
                                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">(052) 742-8227</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="size-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-slate-900 dark:text-white">Email</h3>
                                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">health@sdolegazpicity.com</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Developer Showcase Section -->
                        @php
                            $developers = App\Models\Developer::where('is_active', true)
                                ->orderBy('sort_order')
                                ->get();
                        @endphp

                        <div class="mt-24 pt-12 border-t border-slate-200 dark:border-slate-800">
                            <div class="text-center mb-12">
                                <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Built By</h2>
                                <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">
                                    The talented people behind this system
                                </p>
                            </div>

                            <!-- Team Lead -->
                            <div class="mb-12 flex justify-center">
                                <div class="w-full max-w-sm rounded-2xl border-2 border-blue-500 dark:border-blue-400 bg-gradient-to-br from-blue-50 to-white dark:from-blue-950 dark:to-slate-900 p-8 text-center shadow-lg">
                                    <div class="mx-auto size-24 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white text-3xl font-bold shadow-md">
                                        A
                                    </div>
                                    <div class="mt-2">
                                        <span class="inline-block rounded-full bg-blue-600/10 dark:bg-blue-400/10 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wider">Team Lead</span>
                                    </div>
                                    <h3 class="mt-4 text-xl font-bold text-slate-900 dark:text-white">Aida Santos Noora</h3>
                                    <p class="mt-1 text-sm text-slate-500">UI/UX Designer & Content Manager</p>
                                    <div class="mt-4 space-y-1 text-sm text-slate-600 dark:text-slate-400">
                                        <p class="flex items-center justify-center gap-2">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            ict.legazpi@deped.gov.ph
                                        </p>
                                        <p class="flex items-center justify-center gap-2">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            +63 987 654 3210
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Team Members Divider -->
                            <div class="relative mb-10">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-slate-300 dark:border-slate-700"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="bg-white dark:bg-slate-950 px-4 text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Team Members</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                                @forelse ($developers as $developer)
                                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 text-center hover:shadow-lg transition-shadow">
                                        @if ($developer->photo)
                                            <img src="{{ Storage::disk('public')->url($developer->photo) }}" alt="{{ $developer->name }}"
                                                class="mx-auto size-20 rounded-full object-cover border-2 border-blue-100 dark:border-blue-900">
                                        @else
                                            <div class="mx-auto size-20 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl font-bold">
                                                {{ substr($developer->name, 0, 1) }}
                                            </div>
                                        @endif

                                        <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">{{ $developer->name }}</h3>

                                        @if ($developer->title)
                                            <p class="mt-1 text-sm text-slate-500">{{ $developer->title }}</p>
                                        @endif

                                        @if ($developer->bio)
                                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">{{ $developer->bio }}</p>
                                        @endif

                                        @if ($developer->quote)
                                            <blockquote class="mt-3 text-sm italic text-slate-500 dark:text-slate-400 border-l-2 border-blue-500 pl-3 text-left">
                                                "{{ $developer->quote }}"
                                            </blockquote>
                                        @endif

                                        <div class="mt-4 flex justify-center gap-3">
                                            @if ($developer->github_url)
                                                <a href="{{ $developer->github_url }}" target="_blank" rel="noopener noreferrer"
                                                    class="rounded-full p-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                                                    aria-label="GitHub">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if ($developer->linkedin_url)
                                                <a href="{{ $developer->linkedin_url }}" target="_blank" rel="noopener noreferrer"
                                                    class="rounded-full p-2 text-slate-600 dark:text-slate-400 hover:text-blue-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                                                    aria-label="LinkedIn">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if ($developer->portfolio_url)
                                                <a href="{{ $developer->portfolio_url }}" target="_blank" rel="noopener noreferrer"
                                                    class="rounded-full p-2 text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                                                    aria-label="Portfolio">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-12">
                                        <div class="mx-auto size-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Meet the Team</h3>
                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                                            Developer profiles will appear here once added by the administrator.
                                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Go to Admin → System Management → Developers to add profiles.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
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
                                <li>(052) 742-8227</li>
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
