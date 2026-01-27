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
        .bg-custom-gray { background-color: #F8FAFC; }
        .text-primary-blue { color: #1a365d; }
        .bg-primary-blue { background-color: #1a365d; }
        .hover-bg-yellow:hover { background-color: #FFD700; }
        .hover-bg-primary-blue:hover { background-color: #1e40af; }
        .btn-glow {
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 6px 16px rgba(255, 215, 0, 0.5);
            transform: translateY(-2px);
        }
.hero-image {
    background-image: url('https://trias-sentosa.com/images/about2.webp');
    background-size: cover;
}

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-custom-gray flex flex-col min-h-screen">
    <main class="grow">

<header class="w-full px-4 py-3 md:py-4 md:px-12 flex justify-between items-center bg-white shadow-sm">
    
    <!-- Logo -->
    <div class="flex items-center">
        <img 
            src="https://trias-sentosa.com/images/logo.webp"
            alt="Logo PT Trias Sentosa Tbk"
            class="h-8 md:h-12 object-contain"
        >
    </div>

    <!-- Admin Button -->
    <a href="{{ route('admin.dashboard') }}"
       class="bg-yellow-500 hover:bg-yellow-600 text-blue-900 
              font-bold text-xs md:text-base 
              px-3 py-2 md:px-6 md:py-2.5 
              rounded-lg transition-all 
              flex items-center gap-1.5 md:gap-2 shadow-md">
        
        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <span>ADMIN</span>
    </a>

</header>


    <!-- Hero Section -->
    <section class="relative hero-image h-[80vh] md:h-screen flex items-center justify-center">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-10 text-white text-center px-6 max-w-4xl">
            <h2 class="text-3xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-4">
                BOPP, BOPET & CPP FILMS MANUFACTURER
            </h2>
            <p class="text-lg md:text-xl font-medium mb-8">
                FROM OUR MARKET IN INDONESIA TO ANYWHERE IN THE WORLD
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('audit.setup') }}" 
                   class="bg-primary-blue hover-bg-primary-blue text-white font-bold py-3 px-8 rounded-lg transition-all inline-flex items-center justify-center gap-2 card-hover">
                    Start Audit
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="https://trias-sentosa.com/home" 
                   class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-lg transition-all inline-flex items-center justify-center gap-2 hover:bg-white hover:text-primary-blue card-hover">
                    Learn More
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>


    <!-- Footer -->
<footer class="bg-primary-blue text-white py-5 md:py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-12 text-center">
        <p class="text-[10px] md:text-xs 
                  font-mono font-bold uppercase 
                  tracking-[0.2em] md:tracking-[0.3em] 
                  leading-relaxed md:leading-normal">
            © 2026 PT Trias Sentosa Tbk. All rights reserved.
        </p>
    </div>
    </main>
</footer>



</body>
</html>