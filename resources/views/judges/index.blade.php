@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">System Judges</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">Manage the official judging panel.</p>
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
            <a href="{{ route('judges.create') }}" class="inline-flex items-center px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-300 transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Judge
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Judge Profile</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">Identity</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($judges as $judge)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-50 to-indigo-100 dark:from-indigo-900/40 dark:to-indigo-800/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm shadow-sm group-hover:from-indigo-500 group-hover:to-purple-500 group-hover:text-white transition-all duration-300">
                                        {{ strtoupper(substr($judge->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $judge->name }}</div>
                                        <p class="text-xs text-slate-400 font-medium tracking-tight">Active Evaluator</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-300 tracking-tight">{{ $judge->email }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('judges.edit', $judge) }}" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm border border-slate-100 dark:border-slate-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <div class="delete-action {{ $securityEnabled ? 'opacity-20 pointer-events-none' : '' }} transition-opacity duration-300">
                                        <button type="button" {{ $securityEnabled ? 'disabled' : '' }} 
                                            onclick="openDeleteModal('{{ $judge->id }}', '{{ addslashes($judge->name) }}', '{{ route('judges.destroy', $judge) }}')"
                                            class="delete-btn p-2.5 rounded-xl bg-white dark:bg-slate-800 text-rose-400 hover:text-rose-600 dark:hover:text-rose-400 shadow-sm border border-slate-100 dark:border-slate-700 transition-all disabled:cursor-not-allowed">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($judges->hasPages())
            <div class="p-8 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
                {{ $judges->links() }}
            </div>
        @endif
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
                            You are about to delete <span id="judgeNameDisplay" class="text-rose-600 font-black underline underline-offset-4"></span>. 
                            This will permanently remove the judge's account and all associated evaluations. This action cannot be undone.
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
    let currentJudgeName = '';
    
    const passkeyModal = document.getElementById('passkeyModal');
    const passkeyInput = document.getElementById('passkeyInput');

    const deleteModal = document.getElementById('deleteModal');
    const deleteConfirmInput = document.getElementById('deleteConfirmInput');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const judgeNameDisplay = document.getElementById('judgeNameDisplay');
    const deleteForm = document.getElementById('deleteForm');

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
        currentJudgeName = name;
        judgeNameDisplay.textContent = name;
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
        if (e.target.value === currentJudgeName) {
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
