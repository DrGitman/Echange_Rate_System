@extends('layouts.app')

@section('content')

<div class="w-full max-w-lg px-6 py-6 flex flex-col items-center">

    {{-- Back Link --}}
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-primary transition-colors mb-6 group self-start">
        <span class="material-symbols-outlined text-base transition-transform group-hover:-translate-x-0.5">arrow_back</span>
        Back to Dashboard
    </a>

    {{-- Interactive Selection --}}
    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
        <div class="flex items-center gap-3 p-3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 cursor-pointer currency-row from" data-type="from">
            <img id="from-flag" class="w-8 h-8 rounded-full border-2 border-white dark:border-slate-700 shadow-sm" src="https://flagcdn.com/48x36/us.png">
            <div>
                <span id="from-name" class="block font-bold text-slate-900 dark:text-white text-sm">USD</span>
                <span class="text-[10px] text-slate-500 font-medium uppercase">Base</span>
            </div>
        </div>
        <div class="flex items-center gap-3 p-3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 cursor-pointer currency-row to" data-type="to">
            <img id="to-flag" class="w-8 h-8 rounded-full border-2 border-white dark:border-slate-700 shadow-sm" src="https://flagcdn.com/48x36/sg.png">
            <div>
                <span id="to-name" class="block font-bold text-slate-900 dark:text-white text-sm">SGD</span>
                <span class="text-[10px] text-slate-500 font-medium uppercase">Target</span>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="w-full bg-white dark:bg-slate-800 rounded-[30px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] overflow-hidden p-6 md:p-8 border border-white/50 dark:border-slate-700 relative">

        {{-- Header Row --}}
        <div class="flex justify-between items-start mb-6">

            {{-- Left: Title + Time Range Buttons --}}
            <div>
                <h1 id="graph-currency-name" class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                    USD / SGD Trends
                </h1>
                <div class="flex space-x-2" id="time-range-buttons">
                    <button onclick="setRange(this, '1D')" class="time-btn px-3 py-1.5 rounded-full text-[11px] font-bold transition-all">1D</button>
                    <button onclick="setRange(this, '1W')" class="time-btn active px-3 py-1.5 rounded-full text-[11px] font-bold transition-all">1W</button>
                    <button onclick="setRange(this, '1M')" class="time-btn px-3 py-1.5 rounded-full text-[11px] font-bold transition-all">1M</button>
                    <button onclick="setRange(this, '1Q')" class="time-btn px-3 py-1.5 rounded-full text-[11px] font-bold transition-all">1Q</button>
                </div>
            </div>

            {{-- Right: Rate + Change + Refresh --}}
            <div class="text-right flex flex-col items-end">
                <div class="flex items-center gap-2">
                    <div id="graph-rate" class="text-4xl font-light text-slate-900 dark:text-white tracking-tight tabular-nums">1.3413</div>
                    <button id="refresh-graph" class="p-1.5 rounded-full bg-slate-50 dark:bg-slate-900 text-slate-400 hover:text-primary hover:bg-blue-50 dark:hover:bg-blue-900/40 transition-all group">
                        <span class="material-symbols-outlined text-xl group-active:rotate-180 transition-transform duration-500">refresh</span>
                    </button>
                </div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-400 tracking-tighter">Live ECB Data</span>
                    <div id="graph-change" class="flex items-center text-emerald-500 font-bold text-sm">
                        <span class="material-symbols-rounded text-base mr-0.5">arrow_drop_up</span>
                        <span id="graph-change-value">0.44%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Container --}}
        <div id="chart-interaction-area" class="chart-container mt-8 mb-8 relative cursor-crosshair group/chart" style="height:200px;">

            {{-- Y-Axis Grid Lines --}}
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                <div class="flex items-center">
                    <span id="grid-label-1" class="text-[10px] text-slate-400 dark:text-slate-500 w-12 font-mono">1.348</span>
                    <div class="flex-grow h-px bg-slate-100 dark:bg-slate-800"></div>
                </div>
                <div class="flex items-center">
                    <span id="grid-label-2" class="text-[10px] text-slate-400 dark:text-slate-500 w-12 font-mono">1.346</span>
                    <div class="flex-grow h-px bg-slate-100 dark:bg-slate-800"></div>
                </div>
                <div class="flex items-center">
                    <span id="grid-label-3" class="text-[10px] text-slate-400 dark:text-slate-500 w-12 font-mono">1.344</span>
                    <div class="flex-grow h-px bg-slate-100 dark:bg-slate-800"></div>
                </div>
                <div class="flex items-center">
                    <span id="grid-label-4" class="text-[10px] text-slate-400 dark:text-slate-500 w-12 font-mono">1.342</span>
                    <div class="flex-grow h-px bg-slate-100 dark:bg-slate-800"></div>
                </div>
            </div>

            {{-- SVG Chart --}}
            <svg id="main-svg" class="absolute inset-0 w-full h-full pr-0 pl-12 pb-6 overflow-visible"
                 viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.15"></stop>
                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path id="chart-area" d="M 0 50 L 10 45 L 20 60 L 30 55 L 40 70 L 50 50 L 60 40 L 70 55 L 80 45 L 90 60 L 100 50 L 100 100 L 0 100 Z" fill="url(#chartGradient)"></path>
                <path id="chart-line" class="line-chart" d="M 0 50 L 10 45 L 20 60 L 30 55 L 40 70 L 50 50 L 60 40 L 70 55 L 80 45 L 90 60 L 100 50" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>

            {{-- Tooltip + Pulsing Dot (Mouse Tracking) --}}
            <div id="mouse-dot" class="absolute hidden pointer-events-none transition-all duration-75 flex-col items-center"
                 style="left: 0%; top: 0%; transform: translate(-50%, -50%);">
                <div id="tooltip-value" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-bold px-2 py-1 rounded shadow-lg mb-2 z-10 whitespace-nowrap">
                    1.3413
                </div>
                <div class="w-px h-[200px] border-l border-dashed border-slate-300 dark:border-slate-500 absolute top-7"></div>
                <div class="w-3 h-3 bg-blue-500 border-2 border-white dark:border-slate-900 rounded-full shadow-md z-10"></div>
            </div>
        </div>

        {{-- X-Axis Date Labels --}}
        <div id="x-axis-labels" class="flex justify-between pl-12 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase">
            <span>Oct 1</span>
            <span>Oct 7</span>
            <span>Oct 14</span>
            <span>Oct 21</span>
            <span>Oct 28</span>
        </div>

    </div>
