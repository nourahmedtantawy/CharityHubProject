<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CharityHub') — CharityHub</title>
    <meta name="description" content="@yield('meta_description', 'Transparent fundraising for causes that matter.')">
    <meta property="og:title"       content="@yield('og_title', 'CharityHub')">
    <meta property="og:description" content="@yield('og_description', 'Transparent fundraising.')">
    <meta property="og:image"       content="@yield('og_image', '')">
    <meta property="og:type"        content="website">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon"       href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"    href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    {{-- Top nav --}}
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('campaigns.index') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800">Charity<span class="text-blue-600">Hub</span></span>
                </a>

                {{-- Nav links --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('campaigns.index') }}"
                       class="text-slate-600 hover:text-blue-600 transition-colors {{ request()->routeIs('campaigns.*') ? 'text-blue-600' : '' }}">
                        Campaigns
                    </a>
                    <a href="{{ route('campaigns.index') }}?category=health"
                       class="text-slate-600 hover:text-blue-600 transition-colors">Health</a>
                    <a href="{{ route('campaigns.index') }}?category=education"
                       class="text-slate-600 hover:text-blue-600 transition-colors">Education</a>
                    <a href="{{ route('campaigns.index') }}?category=orphans"
                       class="text-slate-600 hover:text-blue-600 transition-colors">Orphans</a>
                </div>

                {{-- Auth --}}
                <div class="flex items-center gap-3 text-sm">
                    @auth
                        <span class="hidden md:block text-slate-500">
                            Hello, {{ auth()->user()->name }}
                        </span>
                        @if(auth()->user()->isAdmin())
                            <a href="/admin"
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Admin Panel
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-slate-500 hover:text-red-500 transition-colors font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-slate-600 hover:text-blue-600 font-medium transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-20 bg-slate-900 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-lg">Charity<span class="text-blue-400">Hub</span></span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-xs">
                        A transparent platform connecting donors with verified charitable campaigns across Egypt.
                    </p>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3 text-sm">Categories</p>
                    <ul class="space-y-2 text-sm">
                        @foreach(['health','education','orphans','shelter','food','environment','disaster'] as $cat)
                            <li>
                                <a href="{{ route('campaigns.index') }}?category={{ $cat }}"
                                   class="hover:text-blue-400 transition-colors capitalize">
                                    {{ $cat }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3 text-sm">Platform</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('campaigns.index') }}" class="hover:text-blue-400 transition-colors">All Campaigns</a></li>
                        <li><a href="{{ route('register') }}"        class="hover:text-blue-400 transition-colors">Create Account</a></li>
                        <li><a href="{{ route('login') }}"           class="hover:text-blue-400 transition-colors">Sign In</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-6 text-center text-xs">
                © {{ date('Y') }} CharityHub. All rights reserved. Built with Laravel 11.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>