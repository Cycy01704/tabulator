@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">System Administration</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">Global system overview and configuration.</p>
        </div>
        <div class="flex items-center space-x-4">
            {{-- Global Security Toggle --}}
            <div class="flex items-center bg-white dark:bg-slate-900 p-2 pl-4 rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                <span class="text-[10px] font-black text-rose-500 dark:text-rose-400 uppercase tracking-widest mr-4">Global Security</span>
                <button type="button" id="securityToggle" 
                    class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $securityEnabled == '1' ? 'bg-rose-600' : 'bg-slate-200 dark:bg-slate-700' }}"
                    role="switch" aria-checked="{{ $securityEnabled == '1' ? 'true' : 'false' }}">
                    <span id="securityToggleKnob" aria-hidden="true" 
                        class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $securityEnabled == '1' ? 'translate-x-6' : 'translate-x-0' }}">
                    </span>
                </button>
            </div>

            {{-- Leaderboard Toggle --}}
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-white dark:bg-slate-900 p-2 pl-4 rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mr-4">Audience Leaderboard</span>
                    <button type="button" id="leaderboardToggle" 
                        class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $leaderboardVisible == '1' ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700' }}"
                        role="switch" aria-checked="{{ $leaderboardVisible == '1' ? 'true' : 'false' }}">
                        <span id="leaderboardToggleKnob" aria-hidden="true" 
                            class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $leaderboardVisible == '1' ? 'translate-x-6' : 'translate-x-0' }}">
                        </span>
                    </button>
                </div>
                
                {{-- Leaderboard Filter --}}
                <div class="relative group">
                    <button type="button" class="flex items-center justify-between w-24 bg-white dark:bg-slate-900 px-4 py-2 text-xs font-black text-slate-600 dark:text-slate-300 rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800 transition-colors hover:border-indigo-500 dropdown-btn">
                        <span id="filterLabel">{{ $leaderboardFilter === 'all' ? 'All' : 'Top ' . $leaderboardFilter }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-32 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="p-2 space-y-1">
                            <button type="button" class="filter-option w-full text-left px-4 py-2 text-xs font-bold rounded-xl {{ $leaderboardFilter == '3' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}" data-value="3">Top 3</button>
                            <button type="button" class="filter-option w-full text-left px-4 py-2 text-xs font-bold rounded-xl {{ $leaderboardFilter == '5' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}" data-value="5">Top 5</button>
                            <button type="button" class="filter-option w-full text-left px-4 py-2 text-xs font-bold rounded-xl {{ $leaderboardFilter == '10' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}" data-value="10">Top 10</button>
                            <button type="button" class="filter-option w-full text-left px-4 py-2 text-xs font-bold rounded-xl {{ $leaderboardFilter === 'all' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}" data-value="all">All</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-3 bg-white dark:bg-slate-900 p-2 px-6 rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                @if($activeEvent)
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">{{ $activeEvent->name }}</span>
                @else
                    <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        No Active Event
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- System Health Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-950/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Users</h3>
            <p id="stat-total-users" class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $stats['total_users'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 dark:bg-purple-950/30 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Contestants</h3>
            <p id="stat-total-contestants" class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $stats['total_contestants'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-950/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
            </div>
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Event Archives</h3>
            <p class="text-3xl font-black text-slate-900 dark:text-white mt-1 uppercase tracking-tight"><span id="stat-total-archives">{{ $stats['total_archives'] }}</span> <span class="text-xs font-bold text-slate-400">SAVED</span></p>
        </div>
    </div>
    
    {{-- System Usage Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-indigo-600 p-6 rounded-[2.5rem] shadow-xl shadow-indigo-200 dark:shadow-none border border-indigo-500 overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-indigo-500 rounded-full opacity-20 group-hover:scale-125 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black text-indigo-100 uppercase tracking-widest">Scoring Volume</span>
                    <div class="w-6 h-6 bg-indigo-500 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <div class="flex items-baseline space-x-2">
                    <h3 id="stat-active-scores" class="text-3xl font-black text-white tracking-tighter">{{ $stats['active_event_scores'] ?? 0 }}</h3>
                    <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest">SUBMISSIONS</span>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <div class="flex -space-x-2 overflow-hidden">
                        @foreach(range(1, 3) as $i)
                        <div class="inline-block h-5 w-5 rounded-full ring-2 ring-indigo-600 bg-indigo-400"></div>
                        @endforeach
                    </div>
                    <span class="text-[8px] font-black text-indigo-100 uppercase tracking-widest">Active event activity</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden relative group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Panel Engagement</span>
                <div class="w-8 h-8 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline space-x-2">
                <h3 id="stat-active-judges" class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $stats['active_judges_count'] ?? 0 }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">JUDGES ACTIVE</span>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Out of {{ $stats['total_judges'] }} total</div>
                <div class="w-16 h-1 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div id="stat-judges-bar" class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $stats['total_judges'] > 0 ? ($stats['active_judges_count'] / $stats['total_judges']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden relative group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">System Lifetime</span>
                <div class="w-8 h-8 bg-amber-50 dark:bg-amber-950/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline space-x-2">
                <h3 id="stat-total-archives-usage" class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $stats['total_archives'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">EVENTS COMPLETED</span>
            </div>
            <div class="mt-4 text-[8px] font-black text-slate-400 uppercase tracking-widest">
                Data persistence active
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden relative group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Reach</span>
                <div class="w-8 h-8 bg-rose-50 dark:bg-rose-950/30 rounded-xl flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9-3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
            </div>
            <div class="flex items-baseline space-x-2">
                <h3 id="stat-total-scores-lifetime" class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($stats['total_scores']) }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">TOTAL SCORING OPS</span>
            </div>
            <div class="mt-4 text-[8px] font-black text-rose-500 uppercase tracking-widest flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> SYSTEM NOMINAL
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {{-- System Management --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Administrative Framework</h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('events.index') }}" class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-indigo-500/20 hover:bg-white dark:hover:bg-slate-800 transition-all group">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl shadow-sm flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white">Event Lifecycle</h4>
                        <p class="text-xs text-slate-500 mt-1">Plan, start, and oversee competition phases.</p>
                    </a>

                    <a href="{{ route('settings.index') }}" class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-indigo-500/20 hover:bg-white dark:hover:bg-slate-800 transition-all group">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl shadow-sm flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white">Global Settings</h4>
                        <p class="text-xs text-slate-500 mt-1">Configure competition name, passkeys, and modules.</p>
                    </a>

                    <a href="{{ route('users.index') }}" class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-transparent hover:border-emerald-500/20 hover:bg-white dark:hover:bg-slate-800 transition-all group">
                        <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-xl shadow-sm flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white">User Accounts</h4>
                        <p class="text-xs text-slate-500 mt-1">Manage Committee and Judge access credentials.</p>
                    </a>


                </div>
            </div>
        </div>

        {{-- Top Performers Quick Look --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Top Performance</h2>
            </div>
            <div id="top-performers-list" class="p-8 space-y-6">
                @foreach($topPerformers as $performer)
                <div class="flex items-center group">
                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center font-black text-xs rounded-2xl {{ $loop->iteration == 1 ? 'bg-amber-400 text-white shadow-lg shadow-amber-200' : ($loop->iteration == 2 ? 'bg-slate-300 text-slate-700' : ($loop->iteration == 3 ? 'bg-orange-400 text-white shadow-lg shadow-orange-200' : 'bg-slate-100 dark:bg-slate-800 text-slate-400')) }}">
                        {{ $loop->iteration }}
                    </div>
                    <div class="ml-4 flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight truncate">{{ $performer->name }}</h4>
                            <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">#{{ $performer->number }}</span>
                        </div>
                        <div class="flex items-center mt-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $activeEvent->name ?? 'SCORING' }}</span>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <div class="text-lg font-black text-slate-900 dark:text-white tracking-tighter">{{ $performer->avg }}</div>
                        <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">AV SCORE</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Judging Status --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Judging Status</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Individual panelist scoring progress.</p>
            </div>
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

    {{-- Passkey Modal --}}
    <div id="passkeyModal" class="fixed inset-0 z-50 hidden items-center justify-center p-6">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-rank">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 text-center">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Security Verification</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Please enter the administrative passkey to enable the public leaderboard.</p>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Passkey</label>
                    <input type="password" id="passkeyInput" class="w-full bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 text-center text-2xl font-black tracking-[0.5em] outline-none" placeholder="••••">
                </div>
                <div class="flex gap-4">
                    <button type="button" id="cancelPasskey" class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Cancel</button>
                    <button type="button" id="confirmPasskey" class="flex-1 px-6 py-4 rounded-2xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 dark:shadow-none transition-all">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const securityToggle = document.getElementById('securityToggle');
        const securityToggleKnob = document.getElementById('securityToggleKnob');
        let isSecurityEnabled = {{ $securityEnabled == '1' ? 'true' : 'false' }};

        const toggleBtn = document.getElementById('leaderboardToggle');
        const toggleKnob = document.getElementById('leaderboardToggleKnob');
        let isVisible = {{ $leaderboardVisible == '1' ? 'true' : 'false' }};

        // Modal Elements
        const passkeyModal = document.getElementById('passkeyModal');
        const passkeyInput = document.getElementById('passkeyInput');
        const confirmPasskey = document.getElementById('confirmPasskey');
        const cancelPasskey = document.getElementById('cancelPasskey');

        let currentToggleType = null;
        let toggleTargetValue = null;

        if (securityToggle) {
            securityToggle.addEventListener('click', function() {
                toggleTargetValue = !isSecurityEnabled ? '1' : '0';
                currentToggleType = 'security';
                passkeyModal.classList.remove('hidden');
                passkeyModal.classList.add('flex');
                passkeyInput.value = '';
                passkeyInput.focus();
            });
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                toggleTargetValue = !isVisible ? '1' : '0';
                currentToggleType = 'leaderboard';
                passkeyModal.classList.remove('hidden');
                passkeyModal.classList.add('flex');
                passkeyInput.value = '';
                passkeyInput.focus();
            });
        }

        // Filter Options logic
        const filterOptions = document.querySelectorAll('.filter-option');
        filterOptions.forEach(option => {
            option.addEventListener('click', function() {
                const newValue = this.dataset.value;
                if (newValue !== '{{ $leaderboardFilter }}') {
                    toggleTargetValue = newValue;
                    currentToggleType = 'filter';
                    passkeyModal.classList.remove('hidden');
                    passkeyModal.classList.add('flex');
                    passkeyInput.value = '';
                    passkeyInput.focus();
                }
            });
        });

        if (cancelPasskey) {
            cancelPasskey.addEventListener('click', () => {
                passkeyModal.classList.add('hidden');
                passkeyModal.classList.remove('flex');
                currentToggleType = null;
            });
        }

        if (confirmPasskey) {
            confirmPasskey.addEventListener('click', () => {
                const passkey = passkeyInput.value;
                if (!passkey) {
                    showToast('Please enter the passkey', 'error');
                    return;
                }
                
                if (currentToggleType === 'security') {
                    updateSecuritySetting(toggleTargetValue, passkey);
                } else if (currentToggleType === 'leaderboard') {
                    updateVisibility(toggleTargetValue, passkey);
                } else if (currentToggleType === 'filter') {
                    updateFilterSetting(toggleTargetValue, passkey);
                }
            });
        }

        async function updateFilterSetting(newValue, passkey) {
            try {
                const response = await fetch('{{ route('settings.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        key: 'leaderboard_filter',
                        value: newValue,
                        passkey: passkey
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    showToast('Filter updated globally', 'success');
                    setTimeout(() => location.reload(), 500); // Reload to apply filter to view data
                } else {
                    showToast(data.message || 'Operation failed', 'error');
                }
            } catch (error) {
                console.error('Failed to update setting:', error);
                showToast('Network error occurred', 'error');
            }
        }

        async function updateSecuritySetting(newValue, passkey = null) {
            try {
                const response = await fetch('{{ route('settings.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        key: 'triple_layer_security',
                        value: newValue,
                        passkey: passkey
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    isSecurityEnabled = (newValue === '1');
                    
                    if (isSecurityEnabled) {
                        securityToggle.classList.remove('bg-slate-200', 'dark:bg-slate-700');
                        securityToggle.classList.add('bg-rose-600');
                        securityToggleKnob.classList.remove('translate-x-0');
                        securityToggleKnob.classList.add('translate-x-6');
                        securityToggle.setAttribute('aria-checked', 'true');
                        passkeyModal.classList.add('hidden');
                        passkeyModal.classList.remove('flex');
                    } else {
                        securityToggle.classList.add('bg-slate-200', 'dark:bg-slate-700');
                        securityToggle.classList.remove('bg-rose-600');
                        securityToggleKnob.classList.add('translate-x-0');
                        securityToggleKnob.classList.remove('translate-x-6');
                        securityToggle.setAttribute('aria-checked', 'false');
                    }
                    showToast(data.message, 'success');
                    currentToggleType = null;
                } else {
                    showToast(data.message || 'Operation failed', 'error');
                }
            } catch (error) {
                console.error('Failed to update security setting:', error);
                showToast('Network error occurred', 'error');
            }
        }

        async function updateVisibility(newValue, passkey = null) {
            try {
                const response = await fetch('{{ route('settings.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        key: 'leaderboard_visible',
                        value: newValue,
                        passkey: passkey
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    isVisible = (newValue === '1');
                    
                    if (isVisible) {
                        toggleBtn.classList.remove('bg-slate-200', 'dark:bg-slate-700');
                        toggleBtn.classList.add('bg-indigo-600');
                        toggleKnob.classList.remove('translate-x-0');
                        toggleKnob.classList.add('translate-x-6');
                        toggleBtn.setAttribute('aria-checked', 'true');
                        passkeyModal.classList.add('hidden');
                        passkeyModal.classList.remove('flex');
                    } else {
                        toggleBtn.classList.add('bg-slate-200', 'dark:bg-slate-700');
                        toggleBtn.classList.remove('bg-indigo-600');
                        toggleKnob.classList.add('translate-x-0');
                        toggleKnob.classList.remove('translate-x-6');
                        toggleBtn.setAttribute('aria-checked', 'false');
                    }
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Operation failed', 'error');
                }
            } catch (error) {
                console.error('Failed to update setting:', error);
                showToast('Network error occurred', 'error');
            }
        }

        // Helper to escape HTML and prevent XSS
        function escapeHTML(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // Real-time Update Logic
        async function refreshDashboard() {
            try {
                const response = await fetch('{{ route('dashboard.data') }}');
                const data = await response.json();

                // Update Stats
                if (document.getElementById('stat-total-users')) document.getElementById('stat-total-users').innerText = data.stats.total_users;
                if (document.getElementById('stat-total-contestants')) document.getElementById('stat-total-contestants').innerText = data.stats.total_contestants;
                if (document.getElementById('stat-total-archives')) document.getElementById('stat-total-archives').innerText = data.stats.total_archives;
                
                // New System Usage Stats
                if (document.getElementById('stat-active-scores')) document.getElementById('stat-active-scores').innerText = data.stats.active_event_scores;
                if (document.getElementById('stat-active-judges')) document.getElementById('stat-active-judges').innerText = data.stats.active_judges_count;
                if (document.getElementById('stat-total-archives-usage')) document.getElementById('stat-total-archives-usage').innerText = data.stats.total_archives;
                if (document.getElementById('stat-total-scores-lifetime')) document.getElementById('stat-total-scores-lifetime').innerText = data.stats.total_scores.toLocaleString();
                
                const judgesBar = document.getElementById('stat-judges-bar');
                if (judgesBar && data.stats.total_judges > 0) {
                    const pct = (data.stats.active_judges_count / data.stats.total_judges) * 100;
                    judgesBar.style.width = pct + '%';
                }

                // Update Top Performers
                const performersList = document.getElementById('top-performers-list');
                if (performersList && data.topPerformers) {
                    performersList.innerHTML = data.topPerformers.map((performer, index) => {
                        const rank = index + 1;
                        let rankClass = 'bg-slate-100 dark:bg-slate-800 text-slate-400';
                        let shadowClass = '';
                        
                        if (rank === 1) {
                            rankClass = 'bg-amber-400 text-white';
                            shadowClass = 'shadow-lg shadow-amber-200';
                        } else if (rank === 2) {
                            rankClass = 'bg-slate-300 text-slate-700';
                        } else if (rank === 3) {
                            rankClass = 'bg-orange-400 text-white';
                            shadowClass = 'shadow-lg shadow-orange-200';
                        }

                        return `
                        <div class="flex items-center group animate-rank">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center font-black text-xs rounded-2xl ${rankClass} ${shadowClass}">
                                ${rank}
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight truncate">${escapeHTML(performer.name)}</h4>
                                    <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">#${performer.number}</span>
                                </div>
                                <div class="flex items-center mt-1">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">${escapeHTML(data.activeEvent ? data.activeEvent.name : 'SCORING')}</span>
                                </div>
                            </div>
                            <div class="ml-4 text-right">
                                <div class="text-lg font-black text-slate-900 dark:text-white tracking-tighter">${performer.avg}</div>
                                <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">AV SCORE</div>
                            </div>
                        </div>
                        `;
                    }).join('');
                }

                // Update Judging Status
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
                                ` : ''}
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between items-end">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">${judge.scored}/${judge.total} ITEMS</span>
                                    <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">${Math.round(judge.progress)}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full ${judge.is_done ? 'bg-emerald-500' : 'bg-indigo-500'} transition-all duration-1000" style="width: ${judge.progress}%"></div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }

            } catch (error) {
                console.error('Auto-refresh failed:', error);
            }
        }

        setInterval(refreshDashboard, 5000);
    });
</script>
@endpush
