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
        .bg-custom-gray { background-color: #F8FAFC; }
        .text-primary-blue { color: #1a365d; }
        .bg-primary-blue { background-color: #1a365d; }
        .hover-bg-primary-blue:hover { background-color: #1e40af; }
    </style>
</head>

<body class="min-h-screen bg-custom-gray flex items-center justify-center p-4">

<div class="w-full max-w-6xl bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
    
    <div class="flex relative flex-col justify-center items-center px-10 py-16 md:py-0 min-h-[250px] md:min-h-[300px] bg-cover bg-center" 
         style="background-image: url('https://trias-sentosa.com/images/about3.webp');">
        <div class="absolute inset-0 bg-blue-900/70"></div>
        <div class="relative z-10 text-center text-white">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-wider uppercase">INTERNAL AUDIT</h1>
            <p class="text-base md:text-lg font-medium mt-2 opacity-90">PT Trias Sentosa Tbk</p>
        </div>
    </div>

    <div class="p-8 md:p-10 flex flex-col justify-center">
        <div class="mb-6 text-center">
            <h2 class="text-xl font-semibold text-slate-800">Selamat Datang Kembali</h2>
            <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk mengelola audit internal perusahaan</p>
        </div>

        @if(session('error'))
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-5">
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

            <button type="submit" id="submitBtn"
                class="w-full flex justify-center items-center py-3 px-4 rounded-lg shadow-sm text-sm font-bold text-white bg-primary-blue hover-bg-primary-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 transition-all transform hover:-translate-y-0.5">
                <span id="btnText">MASUK PORTAL AUDIT</span>
                
                <svg id="loadingIcon" class="hidden animate-spin ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <p class="mt-4 text-[11px] text-slate-400 text-center uppercase tracking-tighter">
            Akses terbatas untuk personel resmi PT Trias Sentosa Tbk
        </p>

        <div class="mt-6 text-center">
            <a href="{{ route('landing') }}" class="text-xs text-slate-400 hover:text-blue-600 font-medium transition-colors">
                &larr; Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</div>

<script>
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingIcon = document.getElementById('loadingIcon');
    const btnText = document.getElementById('btnText');

    loginForm.addEventListener('submit', function() {
        // 1. Disable tombol agar tidak diklik 2x
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        
        // 2. Tampilkan Spinner & ubah teks
        loadingIcon.classList.remove('hidden');
        btnText.innerText = 'MEMPROSES...';
    });
</script>

</body>
</html>