<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KEN Logistics</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Lottie Player Script -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 sm:p-8">

    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">

        <!-- SISI KIRI: Branding & Animasi Lottie -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-50 to-blue-100/50 flex flex-col justify-center items-center p-8 md:p-12 border-b md:border-b-0 md:border-r border-blue-100/50 relative overflow-hidden">

            <!-- Ornamen Background -->
            <div class="absolute top-0 left-0 w-full h-2 bg-red-600"></div>

            <!-- Header Logo -->
            <div class="text-center z-10 w-full mb-6">
                <div class="flex items-center justify-center gap-2 text-blue-800">
                    <i data-lucide="chevrons-right" class="w-10 h-10 text-red-600"></i>
                    <h1 class="text-4xl font-extrabold tracking-tight italic">KEN</h1>
                </div>
                <h2 class="text-xl font-bold text-red-600 tracking-widest uppercase mt-1">Logistics</h2>
                <div class="mt-4 inline-block bg-blue-800 text-white py-1.5 px-4 rounded-full shadow-sm">
                    <p class="text-[10px] font-bold tracking-[0.2em]">HANDAL . CEPAT . AMAN</p>
                </div>
            </div>

            <!-- Lottie Animation Container -->
            <div class="w-full max-w-[280px] sm:max-w-[320px] relative z-10 drop-shadow-md">
                <lottie-player
                    src="{{ asset('animation.json') }}"
                    background="transparent"
                    speed="1"
                    style="width: 100%; height: auto;"
                    loop
                    autoplay>
                </lottie-player>
            </div>

        </div>

        <!-- SISI KANAN: Form Login -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white">

            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Masuk ke Sistem</h3>
                <p class="text-sm text-gray-500 mt-2 font-medium">Selamat datang kembali! Silakan masukkan email dan password Anda.</p>
            </div>

            @if ($errors->has('login_error') || $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 text-sm flex items-start gap-3 border border-red-100 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500"></i>
                    <span class="font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 {{ $errors->has('email') ? 'text-red-400' : 'text-gray-400' }}"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-3 border rounded-xl sm:text-sm transition-all outline-none font-medium text-gray-900
                                {{ $errors->has('email')
                                    ? 'border-red-300 bg-red-50 text-red-900 placeholder-red-300 focus:ring-2 focus:ring-red-600 focus:border-transparent'
                                    : 'border-gray-200 bg-gray-50 hover:bg-white focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-sm' }}"
                            placeholder="admin@kenlogistics.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs text-red-600 font-bold flex items-center gap-1.5">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 {{ $errors->has('password') ? 'text-red-400' : 'text-gray-400' }}"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-11 pr-4 py-3 border rounded-xl sm:text-sm transition-all outline-none font-medium text-gray-900
                                {{ $errors->has('password')
                                    ? 'border-red-300 bg-red-50 text-red-900 placeholder-red-300 focus:ring-2 focus:ring-red-600 focus:border-transparent'
                                    : 'border-gray-200 bg-gray-50 hover:bg-white focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-sm' }}"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs text-red-600 font-bold flex items-center gap-1.5">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center group">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded cursor-pointer transition-colors">
                        <label for="remember" class="ml-2.5 block text-sm font-medium text-gray-600 cursor-pointer group-hover:text-gray-900 transition-colors">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 mt-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all hover:shadow-md hover:-translate-y-0.5">
                    <span>Masuk Dashboard</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs font-medium text-gray-400">&copy; {{ date('Y') }} PT. Ken Ekspres Nusantara</p>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