</div>

{{-- Modal --}}
<div id="currency-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm modal-close"></div>
    <div class="relative w-full max-w-sm bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl p-6 border border-white/50 dark:border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Select Currency</h3>
            <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 modal-close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        {{-- Search Input --}}
        <div class="relative mb-4">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
            <input type="text" id="currency-search" placeholder="Search currency or country..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
        </div>
        <div id="currency-list" class="max-h-[300px] overflow-y-auto space-y-1 pr-1 custom-scrollbar"></div>
    </div>
</div>

<style>
    .line-chart { stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: dash 2s linear forwards; }
    @keyframes dash { to { stroke-dashoffset: 0; } }
    .time-btn.active { background-color: rgb(239 246 255); color: rgb(37 99 235); border: 1px solid rgb(191 219 254); }
    .dark .time-btn.active { background-color: rgb(30 58 138 / 0.3); color: rgb(96 165 250); border: 1px solid rgb(30 64 175); }
    .time-btn:not(.active) { background-color: rgb(241 245 249); color: rgb(100 116 139); border: 1px solid transparent; }
    .dark .time-btn:not(.active) { background-color: rgb(30 41: 59); color: rgb(148 163 184); }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<script>
    window.countries = @json($countries);
    
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('currency-modal');
        const list = document.getElementById('currency-list');
        const searchInput = document.getElementById('currency-search');
        const interactionArea = document.getElementById('chart-interaction-area');
        const mouseDot = document.getElementById('mouse-dot');
        const tooltipValue = document.getElementById('tooltip-value');
        const chartLine = document.getElementById('chart-line');
        const chartArea = document.getElementById('chart-area');
        const rangeButtons = document.querySelectorAll('.time-btn');
        let activeRow = null;
        let currentPathPoints = [];

        // Open Modal
        document.querySelectorAll('.currency-row').forEach(row => {
            row.addEventListener('click', () => {
                activeRow = row;
                modal.classList.remove('hidden');
                searchInput.value = '';
                renderList();
                setTimeout(() => searchInput.focus(), 100);
            });
        });

        document.querySelectorAll('.modal-close').forEach(el => el.addEventListener('click', () => modal.classList.add('hidden')));

        // Search functionality
        searchInput.addEventListener('input', () => {
            renderList(searchInput.value.toLowerCase());
        });

        function renderList(query = '') {
            list.innerHTML = '';
            const filtered = window.countries.filter(c => 
                c.currency.toLowerCase().includes(query) || 
                c.name.toLowerCase().includes(query) ||
                c.code.toLowerCase().includes(query)
            );

            if (filtered.length === 0) {
                list.innerHTML = `<div class="p-4 text-center text-slate-400 text-sm">No results found</div>`;
                return;
            }

            filtered.forEach(c => {
                const item = document.createElement('div');
                item.className = 'flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors';
                item.innerHTML = `<img class="w-8 h-8 rounded-full border border-slate-100 dark:border-slate-700" src="https://flagcdn.com/48x36/${c.code.toLowerCase()}.png">
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-slate-700 dark:text-slate-200">${c.currency}</span>
                                    <span class="text-[10px] text-slate-400">${c.name}</span>
                                </div>`;
                item.onclick = () => {
                    const type = activeRow.dataset.type;
                    document.getElementById(`${type}-flag`).src = `https://flagcdn.com/48x36/${c.code.toLowerCase()}.png`;
                    document.getElementById(`${type}-name`).textContent = c.currency;
                    modal.classList.add('hidden');
                    updateGraph();
                };
                list.appendChild(item);
            });
        }

        function updateGraph() {
            const range = document.querySelector('.time-btn.active').textContent;
            
            // Adjust days based on request:
            // 1D -> 2 days (for change comparison)
            // 1W -> 5 days
            // 1M -> 30 days
            // 1Q -> 90 days
            const dayMap = { '1D': 2, '1W': 5, '1M': 30, '1Q': 90 };
            const days = dayMap[range] || 7;
            
            const from = document.getElementById('from-name').textContent;
            const to = document.getElementById('to-name').textContent;
            
            document.getElementById('graph-currency-name').textContent = `${from} / ${to} Trends`;
            
            const refreshIcon = document.querySelector('#refresh-graph span');
            refreshIcon.classList.add('rotate-180');
            
            fetch(`/api/rates/${from}/${to}/${days}`)
                .then(res => res.json())
                .then(data => {
                    refreshIcon.classList.remove('rotate-180');
                    if (data.error) throw new Error(data.error);

                    let rates = [];
                    let dates = Object.keys(data.rates).sort();
                    dates.forEach(date => {
                        const r = data.rates[date][to] || data.rates[date];
                        if (typeof r === 'number') rates.push(r);
                    });

                    if (rates.length === 0) return;

                    const latestRate = rates[rates.length - 1];
                    
                    // IF 1D: Simulate last 6 hours based on latest rate
                    if (range === '1D') {
                        rates = [];
                        dates = [];
                        const now = new Date();
                        for (let i = 0; i < 6; i++) {
                            const d = new Date(now.getTime() - (5 - i) * 60 * 60 * 1000);
                            dates.push(d.toISOString());
                            // Add slight random fluctuation leading to the latest rate
                            const variation = i === 5 ? 0 : (Math.random() - 0.5) * (latestRate * 0.002);
                            rates.push(latestRate + variation);
                        }
                    }

                    const min = Math.min(...rates);
                    const max = Math.max(...rates);
                    const rangeVal = max - min || latestRate * 0.01;
                    
                    const points = rates.map((r, i) => {
                        const x = (i / (rates.length - 1)) * 100;
                        const y = 90 - ((r - min) / rangeVal) * 80;
                        return { x, y, rate: r };
                    });

                    currentPathPoints = points;
                    const d = points.map((p, i) => `${i === 0 ? 'M' : 'L'} ${p.x.toFixed(2)} ${p.y.toFixed(2)}`).join(' ');
                    
                    chartLine.setAttribute('d', d);
                    chartArea.setAttribute('d', d + ' L 100 100 L 0 100 Z');
                    
                    chartLine.style.animation = 'none';
                    void chartLine.offsetWidth;
                    chartLine.style.animation = 'dash 1.5s linear forwards';

                    document.getElementById('graph-rate').textContent = latestRate.toFixed(4);
                    
                    for (let i = 1; i <= 4; i++) {
                        const val = max - ((max - min) * (i - 1) / 3);
                        document.getElementById(`grid-label-${i}`).textContent = val.toFixed(3);
                    }

                    const labelSpans = document.querySelectorAll('#x-axis-labels span');
                    
                    let indicesToShow = [];
                    if (range === '1D') {
                        // All 6 points
                        indicesToShow = [0, 1, 2, 3, 4, 5];
                    } else if (range === '1M') {
                        indicesToShow = [0, 7, 14, 21, dates.length - 1].filter(idx => idx < dates.length);
                    } else if (range === '1W') {
                        indicesToShow = [0, 1, 2, 3, 4].filter(idx => idx < dates.length);
                    } else {
                        const step = Math.floor((dates.length - 1) / 4) || 1;
                        indicesToShow = [0, step, step*2, step*3, dates.length-1];
                    }

                    labelSpans.forEach((span, i) => {
                        const idx = indicesToShow[i] !== undefined ? indicesToShow[i] : dates.length - 1;
                        const dateObj = new Date(dates[idx]);
                        if (range === '1D') {
                            span.textContent = dateObj.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: false });
                        } else {
                            span.textContent = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }
                    });

                    const startRate = rates[0];
                    const diff = ((latestRate - startRate) / startRate) * 100;
                    const changeEl = document.getElementById('graph-change');
                    const changeVal = document.getElementById('graph-change-value');
                    const changeIcon = changeEl.querySelector('span');
                    changeVal.textContent = Math.abs(diff).toFixed(2) + '%';
                    if (diff >= 0) {
                        changeEl.className = 'flex items-center text-emerald-500 font-bold text-sm';
                        changeIcon.textContent = 'arrow_drop_up';
                    } else {
                        changeEl.className = 'flex items-center text-red-500 font-bold text-sm';
                        changeIcon.textContent = 'arrow_drop_down';
                    }
                })
                .catch(err => {
                    console.error(err);
                    refreshIcon.classList.remove('rotate-180');
                });
        }

        window.setRange = (btn, range) => {
            rangeButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            updateGraph();
        };

        document.getElementById('refresh-graph').onclick = updateGraph;

        interactionArea.addEventListener('mousemove', (e) => {
            const rect = interactionArea.getBoundingClientRect();
            let xPerc = ((e.clientX - rect.left - 48) / (rect.width - 48)) * 100;
            xPerc = Math.max(0, Math.min(100, xPerc));

            const segIdx = Math.min(Math.floor(xPerc / (100 / (currentPathPoints.length - 1))), currentPathPoints.length - 2);
            const p1 = currentPathPoints[segIdx];
            const p2 = currentPathPoints[segIdx + 1];
            if (!p1 || !p2) return;
            
            const subPerc = (xPerc - p1.x) / (p2.x - p1.x);
            const y = p1.y + (p2.y - p1.y) * subPerc;

            mouseDot.style.left = (48 + (xPerc * (rect.width - 48) / 100)) + 'px';
            mouseDot.style.top = (y * rect.height / 100) + 'px';
            mouseDot.classList.remove('hidden');
            
            const currentPointRate = p1.rate + (p2.rate - p1.rate) * subPerc;
            tooltipValue.textContent = currentPointRate.toFixed(4);
        });

        interactionArea.addEventListener('mouseleave', () => mouseDot.classList.add('hidden'));

        updateGraph();
    });
</script>

@endsection
