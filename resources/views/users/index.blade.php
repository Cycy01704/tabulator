@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Staff Management</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">Control administrative permissions for Committee and Admins.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-300 transform hover:-translate-y-1">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Staff Member
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">User Profile</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Access Role</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 text-right">Settings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 font-bold text-sm shadow-sm group-hover:from-indigo-500 group-hover:to-purple-500 group-hover:text-white transition-all duration-300">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $user->name }}</div>
                                        <p class="text-xs text-slate-400 font-medium tracking-tight">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest {{ $user->isAdmin() ? 'bg-rose-50 text-rose-600 dark:bg-rose-900/30' : ($user->isCommittee() ? 'bg-purple-50 text-purple-600 dark:bg-purple-900/30' : 'bg-slate-50 text-slate-600 dark:bg-slate-900/30') }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $user->isAdmin() ? 'bg-rose-500' : ($user->isCommittee() ? 'bg-purple-500' : 'bg-slate-500') }}"></span>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('users.edit', $user) }}" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm border border-slate-100 dark:border-slate-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Archive this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 shadow-sm border border-slate-100 dark:border-slate-700 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="p-8 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
