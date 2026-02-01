<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resume Audit | Internal Audit</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --navy: #0c2d5a;
            --slate: #475569;
            --slate-dark: #1e293b;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc;
        }
        .hero-section {
            background:
                linear-gradient(
                    rgba(12, 45, 90, 0.88),
                    rgba(12, 45, 90, 0.88)
                ),
                url('https://media.licdn.com/dms/image/v2/D563DAQEpYdKv0Os29A/image-scale_191_1128/image-scale_191_1128/0/1690510724603/pt_trias_sentosa_tbk_cover?e=2147483647&v=beta&t=dOGhpl6HrbRAla_mDVT5azyevrvu-cOGFxPcrlizZ6M');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 260px;
            display: flex;
            align-items: center;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--slate-dark);
            margin: 2.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .section-description {
            color: var(--slate);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        .form-input {
            border: 1px solid #cbd5e1;
            border-radius: 0.375rem;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 2px rgba(12, 45, 90, 0.1);
        }
        .main-cta {
            background: var(--navy);
            color: white;
            border-radius: 0.5rem;
            padding: 0.75rem 2rem;
            font-size: 1.125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            width: 100%;
            max-width: 320px;
            margin: 2rem auto 0;
            text-align: center;
        }
        .main-cta:hover:not(:disabled) {
            background: #0a2445;
        }
        .main-cta:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }
        /* Transparent token section styling */
        .resume-token-section {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 2rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(4px);
        }
        .resume-token-header {
            font-weight: 700;
            color: var(--slate-dark);
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }
        .resume-token-description {
            color: var(--slate);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1.25rem;
        }
        .resume-token-input {
            background-color: white;
            border: 1px solid #cbd5e1;
            border-radius: 0.375rem;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 1.125rem;
            font-weight: 600;
            text-align: center;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            transition: border-color 0.2s;
        }
        .resume-token-input:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 2px rgba(12, 45, 90, 0.1);
        }
        .resume-token-button {
            background: var(--navy);
            color: white;
            border-radius: 0.5rem;
            padding: 0.75rem 2rem;
            font-size: 1.125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            width: 100%;
            margin: 1.25rem auto 0;
            text-align: center;
        }
        .resume-token-button:hover:not(:disabled) {
            background: #0a2445;
        }
        .resume-token-button:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }
        .resume-token-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--slate);
            font-size: 0.9rem;
        }
        .resume-token-footer a {
            color: var(--navy);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .resume-token-footer a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="text-slate-800">
    <!-- Hero Section (same as form) -->
    <section class="hero-section text-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">
            <h1 class="text-3xl md:text-4xl font-bold mb-3">
                INTERNAL AUDIT CHARTER
            </h1>
            <p class="text-base md:text-lg opacity-90 max-w-3xl">
                Official charter defining the objectives, scope, and criteria of internal audits in accordance with ISO 14001.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 lg:px-6 py-8">
        <!-- Transparent Resume Token Section -->
        <section class="resume-token-section">
            <h2 class="resume-token-header">Lanjutkan Audit</h2>
            <p class="resume-token-description">Masukkan Token Unik yang Anda dapatkan saat memulai audit.</p>
            
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
                    <label class="block text-sm font-semibold mb-1 text-slate-700">
                        Resume Token
                    </label>
                    <input
                        type="text"
                        name="resume_token"
                        required
                        autocomplete="off"
                        placeholder="XXX-XXX"
                        class="resume-token-input"
                    >
                </div>

                <button
                    id="submitBtn"
                    type="submit"
                    class="resume-token-button"
                >
                    <span id="btnText">Cek Token</span>
                    <span>→</span>
                    
                    <!-- Loading Icon -->
                    <svg id="loadingIcon" class="hidden w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </form>

            <!-- Footer -->
            <div class="resume-token-footer">
                <a href="{{ route('audit.create') }}">
                    ← Kembali ke Buat Audit Baru
                </a>
            </div>
        </section>

        <!-- Rest of the form content would go here -->
        <!-- ... (other sections would follow) ... -->
    </div>

    <script>
        const resumeForm = document.getElementById('resumeForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingIcon = document.getElementById('loadingIcon');
        const btnText = document.getElementById('btnText');

        resumeForm.addEventListener('submit', function() {
            // Prevent double submission
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            
            // Show loading state
            loadingIcon.classList.remove('hidden');
            btnText.innerText = 'MEMPROSES...';
        });
    </script>
</body>
</html>