<!DOCTYPE html>
<html lang="id">
<head>
    <title>Resume Audit | Internal Audit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 sm:px-6">

    <div class="bg-white w-full max-w-md sm:max-w-lg rounded-xl shadow-lg border border-slate-200
                p-6 sm:p-8">

        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">
                Lanjutkan Audit
            </h1>
            <p class="text-slate-500 text-sm mt-1 leading-relaxed">
                Masukkan Token Unik yang Anda dapatkan saat memulai audit.
            </p>
        </div>

        <!-- Error -->
        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-3 rounded-lg mb-4 text-sm border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
<form id="resumeForm" action="{{ route('audit.resume.validate') }}" method="POST" class="space-y-6">
    @csrf

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Resume Token
        </label>

        <input
            type="text"
            name="resume_token"
            required
            autocomplete="off"
            placeholder="XXX-XXX"
            class="w-full px-4 py-3
                   border border-slate-300 rounded-lg
                   focus:ring-2 focus:ring-[#0c2d5a] focus:outline-none
                   uppercase tracking-[0.25em]
                   text-center text-lg sm:text-xl font-bold text-slate-700
                   placeholder:tracking-normal"
        >
    </div>

    <button
        id="submitBtn"
        type="submit"
        class="w-full bg-[#0c2d5a] hover:bg-[#0a2445]
               text-white font-bold py-3 rounded-lg
               transition shadow-md active:scale-[0.98]
               flex items-center justify-center gap-2">

        <!-- Spinner -->
        <svg id="loadingIcon"
             class="hidden w-5 h-5 animate-spin text-white"
             xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>

        <!-- Text -->
        <span id="btnText">Cek Token</span>
    </button>
</form>


        <!-- Footer -->
        <div class="mt-6 sm:mt-8 text-center pt-4 border-t border-slate-100">
            <a href="{{ route('audit.create') }}"
               class="text-sm text-slate-500 hover:text-[#0c2d5a] font-medium transition">
                ← Kembali ke Buat Audit Baru
            </a>
        </div>
    </div>
<script>
    const resumeForm = document.getElementById('resumeForm');
    const submitBtn  = document.getElementById('submitBtn');
    const loadingIcon = document.getElementById('loadingIcon');
    const btnText = document.getElementById('btnText');

    resumeForm.addEventListener('submit', function () {
        // Cegah klik ganda
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

        // Tampilkan loading
        loadingIcon.classList.remove('hidden');
        btnText.innerText = 'MEMPROSES...';
    });
</script>

</body>
</html>
