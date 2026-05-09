<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — CharityHub</title>
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
        <p class="text-slate-500 mt-2 text-sm">Create your free account and start making a difference</p>
    </div>

    {{-- Trust badges --}}
    <div class="flex justify-center gap-4 mb-6">
        @foreach(['🔒 Secure', '✅ Verified', '💙 Transparent'] as $badge)
            <span class="text-xs text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-full shadow-sm">
                {{ $badge }}
            </span>
        @endforeach
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-8">

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="Ahmed Mohamed"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="your@email.com"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                       placeholder="Min. 8 characters"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       placeholder="Repeat password"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">I am joining as</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2.5 border border-slate-300 rounded-xl p-3 cursor-pointer hover:border-blue-400 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" name="role" value="donor" checked class="text-blue-600">
                        <div>
                            <p class="text-sm font-semibold text-slate-700">💙 Donor</p>
                            <p class="text-xs text-slate-400">I want to donate</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-2.5 border border-slate-300 rounded-xl p-3 cursor-pointer hover:border-blue-400 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" name="role" value="volunteer" class="text-blue-600">
                        <div>
                            <p class="text-sm font-semibold text-slate-700">🤝 Volunteer</p>
                            <p class="text-xs text-slate-400">I want to help</p>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-colors shadow-sm text-sm">
                Create My Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline ml-1">
                    Sign in
                </a>
            </p>
        </div>
    </div>

    <p class="text-center text-xs text-slate-400 mt-6">
        © {{ date('Y') }} CharityHub. Your data is safe with us.
    </p>
</div>
</body>
</html>