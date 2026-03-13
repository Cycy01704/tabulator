<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $eventName }} - Standby</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #0f172a;
            color: white;
            overflow: hidden;
        }
        .bg-glow {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(79, 70, 229, 0.2) 0%, transparent 70%);
            z-index: -1;
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.1); }
        }
        .animate-pulse-slow { animation: pulse-slow 8s infinite ease-in-out; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 text-center">
    <div class="bg-glow animate-pulse-slow"></div>
    
    <div class="max-w-2xl space-y-12">
        <!-- Logo -->
        <div class="inline-flex items-center justify-center w-32 h-32 bg-white/5 backdrop-blur-3xl rounded-[2.5rem] mb-4 border border-white/10 shadow-2xl">
            <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
        </div>

        <div class="space-y-6">
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-white">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Stand By</span>
            </h1>
            <p class="text-slate-400 font-medium text-xl md:text-2xl max-w-lg mx-auto leading-relaxed">
                The results are currently being calculated by the grand panel of judges. Please stay tuned.
            </p>
        </div>

        <div class="flex items-center justify-center space-x-4">
            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            <div class="w-2 h-2 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
        </div>

        <p class="text-slate-600 text-[10px] font-bold uppercase tracking-[0.4em] fixed bottom-12 left-0 w-full">
            {{ $eventName }} Official Scoring Feed
            <a href="{{ route('login') }}" class="text-slate-700/30 hover:text-slate-500 transition-colors ml-2">•</a>
        </p>
    </div>

    <script>
        // Check for state changes every 3 seconds
        setInterval(async () => {
            try {
                const response = await fetch('{{ route('audience.leaderboard.status') }}');
                const data = await response.json();
                if (data.visible) {
                    location.reload(); // Switch to leaderboard
                }
            } catch (error) {
                console.error('Status check failed');
            }
        }, 3000);
    </script>
</body>
</html>
