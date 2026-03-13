@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-10">
    <div class="flex items-center space-x-6">
        <a href="{{ route('users.index') }}" class="w-12 h-12 bg-white dark:bg-slate-900 flex items-center justify-center rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 transition-all shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Add Staff User</h1>
            <p class="text-slate-500 font-medium">Create a new account for system administrators or committee members.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('users.store') }}" method="POST" class="p-10 space-y-8">
            @csrf

            <div class="space-y-3">
                <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1 uppercase tracking-widest">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="block w-full px-6 py-4 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none" placeholder="e.g. John Doe">
                @error('name') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-3">
                <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1 uppercase tracking-widest">Email Identity</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="block w-full px-6 py-4 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none" placeholder="name@example.com">
                @error('email') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label for="role" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1 uppercase tracking-widest">System Role</label>
                    <select name="role" id="role" required
                        class="block w-full px-6 py-4 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none appearance-none">
                        <option value="committee" selected>Committee (Management Access)</option>
                        <option value="admin">System Admin (Full Authorization)</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1 uppercase tracking-widest">Security Pin/Pass</label>
                    <input type="password" name="password" id="password" required
                        class="block w-full px-6 py-4 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none" placeholder="••••••••">
                    @error('password') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-5 rounded-[2rem] shadow-xl shadow-indigo-200 dark:shadow-none transition-all duration-300 transform active:scale-[0.98]">
                    GENERATE ACCOUNT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
