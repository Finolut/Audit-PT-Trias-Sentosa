<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT TRIAS SENTOSA Tbk - Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logots.png') }}" type="image/png">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        .bg-custom-gray { background-color: #E5E7EB; }
        .bg-hero-image {
            background: url('https://www.trias-sentosa.com/assets/images/hero.jpg') no-repeat center center;
            background-size: cover;
        }
        .text-primary-blue { color: #1a365d; }
        .bg-primary-blue { background-color: #1a365d; }
        .bg-yellow { background-color: #FFD700; }
        .hover-bg-yellow:hover { background-color: #FFC107; }
        .hover-bg-primary-blue:hover { background-color: #1e40af; }
    </style>
</head>
<body class="bg-white min-h-screen">

    <!-- Header -->
    <header class="p-6 md:p-8 w-full max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/ts.jpg') }}" alt="Logo PT Trias Sentosa Tbk" class="h-10 md:h-12 object-contain">
            <div class="border-l-2 border-gray-400 pl-4">
                <h1 class="text-base md:text-lg font-bold text-primary-blue leading-none uppercase tracking-tighter">
                    PT Trias Sentosa Tbk
                </h1>
                <p class="text-[8px] md:text-xs text-gray-500 font-medium uppercase tracking-[0.2em] mt-1">
                    Flexible Packaging Film Manufacturer
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative">
                <select class="bg-white border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none">
                    <option>EN</option>
                    <option>ID</option>
                </select>
            </div>
            <button class="bg-yellow hover:bg-yellow text-primary-blue font-bold py-2 px-6 rounded-lg transition-all flex items-center gap-2">
                MENU
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-hero-image h-screen flex items-center justify-center">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-white text-center px-6 max-w-4xl">
            <h2 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">
                BOPP, BOPET & CPP FILMS MANUFACTURER
            </h2>
            <p class="text-xl md:text-2xl font-medium mb-8">
                FROM OUR MARKET IN INDONESIA TO ANYWHERE IN THE WORLD
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('audit.setup') }}" 
                   class="bg-primary-blue hover-bg-primary-blue text-white font-bold py-3 px-8 rounded-lg transition-all inline-flex items-center justify-center gap-2">
                    Read More
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="#" 
                   class="bg-yellow hover-bg-yellow text-primary-blue font-bold py-3 px-8 rounded-lg transition-all inline-flex items-center justify-center gap-2">
                    Contact Us
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center gap-4">
                    <div class="bg-yellow p-3 rounded-full">
                        <svg class="w-8 h-8 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8v6h-8V7z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7H5a2 2 0 00-2 2v6a2 2 0 002 2h2v6a2 2 0 002 2h6a2 2 0 002-2v-6h2a2 2 0 002-2V9a2 2 0 00-2-2h-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-primary-blue mb-2">Our Products</h3>
                        <p class="text-gray-600">High-quality BOPP, BOPET & CPP films for various packaging applications.</p>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center gap-4">
                    <div class="bg-yellow p-3 rounded-full">
                        <svg class="w-8 h-8 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9M5 11V9m2 2a2 2 0 100 4h12a2 2 0 100-4M5 11a2 2 0 100 4h12a2 2 0 100-4M5 9h12v2M5 9v2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-primary-blue mb-2">Global Reach</h3>
                        <p class="text-gray-600">Serving customers from Indonesia to markets around the world.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary-blue text-white py-8">
        <div class="max-w-7xl mx-auto px-6 md:px-12 text-center">
            <p class="text-xs font-bold uppercase tracking-[0.3em]">
                &copy; 2026 PT Trias Sentosa Tbk. All Rights Reserved.
            </p>
        </div>
    </footer>

</body>
</html>