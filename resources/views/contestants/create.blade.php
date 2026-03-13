@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-10">
    <div class="flex items-center space-x-6">
        <a href="{{ route('contestants.index') }}" class="w-12 h-12 bg-white dark:bg-slate-900 flex items-center justify-center rounded-2xl border border-slate-100 dark:border-slate-800 text-slate-400 hover:text-indigo-600 transition-all shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Create Contestant</h1>
            <p class="text-slate-500 text-sm font-medium italic">Adding to <span class="text-indigo-600 font-black">{{ $activeEvent->name }}</span></p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('contestants.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="event_id" value="{{ $activeEvent->id }}">

            {{-- Standard Inputs Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Name --}}
                <div class="space-y-2">
                    <label for="name" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="John Doe">
                    @error('name') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Number --}}
                @if(($settings['field_contestant_number'] ?? '1') == '1')
                <div class="space-y-2">
                    <label for="number" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Number ID</label>
                    <input type="number" name="number" id="number" value="{{ old('number') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="01">
                    @error('number') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Age --}}
                @if(($settings['field_contestant_age'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="age" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Age</label>
                    <input type="text" name="age" id="age" value="{{ old('age') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="21">
                    @error('age') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Address --}}
                @if(($settings['field_contestant_address'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="address" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="City Name">
                    @error('address') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Gender --}}
                @if(($settings['field_contestant_gender'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="gender" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Gender</label>
                    <select name="gender" id="gender" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm">
                        <option value="">Select</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- DOB --}}
                @if(($settings['field_contestant_dob'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="dob" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Date of Birth</label>
                    <input type="date" name="dob" id="dob" value="{{ old('dob') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm">
                    @error('dob') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Occupation --}}
                @if(($settings['field_contestant_occupation'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="occupation" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Occupation</label>
                    <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="Student">
                    @error('occupation') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Contact --}}
                @if(($settings['field_contestant_contact'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="contact_number" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Contact</label>
                    <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="+1 123...">
                    @error('contact_number') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Email --}}
                @if(($settings['field_contestant_email'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="email" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="john@example.com">
                    @error('email') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Motto --}}
                @if(($settings['field_contestant_motto'] ?? '0') == '1')
                <div class="space-y-2">
                    <label for="motto" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Motto</label>
                    <input type="text" name="motto" id="motto" value="{{ old('motto') }}" required
                        class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm" placeholder="Carpe Diem">
                    @error('motto') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <div class="space-y-6">
                    {{-- Hobbies --}}
                    @if(($settings['field_contestant_hobbies'] ?? '0') == '1')
                    <div class="space-y-2">
                        <label for="hobbies" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Hobbies & Interests</label>
                        <textarea name="hobbies" id="hobbies" rows="2" required
                            class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm resize-none" placeholder="Reading, Traveling..."></textarea>
                        @error('hobbies') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    {{-- About Profile --}}
                    @if(($settings['field_contestant_description'] ?? '1') == '1')
                    <div class="space-y-2">
                        <label for="description" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">About Profile</label>
                        <textarea name="description" id="description" rows="3"
                            class="block w-full px-5 py-3.5 rounded-2xl border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-sm resize-none" placeholder="Provide a brief context..."></textarea>
                        @error('description') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>

                {{-- Profile Identity --}}
                @if(($settings['field_contestant_image'] ?? '1') == '1')
                <div class="space-y-2 h-full">
                    <label for="image" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 ml-1 uppercase tracking-widest">Profile Identity</label>
                    <div class="relative group cursor-pointer h-[168px]">
                        <input type="file" name="image" id="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" onchange="previewImage(this)">
                        <div id="uploadPlaceholder" class="w-full h-full rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 flex flex-col items-center justify-center transition-all group-hover:border-indigo-400 group-hover:bg-indigo-50/20">
                            <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-xl shadow-sm flex items-center justify-center text-indigo-500 mb-2 transition-transform group-hover:scale-110">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-bold">Upload identity photo</p>
                        </div>
                        <div id="imagePreviewContainer" class="hidden absolute inset-0 w-full h-full rounded-[2rem] overflow-hidden border-2 border-indigo-500 z-10">
                            <img id="imagePreview" src="#" alt="Preview" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <p class="text-white text-xs font-black uppercase tracking-widest">Change Photo</p>
                            </div>
                        </div>
                    </div>
                    @error('image') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <script>
                    function previewImage(input) {
                        const container = document.getElementById('imagePreviewContainer');
                        const preview = document.getElementById('imagePreview');
                        const placeholder = document.getElementById('uploadPlaceholder');
                        
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                container.classList.remove('hidden');
                                placeholder.classList.add('opacity-0');
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                </script>
                @endif
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-5 rounded-[2rem] shadow-xl shadow-indigo-200 dark:shadow-none transition-all duration-300 transform active:scale-[0.98] tracking-widest text-xs uppercase">
                    REGISTER CONTESTANT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
