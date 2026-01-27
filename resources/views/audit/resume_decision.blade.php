<!DOCTYPE html>
<html lang="id">
<head>
    <title>Konfirmasi Resume | Internal Audit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-slate-100">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden">
        <div class="bg-[#0c2d5a] p-6 text-white text-center">
            <h2 class="text-xl font-bold">Audit Ditemukan</h2>
            <p class="opacity-80 text-sm">Token Valid: {{ $token }}</p>
        </div>

        <div class="p-8">
            <div class="space-y-4 mb-8">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500 text-sm">Lead Auditor</span>
                    <span class="font-semibold text-slate-800">{{ $auditorName }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500 text-sm">Departemen Auditee</span>
                    <span class="font-semibold text-slate-800">{{ $auditeeDept }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500 text-sm">Tanggal Audit</span>
                    <span class="font-semibold text-slate-800">{{ $auditDate }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500 text-sm">Aktivitas Terakhir</span>
                    <span class="font-semibold text-slate-800">{{ $lastActivity }}</span>
                </div>
            </div>

            <form action="{{ route('audit.resume.action') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <button type="submit" name="action" value="continue" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg flex items-center justify-between group transition">
                    <div class="text-left">
                        <div class="font-bold">Lanjutkan Audit Ini</div>
                        <div class="text-xs opacity-90">Masuk kembali ke menu audit</div>
                    </div>
                    <span class="text-xl group-hover:translate-x-1 transition">→</span>
                </button>

                <button type="submit" name="action" value="abandon" 
                        onclick="return confirm('Yakin ingin membatalkan audit ini? Status akan berubah menjadi ABANDONED dan tidak bisa diakses lagi.')"
                        class="w-full bg-white border border-red-200 hover:bg-red-50 text-red-700 p-4 rounded-lg flex items-center justify-between transition mt-4">
                    <div class="text-left">
                        <div class="font-bold">Batalkan & Buat Baru</div>
                        <div class="text-xs text-red-500">Tandai audit ini sebagai 'Abandoned'</div>
                    </div>
                    <span class="text-xl">✕</span>
                </button>
            </form>
        </div>
    </div>
</body>
</html>