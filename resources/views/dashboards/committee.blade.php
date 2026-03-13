@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Control Center</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">Competition overview and live standings.</p>
        </div>
        <div class="flex items-center space-x-6">

            <div class="flex items-center space-x-3 bg-white dark:bg-slate-900 p-2 px-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
                <span class="flex h-3 w-3 relative ml-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </span>
                <span class="text-xs font-black text-slate-900 dark:text-white pr-2 uppercase tracking-tight">
                    {{ $activeEvent ? $activeEvent->name : 'No Active Event' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Stat Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="relative group overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Contestants</h3>
                <div class="flex items-baseline mt-2">
                    <p id="stat-contestants" class="text-5xl font-extrabold text-slate-900 dark:text-white">{{ $stats['contestants'] }}</p>
                    <span class="ml-2 text-xs font-bold text-indigo-600 dark:text-indigo-400">REGISTERED</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>

        <div class="relative group overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Active Criteria</h3>
                <div class="flex items-baseline mt-2">
                    <p id="stat-criteria" class="text-5xl font-extrabold text-slate-900 dark:text-white">{{ $stats['criteria'] }}</p>
                    <span class="ml-2 text-xs font-bold text-emerald-600 dark:text-emerald-400">CONFIGURED</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>

        <div class="relative group overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl group-hover:bg-amber-500/10 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/30 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Judges Panel</h3>
                <div class="flex items-baseline mt-2">
                    <p id="stat-judges" class="text-5xl font-extrabold text-slate-900 dark:text-white">{{ $stats['judges'] }}</p>
                    <span class="ml-2 text-xs font-bold text-amber-600 dark:text-amber-400">ACTIVE</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
    </div>

    {{-- Ranking Insights --}}
    @if(count($rankings) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        {{-- Bar Chart --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Top Performance</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Live scoring analytics.</p>
                </div>
                <div class="flex items-center space-x-2 bg-indigo-50 dark:bg-indigo-950/30 px-4 py-2 rounded-xl">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                    <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Live Sync</span>
                </div>
            </div>
            <div class="p-10">
                <div class="relative h-[350px]">
                    <canvas id="rankingChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Leaderboard Table --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Real-time Leaderboard</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Current standings and voting progress.</p>
            </div>
            <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-4" id="leaderboard-body">
                @foreach($rankings as $item)
                <div class="flex items-center justify-between p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-2xl hover:bg-slate-100/50 dark:hover:bg-slate-800/60 transition-colors group border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg @if($item->rank == 1) bg-amber-100 text-amber-600 @elseif($item->rank == 2) bg-slate-100 text-slate-600 @else bg-indigo-50 text-indigo-600 @endif font-black text-xs">
                            #{{ $item->rank }}
                        </span>
                        <div class="ml-4 flex items-center">
                            <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm border-2 border-white dark:border-slate-800 group-hover:scale-110 transition-transform duration-300">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-xs font-black text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{{ $item->name }}</div>
                                <div class="flex items-center mt-1">
                                    <div class="w-16 h-1 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500" style="width: {{ $item->progress }}%"></div>
                                    </div>
                                    <span class="ml-2 text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ round($item->progress) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ number_format($item->average_score, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="p-6 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800 flex justify-center">
                <a href="{{ route('reports.index') }}" class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 uppercase tracking-widest flex items-center transition-colors">
                    View Full Reports
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Judging Status --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Judging Status</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Individual panelist scoring progress.</p>
            </div>
            <a href="{{ route('judges.index') }}" class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">Manage Judges</a>
        </div>
        <div class="p-8">
            <div id="judging-status-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($judgeProgress as $judge)
                <div class="p-6 bg-slate-50/50 dark:bg-slate-800/30 rounded-3xl border border-slate-100/50 dark:border-slate-800/50 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xs">
                                {{ strtoupper(substr($judge->name, 0, 2)) }}
                            </div>
                            <div class="ml-3">
                                <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $judge->name }}</h4>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $judge->is_done ? 'COMPLETED' : 'IN PROGRESS' }}</span>
                            </div>
                        </div>
                        @if($judge->is_done)
                        <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-end">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $judge->scored }}/{{ $judge->total }} ITEMS</span>
                            <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ round($judge->progress) }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full {{ $judge->is_done ? 'bg-emerald-500' : 'bg-indigo-500' }} transition-all duration-1000" style="width: {{ $judge->progress }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Start --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Quick Actions</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Common administrative tasks.</p>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('contestants.create') }}" class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-emerald-500/20 hover:bg-white dark:hover:bg-slate-800 transition-all duration-300 group">
                <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl shadow-sm flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-900 dark:text-white">Add Contestant</h4>
                <p class="text-xs text-slate-500 mt-1">Register new participants.</p>
            </a>

            <a href="{{ route('criteria.create') }}" class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-amber-500/20 hover:bg-white dark:hover:bg-slate-800 transition-all duration-300 group">
                <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl shadow-sm flex items-center justify-center text-amber-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
                <h4 class="font-bold text-slate-900 dark:text-white">Setup Criteria</h4>
                <p class="text-xs text-slate-500 mt-1">Configure scoring rules.</p>
            </a>

            <a href="{{ route('reports.index') }}" class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-purple-500/20 hover:bg-white dark:hover:bg-slate-800 transition-all duration-300 group">
                <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl shadow-sm flex items-center justify-center text-purple-600 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-900 dark:text-white">Reports</h4>
                <p class="text-xs text-slate-500 mt-1">View overall rankings.</p>
            </a>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Helper to escape HTML and prevent XSS
        function escapeHTML(str) {
            if (!str) return "";
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // Real-time Update Logic (Stats & Judging Status)
        async function refreshDashboard() {
            try {
                const response = await fetch('{{ route('dashboard.data') }}');
                const data = await response.json();

                if (document.getElementById('stat-contestants')) document.getElementById('stat-contestants').innerText = data.stats.contestants;
                if (document.getElementById('stat-criteria')) document.getElementById('stat-criteria').innerText = data.stats.criteria;
                if (document.getElementById('stat-judges')) document.getElementById('stat-judges').innerText = data.stats.judges;

                const tbody = document.getElementById('leaderboard-body');
                if (tbody && data.rankings) {
                    tbody.innerHTML = data.rankings.map(item => `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg ${item.rank == 1 ? 'bg-amber-100 text-amber-600' : (item.rank == 2 ? 'bg-slate-100 text-slate-600' : 'bg-indigo-50 text-indigo-600')} font-black text-xs">
                                    #${item.rank}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm border-2 border-white dark:border-slate-800 group-hover:scale-110 transition-transform duration-300">
                                        ${item.image_path ? 
                                            \`<img src="/storage/\${item.image_path}" class="w-full h-full object-cover">\` : 
                                            \`<div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                            `<img src="/storage/${item.image_path}" class="w-full h-full object-cover">` : 
                                            `<div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>`
                                        }
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-xs font-black text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors uppercase tracking-tight">${escapeHTML(item.name)}</div>
                                        <div class="flex items-center mt-1">
                                            <div class="w-16 h-1 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                                <div class="h-full bg-indigo-500" style="width: ${item.progress}%"></div>
                                            </div>
                                            <span class="ml-2 text-[8px] font-black text-slate-400 uppercase tracking-widest">${Math.round(item.progress)}%</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">${parseFloat(item.average_score).toFixed(2)}</span>
                            </td>
                        </tr>
                    `).join('');
                }

                const judgeGrid = document.getElementById('judging-status-grid');
                if (judgeGrid && data.judgeProgress) {
                    judgeGrid.innerHTML = data.judgeProgress.map(judge => `
                        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/30 rounded-3xl border border-slate-100/50 dark:border-slate-800/50 group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xs">
                                        ${escapeHTML(judge.name.substring(0, 2).toUpperCase())}
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">${escapeHTML(judge.name)}</h4>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">${judge.is_done ? 'COMPLETED' : 'IN PROGRESS'}</span>
                                    </div>
                                </div>
                                ${judge.is_done ? `
                                <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                \` : ''}
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between items-end">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">\${judge.scored}/\${judge.total} ITEMS</span>
                                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">\${Math.round(judge.progress)}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full \${judge.is_done ? 'bg-emerald-500' : 'bg-indigo-500'} transition-all duration-1000" style="width: \${judge.progress}%"></div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }

                @if(count($rankings) > 0)
                // Chart update if ranking exists
                if (typeof chart !== 'undefined') {
                    chart.data.labels = data.rankings.map(i => i.name);
                    chart.data.datasets[0].data = data.rankings.map(i => i.average_score);
                    chart.update();
                }
                @endif

            } catch (error) {
                console.error('Auto-refresh failed:', error);
            }
        }

        setInterval(refreshDashboard, 5000);
    });
</script>

@if(count($rankings) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('rankingChart').getContext('2d');
        const data = @json($rankings);
        const labels = data.map(i => i.name);
        const scores = data.map(i => i.average_score);

        window.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Average Score',
                    data: scores,
                    backgroundColor: [
                        'rgba(99,102,241,0.8)', 'rgba(168,85,247,0.8)',
                        'rgba(236,72,153,0.8)', 'rgba(244,63,94,0.8)',
                        'rgba(249,115,22,0.8)'
                    ],
                    borderColor: ['#6366f1','#a855f7','#ec4899','#f43f5e','#f97316'],
                    borderWidth: 2, borderRadius: 16, barThickness: 40,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { size: 14, weight: 'bold', family: 'Outfit' },
                        bodyFont: { size: 13, family: 'Outfit' },
                        padding: 12, cornerRadius: 12, displayColors: false
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(226,232,240,0.3)' }, ticks: { font: { family: 'Outfit', weight: 'bold' }, color: '#64748b' } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Outfit', weight: 'bold' }, color: '#64748b' } }
                },
                animation: { duration: 2000, easing: 'easeOutQuart' }
            }
        });
    });
</script>
@endif
@endpush
