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
        .token-input {
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
        .token-input:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 2px rgba(12, 45, 90, 0.1);
        }
        .token-button {
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
        .token-button:hover:not(:disabled) {
            background: #0a2445;
        }
        .token-button:disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }
        .token-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--slate);
            font-size: 0.9rem;
        }
        .token-footer a {
            color: var(--navy);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .token-footer a:hover {
            opacity: 0.8;
        }
        /* Decision Card Styling */
        .decision-card {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid var(--navy);
            box-shadow: 0 4px 12px rgba(12, 45, 90, 0.1);
        }
        .decision-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .decision-header svg {
            width: 24px;
            height: 24px;
        }
        .decision-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .decision-info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .decision-info-item:last-child {
            border-bottom: none;
        }
        .decision-info-label {
            font-weight: 600;
            color: var(--slate-dark);
        }
        .decision-info-value {
            color: var(--slate);
            font-weight: 500;
        }
        .decision-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .decision-continue {
            background: var(--navy);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-align: center;
        }
        .decision-continue:hover {
            background: #0a2445;
        }
        .decision-abandon {
            background: white;
            color: var(--navy);
            border: 2px solid var(--navy);
            border-radius: 0.5rem;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .decision-abandon:hover {
            background: #f8fafc;
        }
        .decision-or {
            text-align: center;
            color: var(--slate);
            margin: 1rem 0;
            font-weight: 500;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="text-slate-800">
    <!-- Hero Section -->
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
        
        <!-- SECTION 1: Input Token -->
        <section id="token-section">
            <h2 class="section-title">Lanjutkan Audit</h2>
            <p class="section-description">Masukkan Token Unik yang Anda dapatkan saat memulai audit.</p>
            
            @if($errors->any())
                <div class="bg-red-50 text-red-700 p-3 rounded-lg mb-4 text-sm border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form id="resumeForm" action="{{ route('audit.resume.validate') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700">
                        Resume Token
                    </label>
                    <input
                        type="text"
                        name="resume_token"
                        id="resume_token"
                        required
                        autocomplete="off"
                        placeholder="XXX-XXX"
                        class="token-input"
                        maxlength="7"
                        oninput="formatTokenInput(this)"
                    >
                </div>

                <button
                    id="submitBtn"
                    type="submit"
                    class="token-button"
                >
                    <span id="btnText">Cek Token</span>
                    <span>→</span>
                    
                    <svg id="loadingIcon" class="hidden w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </form>

            <div class="token-footer">
                <a href="{{ route('audit.create') }}">
                    ← Kembali ke Buat Audit Baru
                </a>
            </div>
        </section>

        <!-- SECTION 2: Decision Gate (Hidden by default) -->
        @if(isset($showDecision) && $showDecision)
        <section id="decision-section" class="decision-card">
            <div class="decision-header">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Audit Ditemukan
            </div>

            <div class="decision-info">
                <div class="decision-info-item">
                    <span class="decision-info-label">TOKEN</span>
                    <span class="decision-info-value">{{ $token }}</span>
                </div>
                <div class="decision-info-item">
                    <span class="decision-info-label">Auditor</span>
                    <span class="decision-info-value">{{ $auditorName }}</span>
                </div>
                <div class="decision-info-item">
                    <span class="decision-info-label">Target Departemen</span>
                    <span class="decision-info-value">{{ $auditeeDept }}</span>
                </div>
                <div class="decision-info-item">
                    <span class="decision-info-label">Tanggal Audit</span>
                    <span class="decision-info-value">{{ $auditDate }}</span>
                </div>
                <div class="decision-info-item">
                    <span class="decision-info-label">Terakhir Aktif</span>
                    <span class="decision-info-value">{{ $lastActivity }}</span>
                </div>
            </div>

            <form id="decisionForm" action="{{ route('audit.resume.handle') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="audit_id" value="{{ $auditId }}">

                <div class="decision-actions">
                    <button type="submit" name="action" value="continue" class="decision-continue">
                        Lanjutkan Audit Ini
                    </button>
                    <button type="submit" name="action" value="abandon" class="decision-abandon">
                        Batalkan & Buat Baru
                    </button>
                </div>

                <div class="decision-or">
                    <span>Masuk kembali ke menu & data tersimpan</span>
                </div>
            </form>
        </section>
        @endif

    </div>

    <script>
        // Format token input (auto-add hyphen)
        function formatTokenInput(input) {
            let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (value.length > 3) {
                value = value.substring(0, 3) + '-' + value.substring(3, 6);
            }
            input.value = value;
        }

        // Form submission handler
        const resumeForm = document.getElementById('resumeForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingIcon = document.getElementById('loadingIcon');
        const btnText = document.getElementById('btnText');

        if (resumeForm) {
            resumeForm.addEventListener('submit', function(e) {
                // Prevent double submission
                if (submitBtn.disabled) return;
                
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                
                // Show loading state
                loadingIcon.classList.remove('hidden');
                btnText.innerText = 'MEMPROSES...';
            });
        }
    </script>
</body>
</html>