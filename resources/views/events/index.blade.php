@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Event Management</h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">Create and manage your competition lifecycle.</p>
        </div>
        <button onclick="showModal('addEventModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-indigo-200 dark:shadow-none flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Event
        </button>
    </div>

    @php $activeEvent = \App\Models\Event::current(); @endphp
    @if($activeEvent)
    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
        <div class="relative z-10">
            <div class="flex items-center space-x-3 mb-4">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-black uppercase tracking-widest text-indigo-100">Currently Active Event</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black tracking-tight">{{ $activeEvent->name }}</h2>
                    <p class="mt-2 text-indigo-100 font-medium tracking-tight">Started on {{ $activeEvent->started_at->format('M d, Y @ h:i A') }}</p>
                </div>
                <button onclick="confirmConclude()" class="px-8 py-4 bg-rose-500 hover:bg-rose-600 text-white font-black rounded-2xl shadow-xl shadow-rose-900/20 transition-all transform hover:scale-105 flex items-center group">
                    <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Conclude Event
                </button>
            </div>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 -mr-10 -mb-10">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Event Name</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Status</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">Expected Date</th>
                        <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($events as $event)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $event->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            @if($event->status === 'active')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest">Active</span>
                            @elseif($event->status === 'pending')
                                <span class="px-3 py-1 bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 rounded-full text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 rounded-full text-[10px] font-black uppercase tracking-widest">Concluded</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500 dark:text-slate-400 font-medium">
                            @if($event->expected_at)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ $event->expected_at->format('M d, Y') }}</span>
                                </div>
                            @else
                                <span class="text-slate-300 dark:text-slate-600 italic text-xs">Not set</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                @if($event->status !== 'concluded')
                                    <a href="{{ route('contestants.index', ['event_id' => $event->id]) }}" class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-600 hover:text-white transition-all group/icon relative" title="Manage Contestants">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </a>
                                    <a href="{{ route('criteria.index', ['event_id' => $event->id]) }}" class="p-2 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-600 hover:text-white transition-all group/icon relative" title="Manage Criteria">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </a>
                                @endif
                                @if($event->status === 'pending')
                                    <button type="button" onclick="openStartModal('{{ $event->id }}', '{{ addslashes($event->name) }}')" class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-emerald-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                    <form id="delete-event-{{ $event->id }}" action="{{ route('events.destroy', $event) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDelete('delete-event-{{ $event->id }}', '{{ addslashes($event->name) }}', 'Event')" class="p-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                                @if($event->status === 'active')
                                    <span class="text-[10px] font-black text-emerald-500 uppercase">Running</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">No events found</h3>
                                <p class="text-slate-500 dark:text-slate-400 max-w-xs mx-auto mt-2">Add your first event to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Start Event Modal --}}
<div id="startEventModal" class="fixed inset-0 z-[105] hidden overflow-y-auto" style="display:none" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeStartModal()"></div>
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100 dark:border-slate-800">
            <div class="p-10 text-center">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-950/30 rounded-[2rem] flex items-center justify-center text-emerald-600 mx-auto mb-6 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Activate Event</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 px-6 leading-relaxed">You are about to start <span id="startEventTitle" class="text-emerald-600 font-bold"></span>. This will set it as the active competition for the entire system.</p>
                
                <form id="startEventForm" method="POST" class="space-y-4">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black transition-all shadow-xl shadow-emerald-200 dark:shadow-none uppercase tracking-widest text-xs">
                        Start Competition Now
                    </button>
                    <button type="button" onclick="closeStartModal()" class="w-full py-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold transition-colors">
                        Maybe Later
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add Event Modal --}}
<div id="addEventModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" style="display:none" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" onclick="document.getElementById('addEventModal').classList.add('hidden')"></div>
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <form action="{{ route('events.store') }}" method="POST" class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100 dark:border-slate-800">
            @csrf
            <div class="p-10">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-6">New Competition</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Event Name</label>
                        <input type="text" name="name" required placeholder="e.g., Annual Pageant 2026" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-2xl outline-none font-bold transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Expected Commencement Date</label>
                        <input type="date" name="expected_at" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-2xl outline-none font-bold transition-all duration-300 text-slate-700 dark:text-slate-300">
                        <p class="text-[10px] text-slate-400 mt-1 px-1">Optional — the planned start date of the competition.</p>
                    </div>
                    <div class="flex flex-col gap-3 pt-4">
                        <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
                            Create Event
                        </button>
                        <button type="button" onclick="hideModal('addEventModal')" class="w-full py-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- Custom Passkey Modal for Conclusion --}}
<div id="archiveModal" class="fixed inset-0 z-[110] hidden overflow-y-auto" style="display:none" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeArchiveModal()"></div>
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100 dark:border-slate-800">
            <div class="p-10">
                <div class="flex items-center justify-center mb-8">
                    <div class="w-16 h-16 bg-rose-50 dark:bg-rose-950/30 rounded-2xl flex items-center justify-center text-rose-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 dark:text-white text-center tracking-tight mb-2">Security Verification</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-8 px-4 leading-relaxed">This will conclude <span class="text-rose-600 font-black">{{ $activeEvent->name ?? 'the event' }}</span>, archive all data, and allow a new event to start.</p>

                <div class="space-y-6">
                    <div>
                        <input type="password" id="modalPasskey" placeholder="Enter system passkey" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-rose-500 rounded-2xl outline-none text-center font-bold tracking-[0.5em] transition-all duration-300">
                    </div>
                    
                    <div class="flex flex-col gap-3 pt-4">
                        <button onclick="submitArchive()" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-rose-200 dark:shadow-none">
                            Verify & Conclude
                        </button>
                        <button onclick="closeArchiveModal()" class="w-full py-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('archiveModal');
    const passkeyInput = document.getElementById('modalPasskey');
    const startModal = document.getElementById('startEventModal');
    const startForm = document.getElementById('startEventForm');
    const startTitle = document.getElementById('startEventTitle');

    function showModal(id) {
        const el = document.getElementById(id);
        el.style.display = '';
        el.classList.remove('hidden');
    }

    function hideModal(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.style.display = 'none';
    }

    function openStartModal(id, name) {
        startTitle.innerText = name;
        startForm.action = "{{ route('events.start', ':id') }}".replace(':id', id);
        showModal('startEventModal');
    }

    function closeStartModal() {
        hideModal('startEventModal');
    }

    function confirmConclude() {
        showModal('archiveModal');
        passkeyInput.focus();
    }

    function closeArchiveModal() {
        hideModal('archiveModal');
        passkeyInput.value = '';
    }

    function submitArchive() {
        const passkey = passkeyInput.value;
        
        if (!passkey) {
            showToast("Passkey is required to conclude the event.", "error");
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('archives.store') }}";
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = "{{ csrf_token() }}";

        const passkeyField = document.createElement('input');
        passkeyField.type = 'hidden';
        passkeyField.name = 'passkey';
        passkeyField.value = passkey;
        
        form.appendChild(csrfToken);
        form.appendChild(passkeyField);
        document.body.appendChild(form);
        form.submit();
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            hideModal('archiveModal');
            hideModal('startEventModal');
            hideModal('addEventModal');
        }
    });
</script>
@endpush
