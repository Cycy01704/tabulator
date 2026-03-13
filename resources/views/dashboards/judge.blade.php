@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">

    {{-- Welcome Header --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 rounded-[2.5rem] p-10 shadow-2xl shadow-indigo-200/50 dark:shadow-none">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full -ml-20 -mb-20 blur-3xl"></div>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">Judge Panel</span>
                </div>
                <h1 class="text-4xl font-extrabold text-white tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-indigo-100 font-medium text-lg tracking-tight">
                    Scoring for: <span class="text-emerald-400 font-black italic">{{ $activeEvent ? $activeEvent->name : 'No Active Event' }}</span>
                </p>
            </div>
            <a href="{{ route('scoring.index') }}"
               class="inline-flex items-center gap-3 bg-white text-indigo-700 font-black text-sm uppercase tracking-widest px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Start Scoring
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Contestants --}}
        <div class="relative group overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Total Contestants</h3>
                <div class="flex items-baseline mt-2">
                    <p class="text-5xl font-extrabold text-slate-900 dark:text-white">{{ $stats['contestants'] }}</p>
                    <span class="ml-2 text-xs font-bold text-indigo-600">TO SCORE</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>

        {{-- Scored --}}
        <div class="relative group overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Scored</h3>
                <div class="flex items-baseline mt-2">
                    <p class="text-5xl font-extrabold text-slate-900 dark:text-white">{{ $stats['scored'] }}</p>
                    <span class="ml-2 text-xs font-bold text-emerald-600">COMPLETED</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>

        {{-- Progress --}}
        <div class="relative group overflow-hidden bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/30 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">My Progress</h3>
                <div class="flex items-baseline mt-2">
                    <p class="text-5xl font-extrabold text-slate-900 dark:text-white">{{ $stats['progress'] }}</p>
                    <span class="ml-2 text-xs font-bold text-amber-600">% DONE</span>
                </div>
                <div class="mt-3 w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 transition-all duration-500" style="width: {{ $stats['progress'] }}%"></div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
    </div>

    {{-- Contestant Progress List --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">My Scoring Sheet</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Tap a contestant to score or review their entry.</p>
            </div>
            <a href="{{ route('scoring.index') }}" class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest flex items-center gap-1 hover:text-indigo-700">
                View All
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        {{-- Search Input --}}
        <div class="px-8 pb-4">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="contestantSearch" 
                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl text-sm font-medium placeholder:text-slate-400 dark:placeholder:text-slate-500 transition-all outline-none" 
                    placeholder="Search by name or contestant number...">
            </div>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($contestantProgress as $contestant)
            <a href="{{ route('scoring.create', $contestant->id) }}"
               class="contestant-item flex items-center justify-between p-6 px-8 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group"
               data-name="{{ $contestant->name }}"
               data-number="{{ $contestant->number }}">
                <div class="flex items-center gap-5">
                    <div class="relative w-12 h-12 rounded-2xl overflow-hidden shadow-sm border-2 border-white dark:border-slate-800 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        @if($contestant->image_path)
                            <img src="{{ asset('storage/' . $contestant->image_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <span class="text-lg font-black text-slate-400">{{ $contestant->number }}</span>
                            </div>
                        @endif
                        @if($contestant->done)
                            <div class="absolute inset-0 bg-emerald-500/80 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">#{{ str_pad($contestant->number, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <p class="font-black text-slate-900 dark:text-white text-sm uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $contestant->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $contestant->scored }}/{{ $contestant->total }} criteria</div>
                        <div class="w-24 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mt-1">
                            <div class="h-full transition-all duration-500 {{ $contestant->done ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                                 style="width: {{ $contestant->total > 0 ? ($contestant->scored / $contestant->total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @if($contestant->done)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest">Done</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest">Score</span>
                    @endif
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
            @empty
            <div class="p-12 text-center text-slate-400 font-bold">No contestants registered yet.</div>
            @endforelse
        </div>
    </div>

</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('contestantSearch');
        const contestantItems = document.querySelectorAll('.contestant-item');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                contestantItems.forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    const number = item.getAttribute('data-number').toLowerCase();
                    
                    if (name.includes(query) || number.includes(query)) {
                        item.classList.remove('hidden');
                        item.classList.add('flex');
                    } else {
                        item.classList.add('hidden');
                        item.classList.remove('flex');
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection
