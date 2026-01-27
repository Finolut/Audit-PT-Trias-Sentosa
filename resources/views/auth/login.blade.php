<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal Audit | PT Trias Sentosa Tbk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100 mx-4">
        {{-- Header / Logo Area --}}
       <div class="relative z-10 flex flex-col items-center gap-2">
    <img src="https://s3-symbol-logo.tradingview.com/trias-sentosa-rp-500--600.png" alt="Logo" class="h-8 opacity-90">
    <h1 class="text-xl font-bold tracking-wide">INTERNAL AUDIT</h1>
    <p class="text-blue-200 text-xs">PT Trias Sentosa Tbk</p>
</div>


        {{-- Form Area --}}
        <div class="p-8">
            <div class="mb-6 text-center">
                <h2 class="text-xl font-semibold text-slate-800">Selamat Datang Kembali</h2>
                <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk mengelola audit internal perusahaan</p>
            </div>

            @if(session('error'))
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Perusahaan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" required 
                            class="pl-10 block w-full rounded-lg border-slate-300 bg-slate-50 border focus:bg-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 transition-colors"
                            placeholder="Email Admin">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required 
                            class="pl-10 block w-full rounded-lg border-slate-300 bg-slate-50 border focus:bg-white focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 transition-colors"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                    MASUK PORTAL AUDIT
                </button>
            </form>

            <p class="mt-4 text-[11px] text-slate-400 text-center">
Akses terbatas untuk personel resmi PT Trias Sentosa Tbk
</p>

            <div class="mt-6 text-center">
                <a href="{{ route('landing') }}" class="text-xs text-slate-400 hover:text-blue-600 font-medium transition-colors">
                    &larr; Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>

</body>
</html>