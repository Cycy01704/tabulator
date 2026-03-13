@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    <div class="flex items-center space-x-6">
        <a href="{{ route('criteria.index') }}" class="w-12 h-12 bg-white dark:bg-slate-900 flex items-center justify-center rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 transition-all shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">Edit Criteria</h1>
            <p class="text-slate-500 font-medium italic">
                Configured for <span class="text-emerald-600 font-black">{{ $criterion->event ? $criterion->event->name : 'Legacy Data' }}</span>
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('criteria.update', $criterion) }}" method="POST" class="p-10 space-y-10">
            @csrf
            @method('PUT')

            <!-- Section 01: Criteria Definition -->
            <div class="space-y-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-950/30 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-xs">01</div>
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">Criteria Definition</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Name --}}
                    <div class="space-y-3 md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1 uppercase tracking-widest">Criteria Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $criterion->name) }}" required
                            class="block w-full px-6 py-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                            placeholder="e.g. Stage Presence">
                        @error('name') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Weight --}}
                    @if(($formula ?? 'normal') === 'weighted')
                    <div class="space-y-3">
                        <label for="weight" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1 uppercase tracking-widest">Weight (%)</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight', $criterion->weight) }}" required min="0" max="100" step="0.01"
                            class="block w-full px-6 py-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                            placeholder="e.g. 30">
                        <p class="text-[11px] text-slate-400 ml-1">Percentage weight of this criterion in the total score (0–100).</p>
                        @error('weight') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    @else
                    <input type="hidden" name="weight" value="{{ $criterion->weight ?? 0 }}">
                    @endif

                    {{-- Description --}}
                    <div class="space-y-3">
                        <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-300 ml-1 uppercase tracking-widest">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="block w-full px-6 py-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none resize-none"
                            placeholder="How should judges evaluate this?">{{ old('description', $criterion->description) }}</textarea>
                        @error('description') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="w-full h-px bg-slate-100 dark:bg-slate-800"></div>

            <!-- Section 02: Points Configuration -->
            <div class="space-y-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-50 dark:bg-emerald-950/30 rounded-lg flex items-center justify-center text-emerald-600 font-bold text-xs">02</div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">Points Configuration</h3>
                            <p class="text-xs text-slate-400 font-medium">Each grade has a label and a numeric score value.</p>
                        </div>
                    </div>
                    <button type="button" id="add-grade"
                        class="inline-flex items-center px-4 py-2 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 font-bold text-xs rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                        ADD GRADE
                    </button>
                </div>

                {{-- Column headers --}}
                <div class="grid grid-cols-[1fr_1fr_2.5rem] gap-4 px-2">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Label</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Score (pts)</span>
                    <span></span>
                </div>

                <div id="grades-container" class="space-y-4">
                    @foreach($criterion->grades as $index => $grade)
                        <div class="grade-row grid grid-cols-[1fr_1fr_2.5rem] items-center gap-4 bg-slate-50/50 dark:bg-slate-950/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <input type="text" name="grades[{{ $index }}][label]" value="{{ old("grades.$index.label", $grade->label) }}" required
                                class="block w-full px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-sm font-bold outline-none"
                                placeholder="e.g. Excellent">
                            <input type="number" name="grades[{{ $index }}][score]" value="{{ old("grades.$index.score", $grade->score) }}" required min="0" step="0.01"
                                class="block w-full px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-sm font-bold outline-none"
                                placeholder="e.g. 10">
                            <button type="button" class="remove-grade p-2 text-slate-300 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                @error('grades') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                @error('grades.*.label') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                @error('grades.*.score') <p class="text-xs text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-5 rounded-[2rem] shadow-xl shadow-indigo-200 dark:shadow-none transition-all duration-300 transform active:scale-[0.98] uppercase tracking-widest">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let gradeCount = {{ count($criterion->grades) }};

    document.getElementById('add-grade').addEventListener('click', function () {
        const container = document.getElementById('grades-container');
        const div = document.createElement('div');
        div.className = 'grade-row grid grid-cols-[1fr_1fr_2.5rem] items-center gap-4 bg-slate-50/50 dark:bg-slate-950/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-800';
        div.innerHTML = `
            <input type="text" name="grades[${gradeCount}][label]" required
                class="block w-full px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-sm font-bold outline-none"
                placeholder="e.g. Good">
            <input type="number" name="grades[${gradeCount}][score]" required min="0" step="0.01"
                class="block w-full px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 text-sm font-bold outline-none"
                placeholder="e.g. 7.5">
            <button type="button" class="remove-grade p-2 text-slate-300 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        `;
        container.appendChild(div);
        gradeCount++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-grade')) {
            const row = e.target.closest('.grade-row');
            if (document.querySelectorAll('.grade-row').length > 1) {
                row.remove();
            } else {
                alert('At least one grade point is required.');
            }
        }
    });
</script>
@endsection
