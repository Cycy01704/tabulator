@extends('layouts.landing')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8">
    <!-- Navigation -->
    <nav class="flex items-center justify-between py-10">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-xl shadow-indigo-200 dark:shadow-none">
                T
            </div>
            <span class="text-2xl font-black tracking-tight text-slate-900 dark:text-white uppercase">Tabulator</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('audience.leaderboard') }}" class="text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Public Board</a>
            @auth
                <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition-all uppercase tracking-widest">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition-all uppercase tracking-widest">Sign In</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-20 pb-32 text-center relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-indigo-500/10 blur-[120px] rounded-full -z-10"></div>
        
        <h1 class="text-6xl md:text-8xl font-black text-slate-900 dark:text-white tracking-tighter leading-none mb-8">
            Precision Scoring for <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-emerald-500">Elite Events.</span>
        </h1>
        
        <p class="max-w-2xl mx-auto text-xl text-slate-500 dark:text-slate-400 font-medium leading-relaxed mb-12">
            The ultimate tabulation system designed for accuracy, speed, and transparency. 
            Empower your judges and delight your audience with real-time results.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-indigo-600 text-white rounded-3xl font-black text-lg shadow-2xl shadow-indigo-300 dark:shadow-none hover:bg-indigo-700 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                Start Tabulating
            </a>
            <a href="{{ route('audience.leaderboard') }}" class="w-full sm:w-auto px-10 py-5 bg-white dark:bg-slate-900 text-slate-900 dark:text-white border-2 border-slate-100 dark:border-slate-800 rounded-3xl font-black text-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                Live Results
            </a>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-32">
        <!-- Feature 01 -->
        <div class="p-10 bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none group hover:border-indigo-500/50 transition-all">
            <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600 mb-8 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Real-Time Sync</h3>
            <p class="text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Scores are tabulated instantly as judges submit, keeping your leaderboard alive and accurate.</p>
        </div>

        <!-- Feature 02 -->
        <div class="p-10 bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none group hover:border-emerald-500/50 transition-all">
            <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600 mb-8 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Dual Tabulation</h3>
            <p class="text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Switch between Normal Average and Weighted Average formulas with a single clinical setting.</p>
        </div>

        <!-- Feature 03 -->
        <div class="p-10 bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none group hover:border-purple-500/50 transition-all">
            <div class="w-14 h-14 bg-purple-50 dark:bg-purple-950/30 rounded-2xl flex items-center justify-center text-purple-600 mb-8 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Secure & Private</h3>
            <p class="text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Multilayer security ensures results are only revealed when the committee is ready.</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-12 border-t border-slate-100 dark:border-slate-900 flex flex-col md:flex-row items-center justify-between gap-6">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} {{ \App\Models\Setting::getValue('event_name', 'Tabulator') }}. Built for reliability.</p>
        <div class="flex items-center space-x-8">
            <a href="#" class="text-xs font-black text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Terms</a>
            <a href="#" class="text-xs font-black text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Privacy</a>
            <a href="{{ route('login') }}" class="text-xs font-black text-indigo-600 hover:indigo-700 transition-colors uppercase tracking-widest px-4 py-2 bg-indigo-50 dark:bg-indigo-950/30 rounded-lg">Admin Login</a>
        </div>
    </footer>
</div>
@endsection
