<!DOCTYPE html>
<html lang="en" class="{{ auth()->check() && auth()->user()->dark_mode === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('weblogo.ico') }}">
    <title>@yield('title', 'CurrencyApp')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    <script src="{{ asset('js/darkmode.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/calculator.css') }}">

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#2563eb",
                        "background-light": "#f0f7ff",
                        "background-dark": "#0f172a",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-slate-900 min-h-screen transition-colors duration-300">

    @auth
    {{-- ✅ Global Premium Header --}}
    <header class="flex items-center justify-between px-8 py-5">
        {{-- Logo --}}
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo_text_blue.png') }}" 
                 alt="CurrencyApp" 
                 class="h-9 object-contain">
        </a>

        {{-- User info & Profile icon --}}
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-slate-600 dark:text-slate-400 hidden sm:block">
                {{ auth()->user()->name }}
            </span>
            <a href="{{ route('profile.show') }}" 
               class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center hover:ring-2 hover:ring-primary transition-all overflow-hidden"
               title="My Profile">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" 
                         alt="Profile" 
                         class="w-full h-full object-cover">
                @else
                    <span class="material-symbols-outlined text-slate-500 dark:text-slate-300 text-[22px]">person</span>
                @endif
            </a>
        </div>
    </header>
    @endauth

    <main class="flex flex-col items-center justify-center min-h-[calc(100vh-80px)] px-4 pb-12">
        @yield('content')
    </main>

    {{-- ✅ Polished Success Toast --}}
    @if(session('success'))
    <div id="success-toast"
         class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-700 text-slate-800 dark:text-slate-100 px-5 py-4 rounded-2xl shadow-xl shadow-black/10 max-w-sm"
         style="animation: toastSlideIn 0.4s cubic-bezier(0.16,1,0.3,1) both;">
        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
            <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-sm text-emerald-600 dark:text-emerald-400">Success</p>
            <p class="text-sm text-slate-600 dark:text-slate-300 mt-0.5">{{ session('success') }}</p>
        </div>
        <button onclick="dismissToast()" class="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
        <div id="toast-progress" class="absolute bottom-0 left-0 h-1 bg-emerald-400 rounded-b-2xl"
             style="width: 100%; animation: toastProgress 4s linear forwards;"></div>
    </div>

    <style>
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(120%) scale(0.9); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toastFadeOut {
            to { opacity: 0; transform: translateX(120%) scale(0.9); }
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to   { width: 0%; }
        }
    </style>

    <script>
        function dismissToast() {
            const t = document.getElementById('success-toast');
            if (t) {
                t.style.animation = 'toastFadeOut 0.35s ease forwards';
                setTimeout(() => t.remove(), 350);
            }
        }
        setTimeout(dismissToast, 4000);
    </script>
    @endif

</body>
</html>