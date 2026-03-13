@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-6">
            <a href="{{ route('reports.index') }}" class="w-14 h-14 bg-white dark:bg-slate-900 flex items-center justify-center rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 transition-all shadow-sm group">
                <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Profile Performance</h1>
                <p class="text-slate-500 font-medium">Granular score breakdown for <strong>{{ $contestant->name }}</strong>.</p>
            </div>
        </div>

        <button onclick="window.print()" class="inline-flex items-center px-6 py-4 bg-slate-900 dark:bg-slate-800 text-white font-bold rounded-2xl shadow-lg transition-all hover:bg-black group">
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Download Report
        </button>
    </div>

    <!-- Overview Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 print:grid-cols-1">
        <!-- Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                
                <div class="relative flex flex-col items-center">
                    <div class="w-48 h-48 rounded-[2.5rem] overflow-hidden border-8 border-slate-50 dark:border-slate-800 shadow-2xl mb-8">
                        @if($contestant->image_path)
                            <img src="{{ asset('storage/' . $contestant->image_path) }}" alt="{{ $contestant->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300 text-sm font-black italic">NO ASSET</div>
                        @endif
                    </div>
                    
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2 text-center">{{ $contestant->name }}</h2>
                    <span class="px-6 py-2 bg-slate-900 text-white rounded-full text-xs font-black tracking-widest uppercase mb-8">ENTRY ID #{{ $contestant->number }}</span>
                    
                    <div class="w-full space-y-4">
                        <div class="p-6 bg-slate-50/50 dark:bg-slate-950/50 rounded-3xl border border-slate-100 dark:border-slate-800 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">AGGREGATED AVG</p>
                            <p class="text-5xl font-black text-indigo-600 tabular-nums">
                                @php
                                    $allScores = collect($scoreMap->flatten());
                                    $avg = $allScores->count() > 0 ? $allScores->avg('score') : 0;
                                @endphp
                                {{ number_format($avg, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-8 bg-indigo-600 rounded-[3rem] text-white shadow-xl shadow-indigo-200 dark:shadow-none">
                <h4 class="font-black text-sm uppercase tracking-widest mb-4">Competitor Insight</h4>
                <p class="text-indigo-50 font-medium leading-relaxed italic">
                    "{{ $contestant->description ?: 'No detailed context available from the registration profile.' }}"
                </p>
            </div>
        </div>

        <!-- Scores Breakdown -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight uppercase tracking-[0.1em]">Judge Scoring Matrix</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100 dark:border-slate-800">PANEL MEMBERS</th>
                                @foreach($criteria as $criterion)
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                        {{ $criterion->name }}
                                        <span class="block text-[8px] opacity-70 mt-1">({{ $criterion->weight }}%)</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($judges as $judge)
                                <tr>
                                    <td class="px-8 py-6 bg-slate-50/30 dark:bg-slate-800/20 border-r border-slate-100 dark:border-slate-800">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 flex items-center justify-center text-indigo-600 font-black text-[10px] shadow-sm border border-slate-100 dark:border-slate-700">J</div>
                                            <span class="ml-3 text-sm font-black text-slate-800 dark:text-slate-100 truncate w-24 tracking-tight">{{ $judge->name }}</span>
                                        </div>
                                    </td>
                                    @foreach($criteria as $criterion)
                                        <td class="px-8 py-6 text-center tabular-nums">
                                            @php
                                                $score = $scoreMap->get($judge->id)?->get($criterion->id)?->score;
                                            @endphp
                                            @if($score !== null)
                                                <span class="text-base font-black text-slate-900 dark:text-white">{{ number_format($score, 2) }}</span>
                                            @else
                                                <span class="text-xs font-black text-slate-300 italic">PENDING</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-indigo-50/50 dark:bg-indigo-950/20">
                                <td class="px-8 py-6 font-black text-[10px] uppercase tracking-widest text-indigo-600 dark:text-indigo-400 border-r border-slate-100 dark:border-slate-800">Metric Average</td>
                                @foreach($criteria as $criterion)
                                    <td class="px-8 py-6 text-center tabular-nums">
                                        @php
                                            $cScores = collect($scoreMap->flatten())->where('criterion_id', $criterion->id);
                                            $cAvg = $cScores->count() > 0 ? $cScores->avg('score') : 0;
                                        @endphp
                                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">{{ number_format($cAvg, 2) }}</span>
                                    </td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
