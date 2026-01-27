<!DOCTYPE html>
<html lang="id">
<head>
    <title>Resume Audit | Internal Audit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }</style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full border border-slate-200">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Lanjutkan Audit</h1>
            <p class="text-slate-500 text-sm mt-1">Masukkan Token Unik yang Anda dapatkan saat memulai audit.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-3 rounded mb-4 text-sm border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('audit.resume.check') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Resume Token</label>
                <input type="text" name="resume_token" required autocomplete="off"
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#0c2d5a] focus:outline-none uppercase tracking-[0.2em] text-center text-xl font-bold text-slate-700 placeholder:tracking-normal"
                       placeholder="XXX-XXX">
            </div>

            <button type="submit" class="w-full bg-[#0c2d5a] hover:bg-[#0a2445] text-white font-bold py-3 rounded-lg transition shadow-md">
                Cek Token
            </button>
        </form>

        <div class="mt-6 text-center pt-4 border-t border-slate-100">
            <a href="{{ route('audit.create') }}" class="text-sm text-slate-500 hover:text-[#0c2d5a] font-medium transition">
                ← Kembali ke Buat Audit Baru
            </a>
        </div>
    </div>
</body>
</html>