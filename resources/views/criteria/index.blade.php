@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Scoring Criteria</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium tracking-tight">
                @if($activeEvent)
                    Scoring rules for <span class="text-emerald-600 dark:text-emerald-400 font-black">{{ $activeEvent->name }}</span>
                @else
                    <span class="text-rose-500 font-black">No Active Event Selected</span>
                @endif
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-4">
            @if($securityEnabled)
            <div class="flex items-center px-4 py-3 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-slate-500 mr-3 uppercase tracking-widest">Safety Lock</span>
                <button type="button" id="safetyToggle" onclick="toggleSafety()" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                    <span id="toggleTrack" aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-5"></span>
                </button>
            </div>
            @endif
            @if($activeEvent)
            {{-- Import Button --}}
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden'); document.getElementById('importModal').classList.add('flex');"
                class="inline-flex items-center px-6 py-4 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all duration-300">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import Excel
            </button>
            <a href="{{ route('criteria.create', ['event_id' => $activeEvent->id]) }}" class="inline-flex items-center px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all duration-300 transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Criteria
            </a>
            @else
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-6 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Start an Event First
            </a>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Criteria Details</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Grades Configured</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Weight</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 text-right">Settings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($criteria as $criterion)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-center text-emerald-600 transition-transform group-hover:scale-110 duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 transition-colors">{{ $criterion->name }}</div>
                                        <p class="text-xs text-slate-500 line-clamp-1 max-w-xs mt-0.5">{{ $criterion->description ?: 'No description.' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($criterion->grades as $grade)
                                        <span class="inline-flex items-center px-3 py-1 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded-lg shadow-sm">
                                            {{ $grade->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                                        <div class="bg-emerald-500 h-full" style="width: 100%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">MAX</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('criteria.edit', $criterion) }}" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm border border-slate-100 dark:border-slate-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <div class="delete-action {{ $securityEnabled ? 'opacity-20 pointer-events-none' : '' }} transition-opacity duration-300">
                                        <button type="button" {{ $securityEnabled ? 'disabled' : '' }}
                                            onclick="openDeleteModal('{{ $criterion->id }}', '{{ addslashes($criterion->name) }}', '{{ route('criteria.destroy', $criterion) }}')"
                                            class="delete-btn p-2.5 rounded-xl bg-white dark:bg-slate-800 text-rose-400 hover:text-rose-600 dark:hover:text-rose-400 shadow-sm border border-slate-100 dark:border-slate-700 transition-all disabled:cursor-not-allowed">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center text-slate-300 mb-8 transform rotate-12">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">No criteria defined</h3>
                                    <p class="text-slate-500 dark:text-slate-400 max-w-xs mx-auto mt-2 font-medium">
                                        @if($activeEvent)
                                            Setup scoring rules for <span class="font-bold text-emerald-600">{{ $activeEvent->name }}</span>.
                                        @else
                                            You must <a href="{{ route('events.index') }}" class="text-indigo-600 underline font-black">start an event</a> before you can configure criteria.
                                        @endif
                                    </p>
                                    @if($activeEvent)
                                    <a href="{{ route('criteria.create', ['event_id' => $activeEvent->id]) }}" class="mt-8 px-8 py-4 bg-emerald-600 text-white font-black rounded-2xl shadow-xl shadow-emerald-200 dark:shadow-none hover:scale-105 transition-all">
                                        Create First Criteria
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Import Modal --}}
@if($activeEvent)
<div id="importModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="document.getElementById('importModal').classList.add('hidden'); document.getElementById('importModal').classList.remove('flex');"></div>
    <div class="relative bg-white dark:bg-slate-900 w-full max-w-lg rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Import Criteria</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Upload an Excel or CSV file to bulk-create criteria.</p>
                </div>
            </div>
        </div>
        <div class="p-8 space-y-6">
            {{-- Info Box --}}
            <div class="bg-slate-50 dark:bg-slate-800 rounded-2xl p-4 space-y-1.5">
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">File Format</p>
                <p class="text-sm text-slate-600 dark:text-slate-400">Required columns: <span class="font-bold text-emerald-600">name</span>, <span class="font-bold">description</span>, <span class="font-bold">weight</span>, <span class="font-bold text-emerald-600">grades</span></p>
                <p class="text-xs text-slate-400">Grades format: <code class="bg-white dark:bg-slate-700 px-1 rounded">Excellent:100|Good:80|Fair:60</code></p>
                <a href="{{ route('criteria.template') }}" class="inline-flex items-center mt-2 text-xs font-bold text-indigo-600 hover:text-indigo-700">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Template
                </a>
            </div>

            <form action="{{ route('criteria.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Select File</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="block w-full text-sm text-slate-600 dark:text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-950/30 dark:file:text-emerald-400 cursor-pointer">
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden'); document.getElementById('importModal').classList.remove('flex');"
                        class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-4 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 dark:shadow-none transition-all">
                        Import Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Passkey Modal --}}
