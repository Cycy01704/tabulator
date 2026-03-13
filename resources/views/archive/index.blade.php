@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Event Archives</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">Historical data from past competitions.</p>
        </div>
        <div class="bg-indigo-50 dark:bg-indigo-950/30 p-2 px-4 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $archives->count() }} Archives Found</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Event Name</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Conclusion Date</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Data Points</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($archives as $archive)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $archive->event_name }}</span>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500 dark:text-slate-400 font-medium">
                            {{ $archive->event_date->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold rounded-md">{{ count($archive->data['contestants'] ?? []) }} C</span>
                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold rounded-md">{{ count($archive->data['scores'] ?? []) }} S</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('archives.show', $archive->id) }}" class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-600 hover:text-white transition-all" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('archives.download', $archive->id) }}" class="p-2 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-600 hover:text-white transition-all" title="Download">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ $archive->id }}', '{{ addslashes($archive->event_name) }}')" class="p-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">No archives yet</h3>
                                <p class="text-slate-500 dark:text-slate-400 max-w-xs mx-auto mt-2">Finish an event to see it listed here in the archives.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Delete Archive Security Modal --}}
<div id="deleteArchiveModal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeDeleteModal()"></div>
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100 dark:border-slate-800">
            <div class="p-10">
                <div class="flex items-center justify-center mb-8">
                    <div class="w-16 h-16 bg-rose-50 dark:bg-rose-950/30 rounded-2xl flex items-center justify-center text-rose-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 dark:text-white text-center tracking-tight mb-2">Delete Archive</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-8 px-4 leading-relaxed">You are about to permanently delete the archive for <span id="deleteArchiveName" class="text-rose-600 font-black"></span>. This action cannot be undone.</p>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">System Passkey</label>
                        <input type="password" id="deletePasskey" placeholder="Enter system passkey" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-rose-500 rounded-2xl outline-none text-center font-bold tracking-[0.5em] transition-all duration-300">
                    </div>
                    
                    <div class="flex flex-col gap-3 pt-4">
                        <button onclick="submitDelete()" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black transition-all shadow-lg shadow-rose-200 dark:shadow-none uppercase tracking-widest text-xs">
                            Verify & Delete
                        </button>
                        <button onclick="closeDeleteModal()" class="w-full py-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const deleteModal = document.getElementById('deleteArchiveModal');
    const deletePasskeyInput = document.getElementById('deletePasskey');
    const deleteNameLabel = document.getElementById('deleteArchiveName');
    let deleteArchiveId = null;

    function openDeleteModal(id, name) {
        deleteArchiveId = id;
        deleteNameLabel.innerText = name;
        deletePasskeyInput.value = '';
        deleteModal.classList.remove('hidden');
        deletePasskeyInput.focus();
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deletePasskeyInput.value = '';
        deleteArchiveId = null;
    }

    function submitDelete() {
        const passkey = deletePasskeyInput.value;
        if (!passkey) {
            showToast("Passkey is required to delete this archive.", "error");
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('archives.destroy', ':id') }}".replace(':id', deleteArchiveId);

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = "{{ csrf_token() }}";

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        const passkeyField = document.createElement('input');
        passkeyField.type = 'hidden';
        passkeyField.name = 'passkey';
        passkeyField.value = passkey;

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(passkeyField);
        document.body.appendChild(form);
        form.submit();
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endpush
