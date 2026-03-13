@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('archives.index') }}" class="p-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $archive->event_name }}</h1>
                <p class="mt-1 text-slate-500 dark:text-slate-400 font-medium tracking-tight">Archived on {{ $archive->event_date->format('F d, Y') }}</p>
            </div>
        </div>
        <a href="{{ route('archives.download', $archive->id) }}" class="flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-indigo-200 dark:shadow-none group">
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download JSON
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Stats Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">Snapshot Metadata</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-500 uppercase">Contestants</span>
                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ $rankings->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-500 uppercase">Criteria</span>
                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ $criteria->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-500 uppercase">Total Scores</span>
                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ count($archive->data['scores'] ?? []) }}</span>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter leading-tight">
                            This archive represents a full point-in-time snapshot of the system state including judges and configuration.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Criteria List --}}
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">Judging Criteria</h3>
                <div class="space-y-3">
                    @foreach($criteria as $criterion)
                    <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-tight">{{ $criterion['name'] }}</span>
                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/30 px-2 py-1 rounded-lg">{{ $criterion['weight'] ?? $criterion['percentage'] ?? '—' }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Final Leaderboard --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Final Leaderboard</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Results as of event conclusion</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest">Final</span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($rankings as $contestant)
                    <div class="p-6 px-8 hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        <div class="flex items-center justify-between cursor-pointer" onclick="toggleBreakdown({{ $contestant->rank }})">
                            <div class="flex items-center gap-5">
                                {{-- Rank Badge --}}
                                @if($contestant->rank === 1)
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-amber-200 dark:shadow-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 7.134a1 1 0 01.86 1.5l-2.31 3.79 1.543 4.233a1 1 0 01-1.476 1.133L12 14.93l-4.117 2.86a1 1 0 01-1.476-1.133l1.543-4.233-2.31-3.79a1 1 0 01.86-1.5l3.354.066L11.033 2.744A1 1 0 0112 2z" clip-rule="evenodd"></path></svg>
                                    </div>
                                @elseif($contestant->rank === 2)
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-300 to-slate-500 flex items-center justify-center text-white font-black text-sm shadow-lg">2</div>
                                @elseif($contestant->rank === 3)
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-black text-sm shadow-lg">3</div>
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-black text-sm">{{ $contestant->rank }}</div>
                                @endif

                                {{-- Avatar --}}
                                <div class="relative w-12 h-12 rounded-2xl overflow-hidden shadow-sm border-2 border-white dark:border-slate-800 flex-shrink-0">
                                    @if($contestant->image_path)
                                        <img src="{{ asset('storage/' . $contestant->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                            <span class="text-lg font-black text-slate-400">{{ $contestant->number }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Name --}}
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">#{{ str_pad($contestant->number, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <p class="font-black text-slate-900 dark:text-white text-sm uppercase tracking-tight">{{ $contestant->name }}</p>
                                </div>
                            </div>

                            {{-- Score --}}
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $contestant->average_score }}</span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">AVG</span>
                                </div>
                                <svg id="chevron-{{ $contestant->rank }}" class="w-4 h-4 text-slate-300 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        {{-- Expandable Score Breakdown --}}
                        <div id="breakdown-{{ $contestant->rank }}" class="hidden mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($criteria as $criterion)
                                @php
                                    $criterionId = $criterion['id'];
                                    $breakdownScore = isset($contestant->breakdown[$criterionId]) ? round($contestant->breakdown[$criterionId], 2) : '—';
                                @endphp
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-center">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 truncate" title="{{ $criterion['name'] }}">{{ $criterion['name'] }}</div>
                                    <div class="text-lg font-black text-slate-900 dark:text-white">{{ $breakdownScore }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center text-slate-400 font-bold">No contestant data in this archive.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleBreakdown(rank) {
        const panel = document.getElementById('breakdown-' + rank);
        const chevron = document.getElementById('chevron-' + rank);
        panel.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
</script>
@endpush
