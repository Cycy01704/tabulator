@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Final Rankings</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium tracking-tight">Consolidated results based on judge averages.</p>
        </div>
        <div class="flex items-center gap-4 flex-wrap">
            {{-- Judge count badge --}}
            <div class="flex items-center space-x-4 bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <div class="flex -space-x-3">
                    @for($i = 0; $i < $judgeCount; $i++)
                        <div class="w-10 h-10 rounded-full border-4 border-white dark:border-slate-900 bg-indigo-50 dark:bg-indigo-950 flex items-center justify-center text-indigo-600 text-xs font-black">J</div>
                    @endfor
                </div>
                <div class="pr-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">JUDGES ACTIVE</p>
                    <p class="text-sm font-black text-slate-800 dark:text-white mt-1">{{ $judgeCount }} PANELIST{{ $judgeCount > 1 ? 'S' : '' }}</p>
                </div>
            </div>
            {{-- Export button --}}
            <a href="{{ route('reports.export') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest px-6 py-4 rounded-2xl shadow-lg shadow-emerald-200/50 hover:scale-105 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export to Excel
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">RANKING</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">PARTICIPANT</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 text-center">COMPLETION</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 text-right pr-12">AVERAGE SCORE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($contestants as $contestant)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all duration-300 group cursor-pointer" onclick="window.location='{{ route('reports.show', $contestant->id) }}'">
                            <td class="px-8 py-8 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($contestant->rank == 1)
                                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm border border-amber-200/50 font-black text-xl">1</div>
                                    @elseif($contestant->rank == 2)
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-500 shadow-sm border border-slate-200/50 font-black text-xl">2</div>
                                    @elseif($contestant->rank == 3)
                                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center text-orange-600 shadow-sm border border-orange-200/50 font-black text-xl">3</div>
                                    @else
                                        <div class="w-12 h-12 flex items-center justify-center text-slate-400 font-bold text-lg pr-4">{{ $contestant->rank }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-8 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-white dark:border-slate-800 shadow-md transition-transform group-hover:scale-110 duration-500">
                                        @if($contestant->image_path)
                                            <img src="{{ asset('storage/' . $contestant->image_path) }}" alt="{{ $contestant->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300 italic text-[10px]">NO PHOTO</div>
                                        @endif
                                    </div>
                                    <div class="ml-5">
                                        <div class="text-lg font-black text-slate-900 dark:text-white tracking-tight group-hover:text-indigo-600 transition-colors">{{ $contestant->name }}</div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">ID #{{ $contestant->number }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8 whitespace-nowrap text-center">
                                <div class="inline-flex flex-col items-center">
                                    <div class="w-32 bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden mb-2">
                                        <div class="bg-indigo-500 h-full transition-all duration-700" style="width: {{ $contestant->progress }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-black {{ $contestant->is_complete ? 'text-emerald-500' : 'text-slate-400' }} uppercase tracking-widest">
                                        {{ $contestant->is_complete ? 'FULLY SCORED' : number_format($contestant->progress, 0) . '% EVALUATED' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-8 whitespace-nowrap text-right pr-12">
                                <div class="text-3xl font-black text-slate-900 dark:text-white tabular-nums">{{ number_format($contestant->average_score, 2) }}</div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase pr-1">POINTS AVG</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center">
                                <p class="text-slate-400 font-black tracking-widest uppercase">No competition data consolidated yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
