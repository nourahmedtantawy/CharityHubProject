<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — CharityHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-slate-100 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <a href="{{ route('campaigns.index') }}" class="inline-flex items-center gap-2.5">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <span class="text-2xl font-extrabold text-slate-800">Charity<span class="text-blue-600">Hub</span></span>
        </a>
        <p class="text-slate-500 mt-2 text-sm">Welcome back — sign in to your account</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-8">

        {{-- Session status --}}
        @if(session('status'))
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="text-sm font-semibold text-slate-700">Password</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <input type="password" name="password" required
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded text-blue-600 border-slate-300">
                <span class="text-sm text-slate-600">Keep me signed in</span>
            </label>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-colors shadow-sm text-sm">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline ml-1">
                    Create one free
                </a>
            </p>
        </div>
    </div>

    <p class="text-center text-xs text-slate-400 mt-6">
        © {{ date('Y') }} CharityHub. Transparent giving platform.
    </p>
</div>
</body>
</html>