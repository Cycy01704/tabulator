@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Judging Panel</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">Select a participant to begin evaluation as <span class="text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-tight">{{ $judge->name }}</span>.</p>
        </div>
        <div class="hidden md:flex items-center space-x-2 bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Live Assessment active</span>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input type="text" id="contestantSearch" 
            class="block w-full pl-16 pr-8 py-6 bg-white dark:bg-slate-900 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-[2.5rem] text-lg font-bold placeholder:text-slate-400 dark:placeholder:text-slate-600 shadow-xl shadow-slate-200/50 dark:shadow-none transition-all outline-none" 
            placeholder="Search for a participant by name or contestant number...">
    </div>

    <div id="contestantGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($contestants as $contestant)
            <div class="contestant-card group relative bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden flex flex-col transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-indigo-500/30"
                 data-name="{{ $contestant->name }}"
                 data-number="{{ $contestant->number }}">
                <!-- Image Container -->
                <div class="relative h-64 bg-slate-100 dark:bg-slate-950/50 overflow-hidden">
                    @if($contestant->image_path)
                        <img src="{{ asset('storage/' . $contestant->image_path) }}" alt="{{ $contestant->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 transition-colors group-hover:text-indigo-300">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                    
                    <!-- ID Badge -->
                    <div class="absolute top-6 left-6 flex items-center">
                        <div class="px-4 py-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-xl shadow-lg border border-white/20">
                            <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">ID #{{ $contestant->number }}</span>
                        </div>
                    </div>

                    <!-- Name on Image -->
                    <div class="absolute bottom-6 left-6 right-6">
                        <h2 class="text-2xl font-black text-white tracking-tight group-hover:translate-x-1 transition-transform duration-300">{{ $contestant->name }}</h2>
                    </div>
                </div>

                <!-- Info Body -->
                <div class="p-8 flex-1 flex flex-col">
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 line-clamp-2 font-medium leading-relaxed italic">
                        "{{ $contestant->description ?: 'No introductory context provided for this participant.' }}"
                    </p>
                    
                    <div class="mt-auto">
                        <a href="{{ route('scoring.create', $contestant) }}" class="relative w-full inline-flex justify-center items-center px-6 py-5 bg-indigo-600 text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-lg shadow-indigo-200 dark:shadow-none hover:bg-slate-900 dark:hover:bg-indigo-500 transition-all duration-300 overflow-hidden group/btn">
                            <span class="relative z-10 flex items-center">
                                EVALUATE NOW
                                <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <div id="emptyState" class="{{ $contestants->isEmpty() ? '' : 'hidden' }} col-span-full py-24 text-center bg-white dark:bg-slate-900 rounded-[3rem] border-4 border-dashed border-slate-100 dark:border-slate-800">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center text-slate-200 dark:text-slate-700 mx-auto mb-8 shadow-inner">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            @if(!$activeEvent)
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Judging Inactive</h3>
                <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium max-w-sm mx-auto">There is no active competition phase at the moment. Please wait for the committee to start an event.</p>
            @else
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">No participants available</h3>
                <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium max-w-sm mx-auto">We couldn't find any participants matching your search or the roster is empty.</p>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('contestantSearch');
        const contestantCards = document.querySelectorAll('.contestant-card');
        const emptyState = document.getElementById('emptyState');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let hasResults = false;
                
                contestantCards.forEach(card => {
                    const name = card.getAttribute('data-name').toLowerCase();
                    const number = card.getAttribute('data-number').toLowerCase();
                    
                    if (name.includes(query) || number.includes(query)) {
                        card.classList.remove('hidden');
                        hasResults = true;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                if (emptyState) {
                    if (!hasResults && query.length > 0) {
                        emptyState.classList.remove('hidden');
                    } else if (hasResults) {
                        emptyState.classList.add('hidden');
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
