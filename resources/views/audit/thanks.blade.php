<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Audit Selesai | PT Trias Sentosa Tbk</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 text-center">
        <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-black text-gray-800 mb-4">Terima Kasih!</h1>
        
        <p class="text-gray-600 leading-relaxed mb-8">
            Audit lapangan telah berhasil diselesaikan. Seluruh data pemeriksaan telah tersimpan dengan aman di sistem kami dan status audit telah diperbarui menjadi <span class="font-bold text-green-600">SELESAI</span>.
        </p>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-8 text-sm text-blue-700 italic">
            "Feedback Anda sangat berharga bagi kami untuk terus meningkatkan standar kualitas dan kepatuhan operasional perusahaan."
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('landing') }}" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                Kembali ke Halaman Utama
            </a>
            <p class="text-xs text-gray-400">PT Trias Sentosa Tbk - Management System</p>
        </div>
    </div>
</body>
</html>