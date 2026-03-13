@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-6">
            <a href="{{ route('scoring.index') }}" class="w-14 h-14 bg-white dark:bg-slate-900 flex items-center justify-center rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 transition-all shadow-sm group">
                <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Assessment Session</h1>
                <p class="text-slate-500 font-medium">Evaluating performance for <span class="text-indigo-600 dark:text-indigo-400">{{ $contestant->name }}</span>.</p>
            </div>
        </div>

        <div class="flex items-center space-x-4 bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-200 dark:shadow-none">
                #{{ $contestant->number }}
            </div>
            <div class="pr-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">JUDGE ID</p>
                <p class="text-sm font-black text-slate-800 dark:text-white mt-1 uppercase tracking-tight">{{ $judge->name }}</p>
            </div>
        </div>
    </div>

    <!-- Two-Column Layout: Profile + Criteria -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Contestant Profile Card (Sticky Sidebar) -->
        <div class="lg:col-span-4">
            <div class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
                    <!-- Photo -->
                    <div class="relative aspect-[4/3] bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        @if($contestant->image_path)
                            <img src="{{ asset('storage/' . $contestant->image_path) }}" alt="{{ $contestant->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-24 h-24 mx-auto bg-slate-200 dark:bg-slate-700 rounded-[2rem] flex items-center justify-center mb-3">
                                        <span class="text-5xl font-black text-slate-400 dark:text-slate-500">{{ $contestant->number }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No Photo</p>
                                </div>
                            </div>
                        @endif
                        <!-- Number Badge -->
                        <div class="absolute top-4 left-4 bg-indigo-600 px-4 py-2 rounded-xl shadow-lg">
                            <span class="text-white text-xs font-black uppercase tracking-widest">Contestant #{{ str_pad($contestant->number, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-8 space-y-5">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight uppercase">{{ $contestant->name }}</h2>
                            @if($contestant->description)
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed font-medium">{{ $contestant->description }}</p>
                            @endif
                        </div>

                        <div class="h-px bg-slate-100 dark:bg-slate-800"></div>

                        <!-- Quick Stats -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Criteria to Score</span>
                                <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ $criteria->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Previously Scored</span>
                                <span class="text-sm font-black text-{{ $existingScores->count() > 0 ? 'emerald' : 'slate' }}-600 dark:text-{{ $existingScores->count() > 0 ? 'emerald' : 'slate' }}-400">
                                    {{ $existingScores->count() }} / {{ $criteria->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assessment Form -->
        <div class="lg:col-span-8">
            <form action="{{ route('scoring.store', $contestant) }}" method="POST" class="space-y-8 pb-20">
                @csrf
                {{-- Security: judge_id is now handled in the controller via auth() --}}
                
                {{-- Scoring Table --}}
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden transition-all duration-300">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Criterion / Performance Metric</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center w-32">Weight</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center w-72">Assessment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($criteria as $criterion)
                                <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-8 py-8">
                                        <div class="flex items-center gap-4 mb-2">
                                            <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight group-hover:text-indigo-600 transition-colors uppercase">{{ $criterion->name }}</h3>
                                            @if($existingScores->has($criterion->id))
                                                <span class="inline-flex items-center px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950/30 text-[8px] font-black text-emerald-600 dark:text-emerald-400 rounded-md tracking-widest uppercase">Saved</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-2xl">
                                            {{ $criterion->description ?: 'Follow the general evaluation standards for this performance metric.' }}
                                        </p>
                                    </td>
                                    <td class="px-8 py-8 text-center">
                                        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 dark:bg-indigo-950/30 text-[10px] font-black text-indigo-600 dark:text-indigo-400 rounded-lg tracking-widest">
                                            {{ $criterion->weight }}%
                                        </span>
                                    </td>
                                    <td class="px-8 py-8">
                                        <div class="relative max-w-xs mx-auto">
                                            <select name="scores[{{ $criterion->id }}]" required
                                                class="block w-full px-5 py-3.5 rounded-2xl border-none bg-slate-100/50 dark:bg-slate-950/50 text-slate-900 dark:text-white font-black text-sm shadow-inner focus:ring-4 focus:ring-indigo-500/10 appearance-none text-center group-hover:bg-white dark:group-hover:bg-slate-900 transition-colors">
                                                <option value="">-- SELECT GRADE --</option>
                                                @foreach($criterion->grades->sortByDesc('score') as $grade)
                                                    <option value="{{ $grade->score }}" @if(old("scores.{$criterion->id}", $existingScores[$criterion->id] ?? '') == $grade->score && old("scores.{$criterion->id}", $existingScores[$criterion->id] ?? '') !== '') selected @endif>
                                                        {{ strtoupper($grade->label) }} ({{ number_format($grade->score, 0) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-indigo-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="fixed bottom-10 left-1/2 md:left-auto md:right-10 transform -translate-x-1/2 md:translate-x-0 w-[90%] md:w-80">
                    <button type="submit" class="w-full bg-slate-900 dark:bg-indigo-600 hover:bg-indigo-600 text-white font-black py-6 rounded-3xl shadow-[0_20px_50px_-15px_rgba(79,70,229,0.5)] transition-all duration-300 transform hover:-translate-y-2 active:scale-[0.98] uppercase tracking-[0.2em] flex items-center justify-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Finalize Scores
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
