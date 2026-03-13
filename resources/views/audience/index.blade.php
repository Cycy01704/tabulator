<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $eventName }} - Live Rankings</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #0f172a;
            color: white;
            overflow-x: hidden;
        }
        .bg-glow {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(79, 70, 229, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 70%, rgba(147, 51, 234, 0.15) 0%, transparent 50%);
            z-index: -1;
        }
        .rank-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .rank-card:hover {
            transform: scale(1.02) translateX(10px);
            background: rgba(30, 41, 59, 0.9);
            border-color: rgba(99, 102, 241, 0.5);
        }
        .gold-glow { box-shadow: 0 0 30px rgba(234, 179, 8, 0.2); border-color: rgba(234, 179, 8, 0.3) !important; }
        .silver-glow { box-shadow: 0 0 30px rgba(148, 163, 184, 0.2); border-color: rgba(148, 163, 184, 0.3) !important; }
        .bronze-glow { box-shadow: 0 0 30px rgba(180, 83, 9, 0.2); border-color: rgba(180, 83, 9, 0.3) !important; }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-rank { animation: slideIn 0.8s ease-out forwards; }
    </style>
</head>
<body class="min-h-screen py-20 px-6">
    <div class="bg-glow"></div>
    
    <div class="max-w-5xl mx-auto space-y-16">
        <!-- Header -->
        <div class="text-center space-y-4">
            <div class="inline-flex items-center px-4 py-2 {{ $isConcluded ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-indigo-500/10 border-indigo-500/20 text-indigo-400' }} rounded-full border text-xs font-black uppercase tracking-[0.3em]">
                {{ $isConcluded ? 'Official Final Results' : 'Live Competition Feed' }}
            </div>
            <h1 class="text-7xl font-black tracking-tighter text-white">
                {{ $eventName }} <span class="text-transparent bg-clip-text bg-gradient-to-r {{ $isConcluded ? 'from-emerald-400 to-teal-400' : 'from-indigo-400 to-purple-400' }}">Leaderboard</span>
            </h1>
            <p class="text-slate-400 font-medium text-lg">{{ $isConcluded ? 'The competition has concluded. Here are the final scores.' : 'Real-time standings based on official judging criteria.' }}</p>
        </div>

        <!-- Leaderboard List -->
        <div class="space-y-4">
            @foreach($rankings as $index => $contestant)
                <div class="rank-card rounded-3xl p-6 flex items-center gap-8 animate-rank {{ $index == 0 ? 'gold-glow bg-amber-900/10' : ($index == 1 ? 'silver-glow bg-slate-800/10' : ($index == 2 ? 'bronze-glow bg-orange-900/10' : '')) }}" 
                     style="animation-delay: {{ $index * 0.1 }}s">
                    
                    <!-- Rank Number -->
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl font-black 
                        {{ $index == 0 ? 'text-amber-400 bg-amber-400/10' : ($index == 1 ? 'text-slate-400 bg-slate-400/10' : ($index == 2 ? 'text-orange-400 bg-orange-400/10' : 'text-slate-500 bg-slate-500/10')) }}">
                        #{{ $contestant->rank }}
                    </div>

                    <!-- Image -->
                    <div class="relative">
                        @if($contestant->image_path)
                            <img src="{{ asset('storage/' . $contestant->image_path) }}" class="w-20 h-20 rounded-2xl object-cover shadow-2xl border-2 border-white/10" alt="{{ $contestant->name }}">
                        @else
                            <div class="w-20 h-20 rounded-2xl bg-slate-800 flex items-center justify-center border-2 border-white/10 text-slate-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif
                        @if($index < 3)
                            <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-lg
                                {{ $index == 0 ? 'bg-amber-400 text-amber-950' : ($index == 1 ? 'bg-slate-400 text-slate-950' : 'bg-orange-500 text-orange-950') }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-black text-indigo-400 uppercase tracking-widest bg-indigo-400/10 px-2 py-1 rounded-md">CONT. No. {{ $contestant->number }}</span>
                        </div>
                        <h3 class="text-3xl font-extrabold text-white mt-1">{{ $contestant->name }}</h3>
                    </div>

                    <!-- Progress Bar (Subtle) -->
                    <div class="hidden lg:block w-48">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">{{ $isConcluded ? 'Final Standing' : 'Completion ' . round($contestant->progress) . '%' }}</p>
                        <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full {{ $isConcluded ? 'bg-emerald-500' : 'bg-indigo-500' }} rounded-full transition-all duration-1000" style="width: {{ $isConcluded ? '100' : $contestant->progress }}%"></div>
                        </div>
                    </div>

                    <!-- Score -->
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Final Score</p>
                        <div class="text-5xl font-black text-white lining-nums">
                            {{ number_format($contestant->average_score, 2) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Info -->
        <div class="flex items-center justify-between pt-10 border-t border-white/5">
            <div class="flex items-center space-x-3 text-slate-500">
                @if(!$isConcluded)
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-bold uppercase tracking-widest">Live Updates Enabled</span>
                @else
                    <div class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-500/80">Archived Official Record</span>
                @endif
            </div>
            <p class="text-slate-600 text-[10px] font-bold uppercase tracking-[0.3em]">
                {{ $eventName }} - {{ $isConcluded ? 'Historical Archive' : 'Digital Adjudication System' }}
                <a href="{{ route('login') }}" class="text-slate-700/30 hover:text-slate-500 transition-colors ml-2">•</a>
            </p>
        </div>
    </div>

    <script>
        // Real-time state sync
        setInterval(async () => {
            try {
                const response = await fetch('{{ route('audience.leaderboard.status') }}');
                const data = await response.json();
                if (!data.visible) {
                    location.reload(); // Switch to standby
                }
            } catch (error) {
                console.error('Status check failed');
            }
        }, 3000);

        @if(!$isConcluded)
            // Auto refresh scores every 30 seconds
            setTimeout(() => {
                location.reload();
            }, 30000);
        @endif
    </script>
</body>
</html>