<div id="passkeyModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closePasskeyModal()"></div>
    <div class="relative bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 text-center">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Security Check</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Administrative passkey required to disarm safety lock.</p>
        </div>
        <div class="p-8 space-y-6">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 text-center">Enter Passkey</label>
                <input type="password" id="passkeyInput" class="w-full bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-emerald-500 rounded-2xl p-4 text-center text-2xl font-black tracking-[0.5em] outline-none" placeholder="••••">
            </div>
            <div class="flex gap-4">
                <button type="button" onclick="closePasskeyModal()" class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors uppercase text-xs tracking-widest">Cancel</button>
                <button type="button" onclick="verifyPasskey()" class="flex-1 px-6 py-4 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 dark:shadow-none transition-all uppercase text-xs tracking-widest">Verify</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" onclick="closeDeleteModal()"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-100 dark:border-slate-800">
                <div class="p-10">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto bg-rose-50 dark:bg-rose-900/20 rounded-3xl mb-8">
                        <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>

                    <div class="text-center space-y-4">
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Destructive Action</h3>
                        <p class="text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                            You are about to delete <span id="criterionNameDisplay" class="text-rose-600 font-black underline underline-offset-4"></span>. 
                            This will permanently remove all associated scores and rankings. This action cannot be undone.
                        </p>
                    </div>

                    <div class="mt-10 space-y-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Type the name to confirm</label>
                            <input type="text" id="deleteConfirmInput" 
                                class="block w-full px-6 py-4 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all outline-none font-bold text-center"
                                placeholder="Type name here...">
                        </div>

                        <form id="deleteForm" method="POST" class="flex flex-col sm:flex-row gap-4">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="closeDeleteModal()" 
                                class="flex-1 px-8 py-5 rounded-[2rem] text-sm font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                                CANCEL
                            </button>
                            <button type="submit" id="confirmDeleteBtn" disabled
                                class="flex-[2] bg-rose-600 hover:bg-rose-700 disabled:opacity-30 disabled:cursor-not-allowed text-white font-extrabold py-5 rounded-[2rem] shadow-xl shadow-rose-200 dark:shadow-none transition-all duration-300 transform active:scale-[0.98]">
                                CONFIRM DELETION
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let locked = {{ $securityEnabled ? 'true' : 'false' }};
    const systemPasskey = "{{ $passkey }}";
    let currentCriterionName = '';
    
    const deleteModal = document.getElementById('deleteModal');
    const deleteConfirmInput = document.getElementById('deleteConfirmInput');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const criterionNameDisplay = document.getElementById('criterionNameDisplay');
    const deleteForm = document.getElementById('deleteForm');

    const passkeyModal = document.getElementById('passkeyModal');
    const passkeyInput = document.getElementById('passkeyInput');

    function toggleSafety() {
        if (locked) {
            // Trying to unlock
            openPasskeyModal();
        } else {
            // Turning lock back ON doesn't need passkey
            applySafetyToggle();
        }
    }

    function openPasskeyModal() {
        passkeyModal.classList.remove('hidden');
        passkeyModal.classList.add('flex');
        passkeyInput.value = '';
        setTimeout(() => passkeyInput.focus(), 100);
    }

    function closePasskeyModal() {
        passkeyModal.classList.add('hidden');
        passkeyModal.classList.remove('flex');
    }

    function verifyPasskey() {
        if (passkeyInput.value === systemPasskey) {
            applySafetyToggle();
            closePasskeyModal();
        } else {
            alert("Invalid passkey. Access denied.");
            passkeyInput.value = '';
            passkeyInput.focus();
        }
    }

    function applySafetyToggle() {
        locked = !locked;
        const track = document.getElementById('toggleTrack');
        const toggle = document.getElementById('safetyToggle');
        const deleteContainers = document.querySelectorAll('.delete-action');
        const deleteButtons = document.querySelectorAll('.delete-btn');

        if (locked) {
            track.classList.add('translate-x-5');
            track.classList.remove('translate-x-0');
            toggle.classList.add('bg-emerald-600');
            toggle.classList.remove('bg-slate-200', 'dark:bg-slate-700');
            deleteContainers.forEach(el => el.classList.add('opacity-20', 'pointer-events-none'));
            deleteButtons.forEach(btn => btn.disabled = true);
        } else {
            track.classList.remove('translate-x-5');
            track.classList.add('translate-x-0');
            toggle.classList.remove('bg-emerald-600');
            toggle.classList.add('bg-slate-200', 'dark:bg-slate-700');
            deleteContainers.forEach(el => el.classList.remove('opacity-20', 'pointer-events-none'));
            deleteButtons.forEach(btn => btn.disabled = false);
        }
    }

    function openDeleteModal(id, name, url) {
        currentCriterionName = name;
        criterionNameDisplay.textContent = name;
        deleteForm.action = url;
        deleteConfirmInput.value = '';
        confirmDeleteBtn.disabled = true;
        deleteModal.classList.remove('hidden');
        setTimeout(() => deleteConfirmInput.focus(), 100);
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
    }

    deleteConfirmInput.addEventListener('input', (e) => {
        if (e.target.value === currentCriterionName) {
            confirmDeleteBtn.disabled = false;
        } else {
            confirmDeleteBtn.disabled = true;
        }
    });

    // Handle Enter key in passkey input
    passkeyInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') verifyPasskey();
        if (e.key === 'Escape') closePasskeyModal();
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endpush
@endsection
