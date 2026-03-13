<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Setting::getValue('event_name', 'Tabulator') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind Browser Script (v4) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent),
                        radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.1), transparent);
        }
    </style>
</head>
<body class="antialiased bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden">
    <div class="relative min-h-screen hero-gradient">
        @yield('content')
    </div>
</body>
</html>
