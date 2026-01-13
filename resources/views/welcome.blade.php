<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT TRIAS SENTOSA Tbk - Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-custom-gray { background-color: #E5E7EB; }
    </style>
</head>
<body class="bg-custom-gray min-h-screen">

    <header class="p-8 md:p-12 w-full max-w-7xl mx-auto flex justify-between items-start">
        <div class="flex items-center gap-4">
            <img src="https://www.trias-sentosa.com/assets/images/logo.png" alt="Logo" class="h-16 md:h-20 object-contain">
            <div class="border-l-2 border-gray-400 pl-4">
                <h1 class="text-xl md:text-2xl font-extrabold text-[#1a365d] leading-none uppercase tracking-tighter">
                    PT Trias Sentosa Tbk
                </h1>
                <p class="text-[10px] md:text-xs text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">
                    Flexible Packaging Film Manufacturer
                </p>
            </div>
        </div>

        <a href="{{ route('admin.dashboard') }}" 
           class="bg-white/50 hover:bg-white text-gray-700 text-xs font-bold py-2 px-4 rounded-lg border border-gray-300 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            ADMIN PANEL
        </a>
    </header>

    <main class="max-w-7xl mx-auto px-8 md:px-12 flex flex-col md:flex-row items-center justify-between gap-16 py-10">
        
        <div class="w-full md:w-1/2 space-y-10">
            <div class="space-y-4">
                <h2 class="text-5xl md:text-7xl font-extrabold text-gray-900 leading-[1.05] tracking-tight">
                    BOPP, BOPET <br>
                    & CPP FILMS <br>
                    MANUFACTURER
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 font-medium leading-relaxed">
                    From our market in Indonesia <br>
                    to anywhere in the world
                </p>
            </div>

            <div class="pt-6">
                <a href="#survey-link" 
                   class="bg-white hover:bg-gray-50 text-gray-900 font-extrabold text-xl py-4 px-14 rounded-sm shadow-md transition-all hover:shadow-lg inline-block border border-gray-100 active:scale-95">
                    Survey
                </a>
            </div>
        </div>

        <div class="w-full md:w-[45%] aspect-square bg-gray-300 rounded shadow-inner relative overflow-hidden group">
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-400 opacity-40 group-hover:scale-110 transition-transform duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            </div>
    </main>

    <footer class="mt-auto p-12 text-center">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em]">
            &copy; 2026 PT Trias Sentosa Tbk. All Rights Reserved.
        </p>
    </footer>

</body>
</html>