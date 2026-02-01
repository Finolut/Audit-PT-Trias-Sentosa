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
        .audit-header {
            background: linear-gradient(135deg, #0c2d5a 0%, #0a2445 100%);
            color: white;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50">
    <!-- Header Section -->
    <header class="audit-header py-6 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-2xl sm:text-3xl font-bold">INTERNAL AUDIT CHARTER</h1>
            <p class="text-lg mt-2 max-w-2xl mx-auto opacity-90">
                Official charter defining the objectives, scope, and criteria of internal audits in accordance with ISO 14001.
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 sm:p-8">
            <!-- Resume Section -->
            <section class="mb-8">
                <h2 class="text-xl font-bold text-slate-800 mb-2">Lanjutkan Audit</h2>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Masukkan Token Unik yang Anda dapatkan saat memulai audit.
                </p>
            </section>

            <!-- Error Message -->
            @if($errors->any())
                <div class="bg-red-50 text-red-700 p-3 rounded-lg mb-4 text-sm border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Token Form -->
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
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ route('audit.create') }}"
                   class="text-sm text-slate-500 hover:text-[#0c2d5a] font-medium transition">
                    ← Kembali ke Buat Audit Baru
                </a>
            </div>
        </div>
    </main>

    <script>
        const resumeForm = document.getElementById('resumeForm');
        const submitBtn  = document.getElementById('submitBtn');
        const loadingIcon = document.getElementById('loadingIcon');
        const btnText = document.getElementById('btnText');

        resumeForm.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            loadingIcon.classList.remove('hidden');
            btnText.innerText = 'MEMPROSES...';
        });
    </script>
</body>
</html>