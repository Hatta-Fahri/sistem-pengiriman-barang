<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Tidak Ditemukan - KEN Logistics</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Figtree', sans-serif; }
        .bg-grid-pattern {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="antialiased bg-[#f8fafc] min-h-screen flex flex-col">

    <div class="relative flex-grow flex items-center justify-center px-4 py-16 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-30 z-0 pointer-events-none"></div>
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[500px] h-[500px] bg-blue-50 rounded-full blur-3xl opacity-60 pointer-events-none z-0"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[400px] h-[400px] bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none z-0"></div>

        <div class="relative z-10 max-w-md w-full text-center bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-gray-100 p-8 sm:p-10">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 -rotate-3">
                <i data-lucide="map-pin-off" class="w-8 h-8"></i>
            </div>

            <p class="text-xs font-black text-blue-600 uppercase tracking-widest mb-2">Error 404</p>
            <h1 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">Halaman Tidak Ditemukan</h1>
            <p class="text-gray-500 text-sm font-medium leading-relaxed mb-8">
                Halaman yang Anda cari tidak tersedia, sudah dipindahkan, atau alamatnya salah ketik.
            </p>

            <a href="{{ url('/') }}" class="inline-flex px-6 py-3 bg-blue-700 text-white font-bold rounded-xl hover:bg-blue-800 hover:shadow-lg transition-all items-center justify-center gap-2">
                <i data-lucide="home" class="w-4 h-4"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <footer class="relative z-10 text-center py-6">
        <p class="text-gray-400 text-xs font-medium">&copy; {{ date('Y') }} PT. Ken Ekspres Nusantara. Handal, Cepat, Aman.</p>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
