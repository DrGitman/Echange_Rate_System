@extends('layouts.auth')

@section('content')

<main class="w-full max-w-md bg-card-light dark:bg-card-dark p-8 md:p-12 rounded-[24px] shadow-xl shadow-blue-900/5 transition-all duration-300">
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-slate-100 mb-2">Create Account</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm md:text-base">Join us to start managing your currency exchanges.</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        {{-- Full Name --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-primary text-xl">person</span>
            </div>
            <input type="text" name="name" id="reg-name" value="{{ old('name') }}" placeholder="Enter your full name" required
                class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/50 transition-all duration-200"/>
        </div>

        {{-- Email --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-primary text-xl">mail</span>
            </div>
            <input type="email" name="email" id="reg-email" value="{{ old('email') }}" placeholder="Enter your email" required
                class="block w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/50 transition-all duration-200"/>
        </div>

        {{-- Password --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-primary text-xl">lock</span>
            </div>
            <input type="password" name="password" id="reg-password" placeholder="Enter your password" required
                class="block w-full pl-12 pr-14 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/50 transition-all duration-200"/>
            <button type="button"
                    class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors"
                    data-target="#reg-password"
                    aria-label="Toggle password visibility">
                <span class="material-symbols-outlined text-xl select-none">visibility</span>
            </button>
        </div>

        {{-- Confirm Password --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-primary text-xl">lock</span>
            </div>
            <input type="password" name="password_confirmation" id="reg-confirm" placeholder="Confirm your password" required
                class="block w-full pl-12 pr-14 py-4 bg-slate-50 dark:bg-slate-900/50 border-none rounded-xl text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary/50 transition-all duration-200"/>
            <button type="button"
                    class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary transition-colors"
                    data-target="#reg-confirm"
                    aria-label="Toggle confirm password visibility">
                <span class="material-symbols-outlined text-xl select-none">visibility</span>
            </button>
        </div>

        @if ($errors->any())
            <p class="text-red-500 text-sm">{{ $errors->first() }}</p>
        @endif

        <button type="submit"
            class="w-full bg-primary hover:bg-blue-600 text-white font-semibold py-4 rounded-xl shadow-lg shadow-blue-500/30 active:scale-[0.98] transition-all duration-200 mt-6">
            Sign Up
        </button>
    </form>
</main>

<div class="mt-8 text-center">
    <p class="text-slate-500 dark:text-slate-400 text-sm">
        Already have an account?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Log In</a>
    </p>
</div>

@endsection