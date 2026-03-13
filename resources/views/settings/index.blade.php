@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">System Settings</h1>
            <p class="text-slate-500 font-medium italic mt-1">Configure global platform behavior and registration fields.</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white dark:bg-slate-900 p-2 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center px-4 py-2 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                <div class="flex items-center space-x-3">
                    <div id="unlockIndicator" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="lockIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Session Protection</p>
                        <p id="unlockStatus" class="text-xs font-bold text-slate-600 dark:text-slate-300">LOCKED</p>
                    </div>
                </div>
                <div class="ml-6 border-l border-slate-200 dark:border-slate-700 pl-6">
                    <button type="button" id="sessionAuthToggle" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-slate-200 dark:bg-slate-700 focus:outline-none" role="switch" data-state="off">
                        <span id="sessionAuthKnob" aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-10">
        {{-- Section 01: Core Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center space-x-4">
                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-950/30 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Event Identification</h2>
                    <p class="text-sm text-slate-400 font-medium mt-0.5">Primary Branding for the Competition.</p>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <form action="{{ route('settings.update') }}" method="POST" class="group">
                    @csrf
                    <input type="hidden" name="key" value="event_name">
                    <div class="flex flex-col md:flex-row gap-6 items-end">
                        <div class="flex-1 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Universal Competition Name</label>
                            <input type="text" name="value" value="{{ $settings['event_name'] ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-2xl p-4 text-lg font-bold outline-none transition-all duration-300" 
                                placeholder="e.g. Grand Coronation 2026">
                        </div>
                        <button type="submit" class="px-10 py-5 bg-indigo-600 text-white font-extrabold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none uppercase text-xs tracking-widest">
                            Update Branding
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Section 02: Scoring Configuration --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center space-x-4">
                <div class="w-10 h-10 bg-rose-50 dark:bg-rose-950/30 rounded-xl flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Scoring Tabulation</h2>
                    <p class="text-sm text-slate-400 font-medium mt-0.5">Define the mathematical formula for outcomes.</p>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <form action="{{ route('settings.update') }}" method="POST" class="group">
                    @csrf
                    <input type="hidden" name="key" value="tabulation_formula">
                    <div class="flex flex-col md:flex-row gap-6 items-end">
                        <div class="flex-1 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Calculation Method</label>
                            <select name="value" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-2xl p-4 text-lg font-bold outline-none appearance-none transition-all duration-300">
                                <option value="normal" {{ ($settings['tabulation_formula'] ?? 'normal') === 'normal' ? 'selected' : '' }}>Normal Average (Total Score / Judges)</option>
                                <option value="weighted" {{ ($settings['tabulation_formula'] ?? 'normal') === 'weighted' ? 'selected' : '' }}>Weighted Average (Criterion weights applied)</option>
                            </select>
                        </div>
                        <button type="submit" class="px-10 py-5 bg-indigo-600 text-white font-extrabold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none uppercase text-xs tracking-widest">
                            Save Formula
                        </button>
                    </div>
                    <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-900/30 rounded-2xl">
                        <p class="text-[10px] text-amber-700 dark:text-amber-400 font-bold uppercase tracking-wider mb-1">Calculation Logic:</p>
                        <p class="text-xs text-amber-600 dark:text-amber-500 italic">
                            <b>Normal:</b> Sum of all raw scores divided by the number of judges.<br>
                            <b>Weighted:</b> Sum of (Score × Weight) for all criteria, divided by total weight possible.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        {{-- Section 03: Display & Presentation --}}

        {{-- Section 03: Contestant Configuration --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center space-x-4">
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-950/30 rounded-xl flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Contestant Registration</h2>
                    <p class="text-sm text-slate-400 font-medium mt-0.5">Toggle which fields are visible during registration.</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="batchToggle(1)" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                        Enable All
                    </button>
                    <button type="button" onclick="batchToggle(0)" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-600 hover:text-white transition-all">
                        Disable All
                    </button>
                </div>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                @php
                    $fieldSettings = [
                        'field_contestant_number' => ['label' => 'Contestant Number ID', 'desc' => 'Allow assigning a unique number to each participant.'],
                        'field_contestant_image' => ['label' => 'Profile Identity Photo', 'desc' => 'Enable uploading of profile or identity photos.'],
                        'field_contestant_description' => ['label' => 'About/Biography Field', 'desc' => 'Provide a text area for additional profile details.'],
                        'field_contestant_age' => ['label' => 'Age Information', 'desc' => 'Record the age of the contestants.'],
                        'field_contestant_address' => ['label' => 'Address / Representation', 'desc' => 'Record where the contestant is from or representing.'],
                        'field_contestant_gender' => ['label' => 'Gender', 'desc' => 'Record the gender of the contestants.'],
                        'field_contestant_dob' => ['label' => 'Date of Birth', 'desc' => 'Record the birth date of the contestants.'],
                        'field_contestant_occupation' => ['label' => 'Occupation / School', 'desc' => 'Record where the contestant works or studies.'],
                        'field_contestant_contact' => ['label' => 'Contact Number', 'desc' => 'Record the phone number of the contestant.'],
                        'field_contestant_email' => ['label' => 'Email Address', 'desc' => 'Record the email of the contestant.'],
                        'field_contestant_hobbies' => ['label' => 'Hobbies & Interests', 'desc' => 'Capture what the contestant likes to do.'],
                        'field_contestant_motto' => ['label' => 'Personal Motto', 'desc' => 'Capture the contestant\'s life philosophy.'],
                    ];
                @endphp

                @foreach($fieldSettings as $key => $data)
                <div class="flex items-center justify-between p-5 bg-slate-50/50 dark:bg-slate-800/30 rounded-3xl border border-transparent hover:border-indigo-500/10 transition-all group">
                    <div class="max-w-md">
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $data['label'] }}</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $data['desc'] }}</p>
                    </div>
                    <div class="flex items-center">
                        <button type="button" 
                            data-key="{{ $key }}"
                            class="field-toggle relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ ($settings[$key] ?? (in_array($key, ['field_contestant_number', 'field_contestant_image', 'field_contestant_description', 'field_contestant_age', 'field_contestant_address']) ? '1' : '0')) == '1' ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700' }}"
                            role="switch" data-state="{{ ($settings[$key] ?? (in_array($key, ['field_contestant_number', 'field_contestant_image', 'field_contestant_description', 'field_contestant_age', 'field_contestant_address']) ? '1' : '0')) == '1' ? 'on' : 'off' }}">
                            <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-sm ring-0 transition duration-300 ease-in-out {{ ($settings[$key] ?? (in_array($key, ['field_contestant_number', 'field_contestant_image', 'field_contestant_description', 'field_contestant_age', 'field_contestant_address']) ? '1' : '0')) == '1' ? 'translate-x-5' : 'translate-x-0' }}">
                            </span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Section 04: Security & Access --}}

                {{-- System Passkey Field --}}
                <form action="{{ route('settings.update') }}" method="POST" class="group passkey-form">
                    @csrf
                    <input type="hidden" name="key" value="system_passkey">
                    <input type="hidden" name="passkey" id="systemPasskeyAuth" value="">
                    <div class="flex flex-col md:flex-row gap-6 items-end p-6 border-2 border-dashed border-rose-100 dark:border-rose-900/30 rounded-3xl bg-rose-50/10">
                        <div class="flex-1 space-y-3">
                            <label class="block text-[10px] font-black text-rose-400 uppercase tracking-widest ml-1">Critical System Passkey</label>
                            <input type="text" name="value" value="{{ $settings['system_passkey'] ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-rose-500 rounded-2xl p-4 text-2xl font-black tracking-[0.5em] outline-none text-center transition-all duration-300" 
                                placeholder="4-DIGIT PIN">
                        </div>
                        <button type="submit" class="px-10 py-5 bg-rose-600 text-white font-extrabold rounded-2xl hover:bg-rose-700 transition-all shadow-xl shadow-rose-200 dark:shadow-none uppercase text-xs tracking-widest">
                            Update Master
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-3 ml-4 font-medium flex items-center italic">
                        <svg class="w-3 h-3 mr-1 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path></svg>
                        Protects event conclusion, archives, and critical security settings.
                    </p>
                </form>

                <hr class="border-slate-100 dark:border-slate-800 my-4">

                {{-- Leaderboard Passkey Field --}}
                <form action="{{ route('settings.update') }}" method="POST" class="group passkey-form">
                    @csrf
                    <input type="hidden" name="key" value="leaderboard_passkey">
                    <input type="hidden" name="passkey" id="leaderboardPasskeyAuth" value="">
                    <div class="flex flex-col md:flex-row gap-6 items-end p-6 border-2 border-dashed border-indigo-100 dark:border-indigo-900/30 rounded-3xl bg-indigo-50/10">
                        <div class="flex-1 space-y-3">
                            <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-1">Audience Visibility Passkey</label>
                            <input type="text" name="value" value="{{ $settings['leaderboard_passkey'] ?? '' }}" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-2xl p-4 text-2xl font-black tracking-[0.5em] outline-none text-center transition-all duration-300" 
                                placeholder="4-DIGIT PIN">
                        </div>
                        <button type="submit" class="px-10 py-5 bg-indigo-600 text-white font-extrabold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none uppercase text-xs tracking-widest">
                            Update Public
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-3 ml-4 font-medium flex items-center italic">
                        <svg class="w-3 h-3 mr-1 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path></svg>
                        Required only for toggling the public leaderboard visibility.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Passkey Modal for Toggling --}}
