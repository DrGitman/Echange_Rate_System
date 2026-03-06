@extends('layouts.app')

@section('content')

<div class="w-full max-w-md px-6 py-6">

    {{-- Back Link --}}
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-primary transition-colors mb-6 group">
        <span class="material-symbols-outlined text-base transition-transform group-hover:-translate-x-0.5">arrow_back</span>
        Back to Dashboard
    </a>

    {{-- Calculator Card --}}
    <form action="{{ route('calculator.calculate') }}" method="POST" id="calc-form">
        @csrf
        {{-- Hidden fields for currency codes --}}
        <input type="hidden" name="from_currency" id="hidden-from" value="{{ $from_currency ?? 'USD' }}">
        <input type="hidden" name="to_currency" id="hidden-to" value="{{ $to_currency ?? 'EUR' }}">

        <div class="bg-white dark:bg-slate-800 rounded-[30px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] p-6 md:p-8 border border-white/50 dark:border-slate-700">

            <!-- Amount Section -->
            <div class="text-center mb-6">
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-1 uppercase tracking-wider">Amount to Convert</p>
                <div class="flex items-center justify-center">
                    <span class="text-4xl font-bold text-slate-900 dark:text-white mr-1">$</span>
                    <input class="text-5xl font-bold text-slate-900 dark:text-white bg-transparent border-none focus:ring-0 p-0 w-48 text-center placeholder-slate-200 dark:placeholder-slate-700"
                           type="number" name="amount" id="calc-amount" value="{{ old('amount', $amount ?? 100) }}" placeholder="0.00" step="any" required>
                </div>
            </div>

            <!-- Conversion Rows -->
            <div class="relative space-y-3 mb-6">

                <!-- FROM Currency -->
                <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700 flex items-center justify-between cursor-pointer currency-row from group hover:border-blue-200 dark:hover:border-blue-900 transition-all" data-type="from">
                    <div class="flex items-center gap-4">
                        <img id="from-flag" class="w-10 h-10 rounded-full object-cover shadow-sm border-2 border-white dark:border-slate-800" src="https://flagcdn.com/48x36/{{ strtolower(substr($from_currency ?? 'US', 0, 2)) }}.png">
                        <div>
                            <span id="from-name" class="block font-bold text-slate-900 dark:text-white text-lg">{{ $from_currency ?? 'USD' }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">From</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span id="from-amount-display" class="block font-bold text-slate-900 dark:text-white text-xl">{{ $amount ?? 0 }}</span>
                    </div>
                </div>

                <!-- Swap Button -->
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <button type="button" id="swap-btn" class="bg-white dark:bg-slate-700 w-12 h-12 flex items-center justify-center rounded-full shadow-lg border border-slate-100 dark:border-slate-600 hover:scale-110 active:scale-95 transition-all text-primary">
                        <span class="material-symbols-outlined text-2xl font-bold">swap_vert</span>
                    </button>
                </div>

                <!-- TO Currency -->
                <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700 flex items-center justify-between cursor-pointer currency-row to group hover:border-blue-200 dark:hover:border-blue-900 transition-all" data-type="to">
                    <div class="flex items-center gap-4">
                        <img id="to-flag" class="w-10 h-10 rounded-full object-cover shadow-sm border-2 border-white dark:border-slate-800" src="https://flagcdn.com/48x36/{{ strtolower(substr($to_currency ?? 'EU', 0, 2)) }}.png">
                        <div>
                            <span id="to-name" class="block font-bold text-slate-900 dark:text-white text-lg">{{ $to_currency ?? 'EUR' }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">To</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span id="to-amount" class="block font-bold text-blue-600 dark:text-blue-400 text-2xl">{{ $result ?? 0.00 }}</span>
                    </div>
                </div>

            </div>

            <!-- Convert Button -->
            <button type="submit"
                    class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-5 rounded-2xl shadow-xl shadow-blue-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-3 text-lg">
                Convert Now
                <span class="material-symbols-outlined text-2xl">trending_flat</span>
            </button>

            @if($exchangeRate > 0)
            <p class="text-center mt-6 text-sm text-slate-500 dark:text-slate-400">
                1 {{ $from_currency }} = {{ $exchangeRate }} {{ $to_currency }}
            </p>
            @endif

        </div>{{-- /card-inner --}}
    </form>

</div>{{-- /container --}}

{{-- Currency Selection Modal --}}
<div id="currency-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm modal-close"></div>
    <div class="relative w-full max-w-sm bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl p-6 border border-white/50 dark:border-slate-700">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Select Currency</h3>
            <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 modal-close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="relative mb-4">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input type="text" id="currency-search" placeholder="Search currencies..." 
                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all text-slate-800 dark:text-white">
        </div>
        <div id="currency-list" class="max-h-[300px] overflow-y-auto space-y-1 pr-1 custom-scrollbar">
            {{-- Injected by JS --}}
        </div>
    </div>
</div>

<script>
    window.countries = @json($countries);
</script>
<script src="{{ asset('js/calculator.js') }}"></script>

@endsection