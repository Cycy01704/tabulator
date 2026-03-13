<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tabulator</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: url('{{ asset('assets/images/login-bg.png') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, rgba(30, 27, 75, 0.4) 0%, rgba(15, 12, 41, 0.8) 100%);
            z-index: -1;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(24px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), inset 0 0 0 1px rgba(255, 255, 255, 0.1) !important;
        }
        .dark .glass-card {
            background: rgba(15, 23, 42, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-xl rounded-3xl mb-4 border border-white/30 shadow-2xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white tracking-tight">Tabulator</h1>
            <p class="text-white/70 mt-2">Judging Management System</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-[2.5rem] p-10">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8 tracking-tight">Welcome Back</h2>

            @if ($errors->any())
                <div class="mb-6 p-5 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-600 dark:text-red-400 text-sm font-medium backdrop-blur-md">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div class="group space-y-2">
                    <label for="email" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1 transition-colors group-focus-within:text-indigo-600">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                            class="w-full px-6 py-4 rounded-2xl border border-transparent bg-white/50 focus:bg-white dark:bg-slate-900/50 dark:focus:bg-slate-900 text-gray-900 dark:text-white font-semibold shadow-inner focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 outline-none"
                            placeholder="judge@tabulator.com">
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group space-y-2">
                    <label for="password" class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1 transition-colors group-focus-within:text-indigo-600">Security Key</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-6 py-4 rounded-2xl border border-transparent bg-white/50 focus:bg-white dark:bg-slate-900/50 dark:focus:bg-slate-900 text-gray-900 dark:text-white font-semibold shadow-inner focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 outline-none"
                            placeholder="••••••••">
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-900 dark:bg-indigo-600 hover:bg-black dark:hover:bg-indigo-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98] uppercase tracking-widest text-xs">
                        Enter Workspace
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-200/30">
                <p class="text-center text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                    Authorized Access Only
                </p>
            </div>
        </div>
    </div>
</body>
</html>