<div id="passkeyModal" class="fixed inset-0 z-[120] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" id="modalCloseBackdrop"></div>
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100 dark:border-slate-800">
            <div class="p-10">
                <div class="flex items-center justify-center mb-8">
                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl flex items-center justify-center text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 dark:text-white text-center tracking-tight mb-2">Authorization Required</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-8 px-4 leading-relaxed">Please enter the administrative passkey to toggle this setting.</p>

                <div class="space-y-6">
                    <div>
                        <input type="password" id="modalPasskey" placeholder="••••" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-2xl outline-none text-center font-bold tracking-[1em] transition-all duration-300">
                    </div>
                    
                    <div class="flex flex-col gap-3 pt-4">
                        <button id="modalConfirmBtn" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
                            Verify & Apply
                        </button>
                        <button id="modalCancelBtn" class="w-full py-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold transition-colors">
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
    document.addEventListener('DOMContentLoaded', function () {
        const passkeyModal = document.getElementById('passkeyModal');
        const modalPasskey = document.getElementById('modalPasskey');
        const modalConfirmBtn = document.getElementById('modalConfirmBtn');
        const modalCancelBtn = document.getElementById('modalCancelBtn');
        const modalCloseBackdrop = document.getElementById('modalCloseBackdrop');
        const sessionAuthToggle = document.getElementById('sessionAuthToggle');
        const sessionAuthKnob = document.getElementById('sessionAuthKnob');
        const unlockStatus = document.getElementById('unlockStatus');
        const unlockIndicator = document.getElementById('unlockIndicator');
        const lockIcon = document.getElementById('lockIcon');

        let pendingToggle = null;
        let isUnlocked = false;
        let cachedPasskey = '';

        // Initialize all toggles with the 'field-toggle' class
        document.querySelectorAll('.field-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                handleToggle(this, key);
            });
        });

        // Intercept passkey-protected forms
        document.querySelectorAll('.passkey-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!isUnlocked) {
                    e.preventDefault();
                    pendingToggle = { isFormSubmit: true, form: this };
                    passkeyModal.classList.remove('hidden');
                    modalPasskey.focus();
                }
            });
        });

        const leaderboardToggle = document.getElementById('leaderboardToggle');
        const securityToggle = document.getElementById('securityToggle');

        if (leaderboardToggle) leaderboardToggle.addEventListener('click', () => handleToggle(leaderboardToggle, 'leaderboard_visible'));
        if (securityToggle) securityToggle.addEventListener('click', () => handleToggle(securityToggle, 'triple_layer_security'));

        sessionAuthToggle.addEventListener('click', function() {
            if (isUnlocked) {
                lockSession();
            } else {
                pendingToggle = { isUnlockAction: true };
                passkeyModal.classList.remove('hidden');
                modalPasskey.focus();
            }
        });

        function handleToggle(button, key) {
            const currentState = button.getAttribute('data-state') === 'on';
            const newValue = currentState ? '0' : '1';

            if (isUnlocked && cachedPasskey) {
                executeRequest({ button, key, newValue, passkey: cachedPasskey });
            } else {
                pendingToggle = { button, key, newValue };
                passkeyModal.classList.remove('hidden');
                modalPasskey.focus();
            }
        }

        function lockSession() {
            isUnlocked = false;
            cachedPasskey = '';
            sessionAuthToggle.classList.add('bg-slate-200', 'dark:bg-slate-700');
            sessionAuthToggle.classList.remove('bg-emerald-500');
            sessionAuthKnob.classList.remove('translate-x-5');
            unlockStatus.textContent = 'LOCKED';
            unlockStatus.classList.remove('text-emerald-500');
            unlockIndicator.classList.remove('bg-emerald-50', 'dark:bg-emerald-900/30', 'text-emerald-500');
            unlockIndicator.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-400');
            lockIcon.setAttribute('d', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z');
            showToast('Session locked', 'info');
        }

        function unlockSession(passkey) {
            isUnlocked = true;
            cachedPasskey = passkey;
            
            // Mirror passkey to hidden inputs for form submissions
            const sysPassInput = document.getElementById('systemPasskeyAuth');
            const leadPassInput = document.getElementById('leaderboardPasskeyAuth');
            if (sysPassInput) sysPassInput.value = passkey;
            if (leadPassInput) leadPassInput.value = passkey;

            sessionAuthToggle.classList.remove('bg-slate-200', 'dark:bg-slate-700');
            sessionAuthToggle.classList.add('bg-emerald-500');
            sessionAuthKnob.classList.add('translate-x-5');
            unlockStatus.textContent = 'UNLOCKED';
            unlockStatus.classList.add('text-emerald-500');
            unlockIndicator.classList.add('bg-emerald-50', 'dark:bg-emerald-900/30', 'text-emerald-500');
            unlockIndicator.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-400');
            lockIcon.setAttribute('d', 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z');
            showToast('Session unlocked', 'success');
        }

        modalCancelBtn.addEventListener('click', closePasskeyModal);
        modalCloseBackdrop.addEventListener('click', closePasskeyModal);

        function closePasskeyModal() {
            passkeyModal.classList.add('hidden');
            modalPasskey.value = '';
            pendingToggle = null;
        }

        modalConfirmBtn.addEventListener('click', submitUpdate);
        modalPasskey.addEventListener('keypress', (e) => { if (e.key === 'Enter') submitUpdate(); });

        async function submitUpdate() {
            if (!pendingToggle) return;

            const passkey = modalPasskey.value;
            if (!passkey) {
                showToast('Passkey is required', 'error');
                return;
            }

            if (pendingToggle.isUnlockAction || pendingToggle.isFormSubmit) {
                // Verify passkey against system_passkey (the master key)
                if (passkey === "{{ $settings['system_passkey'] ?? '1234' }}") {
                    unlockSession(passkey);
                    
                    if (pendingToggle.isFormSubmit) {
                        // Resubmit the form now that inputs are mirrored
                        pendingToggle.form.submit();
                    }
                    
                    closePasskeyModal();
                } else {
                    showToast('Invalid passkey', 'error');
                    modalPasskey.value = '';
                }
                return;
            }

            // Handle Batch Update
            if (pendingToggle.isBatch) {
                const success = await executeBatchRequest(pendingToggle.settings, passkey, pendingToggle.newValue);
                if (success) {
                    if (isUnlocked) cachedPasskey = passkey; // Update cache if we were in the middle of unlocking
                    closePasskeyModal();
                }
                return;
            }

            const success = await executeRequest({ ...pendingToggle, passkey });
            if (success) {
                closePasskeyModal();
            }
        }

        async function executeRequest({ button, key, newValue, passkey }) {
            try {
                const response = await fetch('{{ route('settings.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ key, value: newValue, passkey })
                });

                const data = await response.json();

                if (response.ok) {
                    const isOn = newValue === '1';
                    const knob = button.querySelector('span');

                    button.setAttribute('data-state', isOn ? 'on' : 'off');
                    
                    if (isOn) {
                        button.classList.remove('bg-slate-200', 'dark:bg-slate-700');
                        button.classList.add(button.id === 'securityToggle' ? 'bg-rose-600' : 'bg-indigo-600');
                        knob.classList.add(button.id === 'securityToggle' ? 'translate-x-7' : 'translate-x-5');
                        knob.classList.remove('translate-x-0');
                    } else {
                        button.classList.add('bg-slate-200', 'dark:bg-slate-700');
                        button.classList.remove('bg-indigo-600', 'bg-rose-600');
                        knob.classList.remove('translate-x-7', 'translate-x-5');
                        knob.classList.add('translate-x-0');
                    }

                    showToast(data.message, 'success');
                    return true;
                } else {
                    showToast(data.message || 'Verification failed', 'error');
                    if (isUnlocked) lockSession(); // Force lock on auth failure
                    return false;
                }
            } catch (error) {
                showToast('Network error occurred', 'error');
                return false;
            }
        }

        async function executeBatchRequest(settings, passkey, newValue) {
            try {
                const response = await fetch('{{ route('settings.batch') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ settings, passkey })
                });

                const data = await response.json();

                if (response.ok) {
                    const isOn = newValue === '1';
                    document.querySelectorAll('.field-toggle').forEach(button => {
                        const knob = button.querySelector('span');
                        button.setAttribute('data-state', isOn ? 'on' : 'off');
                        if (isOn) {
                            button.classList.remove('bg-slate-200', 'dark:bg-slate-700');
                            button.classList.add('bg-indigo-600');
                            knob.classList.add('translate-x-5');
                            knob.classList.remove('translate-x-0');
                        } else {
                            button.classList.add('bg-slate-200', 'dark:bg-slate-700');
                            button.classList.remove('bg-indigo-600');
                            knob.classList.remove('translate-x-5');
                            knob.classList.add('translate-x-0');
                        }
                    });

                    showToast(data.message, 'success');
                    return true;
                } else {
                    showToast(data.message || 'Verification failed', 'error');
                    if (isUnlocked) lockSession();
                    return false;
                }
            } catch (error) {
                showToast('Network error occurred', 'error');
                return false;
            }
        }

        window.batchToggle = function(state) {
            const newValue = state.toString();
            const settingsObject = {};
            document.querySelectorAll('.field-toggle').forEach(btn => {
                const key = btn.getAttribute('data-key');
                settingsObject[key] = newValue;
            });

            if (isUnlocked && cachedPasskey) {
                executeBatchRequest(settingsObject, cachedPasskey, newValue);
            } else {
                pendingToggle = {
                    isBatch: true,
                    settings: settingsObject,
                    newValue: newValue
                };
                passkeyModal.classList.remove('hidden');
                modalPasskey.focus();
            }
        };
    });
</script>
@endpush
@endsection
