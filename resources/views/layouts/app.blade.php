<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::getValue('event_name', 'Tabulator') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }
        .sidebar-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.02);
        }
        .dark .sidebar-glass {
            background: rgba(15, 23, 42, 0.8);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        .nav-link-active {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }
        .transition-all-custom {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
        
        /* Sonner-style Toast Overrides */
        .toastify {
            background: white !important;
            color: #0f172a !important;
            padding: 16px 24px !important;
            border-radius: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid #f1f5f9 !important;
            bottom: 30px !important;
            right: 30px !important;
            opacity: 1 !important;
            left: auto !important;
            max-width: 380px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        .dark .toastify {
            background: #0f172a !important;
            color: white !important;
            border: 1px solid #1e293b !important;
        }
        .toastify.toastify-success {
            border-left: 4px solid #10b981 !important;
        }
        .toastify.toastify-error {
            border-left: 4px solid #ef4444 !important;
        }
    </style>
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-glass w-64 flex-shrink-0 hidden md:flex flex-col transition-all duration-300 ease-in-out">
            <div class="p-6 flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">
                        T
                    </div>
                    <span class="text-xl font-bold tracking-tight">Tabulator</span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-8 space-y-3">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->is('dashboard')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->isJudge())
                <a href="{{ route('scoring.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('scoring.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Scoring
                </a>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isCommittee())
                    <div class="px-4 mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Evaluation Team</span>
                    </div>

                    <a href="{{ route('judges.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('judges.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Judges
                    </a>

                    <div class="h-4"></div>
                    <div class="px-4 mb-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Management</span>
                    </div>

                    <a href="{{ route('events.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('events.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Event Calendar
                    </a>

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('users.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Staff Accounts
                    </a>
                    @endif

                    <a href="{{ route('contestants.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('contestants.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Contestants
                    </a>

                    <a href="{{ route('criteria.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('criteria.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 0-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Criteria
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('reports.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Reports
                    </a>

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('settings.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>

                    <a href="{{ route('archives.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all-custom group @if(request()->routeIs('archives.*')) nav-link-active @else text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        Archives
                    </a>
                    @endif
                @endif
            </nav>

            <!-- User Info / Logout -->
            <div class="p-6 border-t border-slate-100 dark:border-slate-900/50 bg-slate-50/50 dark:bg-slate-900/20">
                <div class="flex items-center mb-6">
                    <img class="w-10 h-10 rounded-2xl border-2 border-white dark:border-slate-800 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&bold=true&color=4F46E5&background=EEF2FF" alt="User avatar">
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-bold truncate text-slate-800 dark:text-slate-100">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-semibold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-2xl transition-all-custom group">
                        <svg class="w-5 h-5 mr-3 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <!-- Header -->
            <header class="sticky top-0 z-10 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl h-20 flex items-center justify-between px-10 border-b border-slate-100 dark:border-slate-900/50 shadow-sm">
                <div class="flex items-center flex-1">
                    <button id="sidebar-toggle" class="p-2 mr-4 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-900 md:hidden transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                    
                    <div class="hidden md:flex items-center text-sm text-slate-400">
                        <span>Tabulator</span>
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-slate-900 dark:text-slate-100 font-semibold">{{ ucfirst(request()->path()) }}</span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-6">
                    
                    <div class="w-px h-6 bg-slate-200 dark:bg-slate-800"></div>
                    
                    <div class="flex items-center">
                        <div class="text-right mr-3 hidden sm:block">
                            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs shadow-lg shadow-indigo-200 dark:shadow-none">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="py-6 px-8">
                @yield('content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('flex');
            });
        }

        // Sonner-style Toast Handler
        function showToast(message, type = 'success') {
            Toastify({
                text: message,
                duration: 4000,
                close: true,
                gravity: "bottom",
                position: "right",
                className: "toastify-" + type,
                stopOnFocus: true,
            }).showToast();
        }

        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif
    </script>
    <script>
        let _deleteFormId = null;

        function confirmDelete(formId, itemName, itemType) {
            _deleteFormId = formId;
            document.getElementById('deleteModalName').textContent = itemName || 'this item';
            document.getElementById('deleteModalType').textContent = itemType || 'Item';
            const modal = document.getElementById('deleteModal');
            modal.style.display = '';
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            _deleteFormId = null;
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        function submitDeleteModal() {
            if (_deleteFormId) {
                document.getElementById(_deleteFormId).submit();
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
    @stack('scripts')

    {{-- Universal Delete Confirmation Modal --}}
    <div id="deleteModal" style="display:none" class="fixed inset-0 z-[200] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all w-full max-w-sm border border-slate-100 dark:border-slate-800">
                <div class="p-10 text-center">
                    <div class="w-16 h-16 bg-rose-50 dark:bg-rose-950/30 rounded-[2rem] flex items-center justify-center text-rose-500 mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Delete <span id="deleteModalType">Item</span>?</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-8">
                        You are about to permanently remove <span id="deleteModalName" class="font-bold text-rose-500"></span>. This action cannot be undone.
                    </p>
                    <div class="flex flex-col gap-3">
                        <button id="deleteModalConfirm" onclick="submitDeleteModal()" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black transition-all shadow-lg shadow-rose-200 dark:shadow-none uppercase tracking-widest text-xs">
                            Yes, Delete
                        </button>
                        <button onclick="closeDeleteModal()" class="w-full py-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
