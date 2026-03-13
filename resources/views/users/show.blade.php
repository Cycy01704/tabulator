@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">User Details</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Viewing information for {{ $user->name }}.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-8 space-y-6">
            <div class="flex items-center space-x-6">
                <div class="w-20 h-20 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-2xl">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Account Created</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Updated</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div class="pt-6 flex space-x-4">
                <a href="{{ route('users.edit', $user) }}" class="flex-1 flex justify-center items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                    Edit User
                </a>
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex justify-center items-center px-6 py-3 bg-white dark:bg-gray-900 border border-red-600 rounded-xl font-semibold text-sm text-red-600 uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-900/20 transition ease-in-out duration-150">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
