@extends('layouts.app')

@section('content')

{{-- Welcome Title --}}
<div class="text-center mb-14">
    <h2 class="text-[34px] font-bold text-slate-800 dark:text-slate-100 mb-2">
        @if(session('_welcomeType') === 'new')
            Welcome, {{ auth()->user()->name }}!
        @else
            Welcome back, {{ auth()->user()->name }}
        @endif
    </h2>
    <p class="text-slate-500 dark:text-slate-400 text-lg">
        Choose a tool to start managing your finances.
    </p>
</div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 w-full max-w-4xl">

            {{-- Calculator Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-[30px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] p-12 text-center transition-transform duration-200 hover:-translate-y-1">
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/30 rounded-[20px] flex items-center justify-center mx-auto mb-8">
                    <span class="material-symbols-outlined text-[40px] text-blue-500">calculate</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Exchange Rate Calculator</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Instantly convert between dozens of global currencies with real-time accuracy and mid-market rates.
                </p>
                <a href="{{ route('calculator.index') }}"
                   class="block w-full bg-primary hover:bg-blue-600 text-white font-semibold py-4 rounded-xl text-center transition-colors duration-200">
                    Open Calculator
                </a>
            </div>

            {{-- Graph Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-[30px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] p-12 text-center transition-transform duration-200 hover:-translate-y-1">
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/30 rounded-[20px] flex items-center justify-center mx-auto mb-8">
                    <span class="material-symbols-outlined text-[40px] text-blue-500">show_chart</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-4">Market Trends Graph</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Analyze historical performance and visualize market fluctuations with our interactive trend charting tools.
                </p>
                <a href="{{ route('graph') }}"
                   class="block w-full bg-primary hover:bg-blue-600 text-white font-semibold py-4 rounded-xl text-center transition-colors duration-200">
                    Open Trends
                </a>
        </div>
    </div>
@endsection