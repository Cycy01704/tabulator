@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Contestants</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium tracking-tight">
                @if($activeEvent)
                    Managing participants for <span class="text-indigo-600 dark:text-indigo-400 font-black">{{ $activeEvent->name }}</span>
                @else
                    <span class="text-rose-500 font-black">No Active Event Selected</span>
                @endif
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-4">
            @if($securityEnabled)
            <div class="flex items-center px-4 py-3 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-slate-500 mr-3 uppercase tracking-widest">Safety Lock</span>
                <button type="button" id="safetyToggle" onclick="toggleSafety()" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                    <span id="toggleTrack" aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-5"></span>
                </button>
            </div>
            @endif
            @if($activeEvent)
            <button type="button" onclick="openImportModal()" class="inline-flex items-center px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all duration-300 transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Import Excel
            </button>
            <a href="{{ route('contestants.create', ['event_id' => $activeEvent->id]) }}" class="inline-flex items-center px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-300 transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Contestant
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
                        @if(($settings['field_contestant_number'] ?? '1') == '1')
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">#</th>
                        @endif
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Identity</th>
                        @if(($settings['field_contestant_age'] ?? '0') == '1')
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Age</th>
                        @endif
                        @if(($settings['field_contestant_address'] ?? '0') == '1')
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Address</th>
                        @endif
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 text-right">Settings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($contestants as $contestant)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            @if(($settings['field_contestant_number'] ?? '1') == '1')
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white font-bold text-sm shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                    {{ $contestant->number }}
                                </span>
                            </td>
                            @endif
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if(($settings['field_contestant_image'] ?? '1') == '1')
                                    <div class="w-12 h-12 flex-shrink-0 rounded-2xl overflow-hidden shadow-sm border-2 border-white dark:border-slate-700 transition-transform group-hover:scale-105 duration-300">
                                        @if($contestant->image_path)
                                            <img src="{{ asset('storage/' . $contestant->image_path) }}" alt="{{ $contestant->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="{{ ($settings['field_contestant_image'] ?? '1') == '1' ? 'ml-4' : '' }}">
                                        <div class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $contestant->name }}</div>
                                        @if(($settings['field_contestant_number'] ?? '1') == '1')
                                        <div class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-0.5">Contestant #{{ $contestant->number }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @if(($settings['field_contestant_age'] ?? '0') == '1')
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $contestant->age ?: 'N/A' }}</span>
                            </td>
                            @endif
                            @if(($settings['field_contestant_address'] ?? '0') == '1')
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $contestant->address ?: 'N/A' }}</span>
                            </td>
                            @endif
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('contestants.edit', $contestant) }}" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm border border-slate-100 dark:border-slate-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <div class="delete-action {{ $securityEnabled ? 'opacity-20 pointer-events-none' : '' }} transition-opacity duration-300">
                                        <button type="button" {{ $securityEnabled ? 'disabled' : '' }}
                                            onclick="openDeleteModal('{{ $contestant->id }}', '{{ addslashes($contestant->name) }}', '{{ route('contestants.destroy', $contestant) }}')"
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
                            <td colspan="10" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center text-slate-300 mb-8 transform -rotate-12">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">No contestants found</h3>
                                    <p class="text-slate-500 dark:text-slate-400 max-w-xs mx-auto mt-2 font-medium">
                                        @if($activeEvent)
                                            Start by adding participants for <span class="font-bold text-indigo-600">{{ $activeEvent->name }}</span>.
                                        @else
                                            You must <a href="{{ route('events.index') }}" class="text-indigo-600 underline font-black">start an event</a> before you can manage contestants.
                                        @endif
                                    </p>
                                    @if($activeEvent)
                                    <a href="{{ route('contestants.create', ['event_id' => $activeEvent->id]) }}" class="mt-8 px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none hover:scale-105 transition-all">
                                        Add First Contestant
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($contestants->hasPages())
            <div class="p-8 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
                {{ $contestants->links() }}
            </div>
        @endif
    </div>
</div>
    </div>
</div>

