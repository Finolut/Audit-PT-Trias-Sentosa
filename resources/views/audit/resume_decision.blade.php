<!DOCTYPE html>
<html lang="id">
<head>
    <title>Konfirmasi Resume | Internal Audit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="flex items-center justify-center min-h-screen bg-slate-100">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="bg-[#0c2d5a] p-6 text-white text-center relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-xl font-bold">Audit Ditemukan</h2>
                <div class="inline-block bg-white/20 px-3 py-1 rounded text-xs mt-2 font-mono tracking-wider">
                    TOKEN: {{ $token }}
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="space-y-4 mb-8 bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                    <span class="text-slate-500 text-sm">Lead Auditor</span>
                    <span class="font-bold text-slate-800">{{ $auditorName }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                    <span class="text-slate-500 text-sm">Target Departemen</span>
                    <span class="font-bold text-slate-800">{{ $auditeeDept }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                    <span class="text-slate-500 text-sm">Tanggal Audit</span>
                    <span class="font-semibold text-slate-700">{{ $auditDate }}</span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="text-slate-500 text-xs italic">Terakhir aktif</span>
                    <span class="text-slate-600 text-xs italic">{{ $lastActivity }}</span>
                </div>
            </div>

            <form action="{{ route('audit.resume.action') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="audit_id" value="{{ $auditId }}">
                
                <button type="submit" name="action" value="continue" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white p-4 rounded-lg flex items-center justify-between group transition shadow-md hover:shadow-lg">
                    <div class="text-left">
                        <div class="font-bold text-lg">Lanjutkan Audit Ini</div>
                        <div class="text-xs opacity-90 font-light">Masuk kembali ke menu & data tersimpan</div>
                    </div>
                    <span class="text-2xl font-light group-hover:translate-x-1 transition">→</span>
                </button>

                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink-0 mx-4 text-slate-400 text-xs uppercase font-bold">Atau</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <button type="submit" name="action" value="abandon" 
                        onclick="return confirm('PERINGATAN: Audit lama akan dibatalkan permanen dan token ini hangus. Anda akan diarahkan untuk membuat audit baru. Lanjutkan?')"
                        class="w-full bg-white border-2 border-slate-200 hover:border-red-200 hover:bg-red-50 text-slate-600 hover:text-red-700 p-3 rounded-lg flex items-center justify-center gap-2 transition font-semibold text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Batalkan & Buat Baru
                </button>
            </form>
        </div>
    </div>
</body>
</html>