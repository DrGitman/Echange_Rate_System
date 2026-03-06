@extends('layouts.auth')

@section('content')

<main class="w-full max-w-md bg-card-light dark:bg-card-dark p-8 md:p-12 rounded-[24px] shadow-xl shadow-blue-900/5 transition-all duration-300">
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-slate-100 mb-2">Welcome Back</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base">Enter your credentials to access your account.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 text-red-500 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-primary text-xl">mail</span>
            </div>
            <input type="email" name="email" id="login-email" value="{{ old('email') }}" placeholder="Enter your email" required
                class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/50 transition-all duration-200"/>
        </div>

        {{-- Password --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-primary text-xl">lock</span>
            </div>
            <input type="password" name="password" id="login-password" placeholder="Enter your password" required
                class="block w-full pl-12 pr-14 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/50 transition-all duration-200"/>
            {{-- Eye toggle --}}
            <button type="button"
                    class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors"
                    data-target="#login-password"
                    aria-label="Toggle password visibility">
                <span class="material-symbols-outlined text-xl select-none">visibility</span>
            </button>
        </div>

        <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-semibold py-4 rounded-xl shadow-lg shadow-blue-500/30 active:scale-[0.98] transition-all duration-200 mt-6">
            Sign In
        </button>
    </form>
</main>

<div class="mt-8 text-center">
    <p class="text-slate-500 dark:text-slate-400 text-sm">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Sign Up</a>
    </p>
</div>

@endsection