{{-- Passkey Modal --}}
<div id="passkeyModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closePasskeyModal()"></div>
    <div class="relative bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 text-center">
            <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mx-auto mb-4">
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
                <input type="password" id="passkeyInput" class="w-full bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 text-center text-2xl font-black tracking-[0.5em] outline-none" placeholder="••••">
            </div>
            <div class="flex gap-4">
                <button type="button" onclick="closePasskeyModal()" class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors uppercase text-xs tracking-widest">Cancel</button>
                <button type="button" onclick="verifyPasskey()" class="flex-1 px-6 py-4 rounded-2xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 dark:shadow-none transition-all uppercase text-xs tracking-widest">Verify</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[120] hidden">
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
                            You are about to delete <span id="contestantNameDisplay" class="text-rose-600 font-black underline underline-offset-4"></span>. 
                            This will permanently remove the participant and all their associated scores. This action cannot be undone.
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

{{-- Import Excel Modal --}}
@if($activeEvent)
<div id="importModal" class="fixed inset-0 z-[130] hidden items-center justify-center p-6">
    <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeImportModal()"></div>
    <div class="relative bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('contestants.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 text-center">
                <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m-4 4l-4-4m4 4l4-4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Import Contestants</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Upload an Excel file (.xlsx, .xls) or CSV.</p>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Choose File</label>
                    <input type="file" name="file" accept=".xlsx, .xls, .csv" required class="w-full bg-slate-50 dark:bg-slate-800 border-none focus:ring-2 focus:ring-emerald-500 rounded-2xl p-4 text-sm font-bold text-slate-700 dark:text-slate-300 outline-none">
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-950/20 p-4 rounded-2xl">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Required Format:</p>
                        <a href="{{ route('contestants.template') }}" class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Template
                        </a>
                    </div>
                    <p class="text-xs text-indigo-500/80 dark:text-indigo-400/80 font-medium">Header row with: <span class="font-black text-indigo-600">name</span>, <span class="font-black text-indigo-600">number</span>, <span class="font-black">age</span>, <span class="font-black">address</span>, <span class="font-black">gender</span>, <span class="font-black">dob</span>, <span class="font-black">occupation</span>, <span class="font-black">contact_number</span>, <span class="font-black">email</span>, <span class="font-black">hobbies</span>, <span class="font-black">motto</span>, and <span class="font-black">description</span>.</p>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeImportModal()" class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors uppercase text-xs tracking-widest">Cancel</button>
                    <button type="submit" class="flex-1 px-6 py-4 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 dark:shadow-none transition-all uppercase text-xs tracking-widest">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
    let locked = {{ $securityEnabled ? 'true' : 'false' }};
    const systemPasskey = "{{ $passkey }}";
    let currentContestantName = '';
    
    const passkeyModal = document.getElementById('passkeyModal');
    const passkeyInput = document.getElementById('passkeyInput');

    const deleteModal = document.getElementById('deleteModal');
    const deleteConfirmInput = document.getElementById('deleteConfirmInput');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const contestantNameDisplay = document.getElementById('contestantNameDisplay');
    const deleteForm = document.getElementById('deleteForm');

    const importModal = document.getElementById('importModal');

    function openImportModal() {
        if(importModal) {
            importModal.classList.remove('hidden');
            importModal.classList.add('flex');
        }
    }

    function closeImportModal() {
        if(importModal) {
            importModal.classList.add('hidden');
            importModal.classList.remove('flex');
        }
    }

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
            toggle.classList.add('bg-indigo-600');
            toggle.classList.remove('bg-slate-200', 'dark:bg-slate-700');
            deleteContainers.forEach(el => el.classList.add('opacity-20', 'pointer-events-none'));
            deleteButtons.forEach(btn => btn.disabled = true);
        } else {
            track.classList.remove('translate-x-5');
            track.classList.add('translate-x-0');
            toggle.classList.remove('bg-indigo-600');
            toggle.classList.add('bg-slate-200', 'dark:bg-slate-700');
            deleteContainers.forEach(el => el.classList.remove('opacity-20', 'pointer-events-none'));
            deleteButtons.forEach(btn => btn.disabled = false);
        }
    }

    function openDeleteModal(id, name, url) {
        currentContestantName = name;
        contestantNameDisplay.textContent = name;
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
        if (e.target.value === currentContestantName) {
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
        if (e.key === 'Escape') {
            closePasskeyModal();
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection
