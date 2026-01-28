<!DOCTYPE html>
<html lang="id">
<head>
    <title>Konfirmasi Resume | Internal Audit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4 sm:px-6">

    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden border border-slate-200">

        <!-- Header -->
        <div class="bg-[#0c2d5a] px-5 py-6 sm:p-6 text-white text-center">
            <h2 class="text-lg sm:text-xl font-bold">
                Audit Ditemukan
            </h2>
            <div class="inline-block bg-white/20 px-3 py-1 rounded text-xs mt-2 font-mono tracking-wider">
                TOKEN: {{ $token }}
            </div>
        </div>

        <!-- Content -->
        <div class="p-5 sm:p-8">

            <!-- Info -->
            <div class="space-y-3 mb-6 sm:mb-8 bg-slate-50 p-4 rounded-lg border border-slate-100 text-sm">

                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Auditor</span>
                    <span class="font-bold text-slate-800">{{ $auditorName }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Target Departemen</span>
                    <span class="font-bold text-slate-800">{{ $auditeeDept }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Tanggal Audit</span>
                    <span class="font-semibold text-slate-700">{{ $auditDate }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between gap-1 pt-1 text-xs italic">
                    <span class="text-slate-500">Terakhir aktif</span>
                    <span class="text-slate-600">{{ $lastActivity }}</span>
                </div>
            </div>

            <!-- Actions -->
            <form action="{{ route('audit.resume.action') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="audit_id" value="{{ $auditId }}">

                <!-- Continue -->
<button
    type="submit"
    name="action"
    value="continue"
    data-action-btn
    class="w-full bg-emerald-600 hover:bg-emerald-700
           text-white p-4 rounded-lg
           flex items-center justify-between
           transition shadow-md hover:shadow-lg active:scale-[0.98]">

    <div class="text-left">
        <div class="font-bold text-base sm:text-lg">
            Lanjutkan Audit Ini
        </div>
        <div class="text-xs opacity-90">
            Masuk kembali ke menu & data tersimpan
        </div>
    </div>

    <div class="flex items-center gap-2">
        <svg class="loadingIcon hidden w-5 h-5 animate-spin text-white"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <span class="arrow text-xl sm:text-2xl font-light">→</span>
    </div>
</button>


                <!-- Divider -->
                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="mx-4 text-slate-400 text-xs uppercase font-bold">Atau</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Abandon -->
 <button
    type="submit"
    name="action"
    value="abandon"
    data-action-btn
    onclick="return confirm('PERINGATAN: Audit lama akan dibatalkan permanen dan token ini hangus. Anda akan diarahkan untuk membuat audit baru. Lanjutkan?')"
    class="w-full bg-white border-2 border-slate-200
           hover:border-red-200 hover:bg-red-50
           text-slate-600 hover:text-red-700
           p-3 rounded-lg
           flex items-center justify-center gap-2
           transition font-semibold text-sm active:scale-[0.98]">

    <svg class="trashIcon h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
    </svg>

    <svg class="loadingIcon hidden w-4 h-4 animate-spin text-red-600"
         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
    </svg>

    <span class="btnText">Batalkan & Buat Baru</span>
</button>

            </form>
        </div>
    </div>
<script>
    const form = document.querySelector('form');
    const actionButtons = document.querySelectorAll('[data-action-btn]');

    let clickedButton = null;

    actionButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            clickedButton = btn;
        });
    });

    form.addEventListener('submit', () => {
        actionButtons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');

            const spinner = btn.querySelector('.loadingIcon');
            const arrow   = btn.querySelector('.arrow');
            const text    = btn.querySelector('.btnText');

            if (btn === clickedButton) {
                if (spinner) spinner.classList.remove('hidden');
                if (arrow) arrow.classList.add('hidden');
                if (text) text.innerText = 'MEMPROSES...';
            }
        });
    });
</script>

</body>
</html>
