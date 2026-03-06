@extends('layouts.app')

@section('content')

<div class="w-full max-w-2xl px-4 py-16 flex flex-col items-center justify-center">

    {{-- Tiny Back Link --}}
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-primary transition-colors mb-4 group self-start">
        <span class="material-symbols-outlined text-sm transition-transform group-hover:-translate-x-0.5">arrow_back</span>
        Return to Dashboard
    </a>

    {{-- Compact Profile Card --}}
    <div class="w-full bg-white dark:bg-slate-800 rounded-[24px] shadow-2xl p-8 border border-white/50 dark:border-slate-700 overflow-hidden">
        
        <div class="flex items-center gap-6 mb-6">
            {{-- Small Avatar Section --}}
            <div class="relative flex-shrink-0">
                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center border-2 border-slate-50 dark:border-slate-600 overflow-hidden">
                    @if(auth()->user()->avatar)
                        <img id="avatar-preview" src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <span id="avatar-placeholder" class="material-symbols-outlined text-slate-400 text-4xl">person</span>
                        <img id="avatar-preview" src="" class="w-full h-full object-cover hidden">
                    @endif
                </div>
                <form id="avatar-form" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden">
                    <button type="button" onclick="document.getElementById('avatar-input').click()"
                            class="absolute -bottom-1 -right-1 bg-blue-600 hover:bg-blue-700 text-white p-1 rounded-full border-2 border-white dark:border-slate-800 shadow-lg transition-all active:scale-90">
                        <span class="material-symbols-outlined text-[14px] block">camera_alt</span>
                    </button>
                </form>
            </div>
            
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ auth()->user()->name }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
            </div>
        </div>

        {{-- Form Split - Compact Grid --}}
        <form action="{{ route('profile.update') }}" method="POST" id="profile-form" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Account Details --}}
                <div class="space-y-3">
                    <h3 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Personal</h3>
                    <div>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required placeholder="Full Name"
                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-2 px-4 text-sm text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-blue-500 transition-all"/>
                    </div>
                    <div>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required placeholder="Email"
                               class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-2 px-4 text-sm text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-blue-500 transition-all"/>
                    </div>
                </div>

                {{-- Preferences & Security Col --}}
                <div class="space-y-3">
                    <h3 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Theme & Security</h3>
                    
                    {{-- Compact Mode Switcher --}}
                    <div class="flex items-center justify-between bg-slate-50 dark:bg-slate-900 p-2 rounded-xl border border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 ml-1">Appearance</span>
                        <div class="flex p-0.5 bg-slate-200 dark:bg-slate-700 rounded-lg">
                            <button type="button" onclick="setMode('light')" id="mode-light"
                                    class="px-2 py-1 rounded-md text-[10px] font-bold transition-all {{ (auth()->user()->dark_mode ?? 'light') === 'light' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500' }}">LIGHT</button>
                            <button type="button" onclick="setMode('dark')" id="mode-dark"
                                    class="px-2 py-1 rounded-md text-[10px] font-bold transition-all {{ (auth()->user()->dark_mode ?? 'light') === 'dark' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500' }}">DARK</button>
                        </div>
                        <input type="hidden" name="dark_mode" id="dark-mode-input" value="{{ auth()->user()->dark_mode ?? 'light' }}">
                    </div>

                    {{-- Tiny Password Trigger --}}
                    <button type="button" onclick="document.getElementById('change-password-modal').classList.remove('hidden')"
                            class="w-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 font-bold py-2 rounded-xl text-xs transition-all border border-blue-100 dark:border-blue-900/30">
                        Update Password
                    </button>
                </div>
            </div>

            @if($errors->any())
                <p class="text-red-500 text-[10px] font-bold bg-red-50 dark:bg-red-900/20 p-2 rounded-lg">{{ $errors->first() }}</p>
            @endif

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/10 active:scale-[0.98] transition-all text-sm mt-2">
                Save All Changes
            </button>
        </form>

        <form action="{{ route('logout') }}" method="POST" class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/50">
            @csrf
            <button type="submit" class="w-full text-center text-[10px] font-bold text-red-400 hover:text-red-500 transition-colors uppercase tracking-widest">
                Log Out Account
            </button>
        </form>
    </div>
</div>

{{-- Change Password Modal --}}
<div id="change-password-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="document.getElementById('change-password-modal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-xs bg-white dark:bg-slate-900 rounded-[30px] shadow-2xl p-6">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">New Password</h2>
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-3">
            @csrf
            <div class="space-y-1">
                <div class="relative">
                    <input type="password" name="current_password" id="cp-current" placeholder="Current Password" required
                           class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-blue-500 @error('current_password') ring-1 ring-red-500 @enderror"/>
                    <button type="button" class="toggle-password absolute right-3 top-3 text-slate-400 hover:text-primary transition-colors" data-target="#cp-current"><span class="material-symbols-outlined text-lg">visibility</span></button>
                </div>
                @error('current_password')
                    <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <div class="relative">
                    <input type="password" name="password" id="cp-new" placeholder="New Password" required
                           class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-blue-500 @error('password') ring-1 ring-red-500 @enderror"/>
                    <button type="button" class="toggle-password absolute right-3 top-3 text-slate-400 hover:text-primary transition-colors" data-target="#cp-new"><span class="material-symbols-outlined text-lg">visibility</span></button>
                </div>
                @error('password')
                    <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <div class="relative">
                    <input type="password" name="password_confirmation" id="cp-confirm" placeholder="Confirm Password" required
                           class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-xs text-slate-800 dark:text-slate-100 focus:ring-1 focus:ring-blue-500"/>
                    <button type="button" class="toggle-password absolute right-3 top-3 text-slate-400 hover:text-primary transition-colors" data-target="#cp-confirm"><span class="material-symbols-outlined text-lg">visibility</span></button>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-xs hover:bg-blue-700 transition-all mt-2">Update Security</button>
            <button type="button" onclick="document.getElementById('change-password-modal').classList.add('hidden')" class="w-full text-slate-400 font-bold py-2 text-[10px] uppercase tracking-widest">Cancel</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/togglepassword.js') }}"></script>

<script>
    function setMode(mode) {
        localStorage.setItem('theme', mode);
        document.getElementById('dark-mode-input').value = mode;
        if (mode === 'dark') document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
        
        const lb = document.getElementById('mode-light');
        const db = document.getElementById('mode-dark');
        if (lb && db) {
            lb.classList.toggle('bg-white', mode === 'light');
            lb.classList.toggle('text-blue-600', mode === 'light');
            lb.classList.toggle('shadow-sm', mode === 'light');
            lb.classList.toggle('text-slate-500', mode !== 'light');

            db.classList.toggle('bg-white', mode === 'dark');
            db.classList.toggle('text-blue-600', mode === 'dark');
            db.classList.toggle('shadow-sm', mode === 'dark');
            db.classList.toggle('text-slate-500', mode !== 'dark');
        }
    }

    // Sync UI with current theme on load
    (function syncThemeUI() {
        const activeTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        setMode(activeTheme);
    })();

    document.getElementById('avatar-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            if(preview){ preview.src = e.target.result; preview.classList.remove('hidden'); }
            if(placeholder){ placeholder.classList.add('hidden'); }
        };
        reader.readAsDataURL(file);
        document.getElementById('avatar-form').submit();
    });

    @if($errors->has('current_password') || $errors->has('password'))
        document.getElementById('change-password-modal').classList.remove('hidden');
    @endif
</script>

@endsection
