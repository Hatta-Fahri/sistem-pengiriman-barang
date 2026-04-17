<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KEN Logistics</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

        <div class="flex flex-col text-center">
            <div class="bg-red-600 text-white py-2 px-4">
                <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase">PT. Kiriman Ekspres Nusantara</p>
            </div>

            <div class="bg-white py-6 px-4 flex flex-col items-center justify-center border-b border-gray-100">
                <div class="flex items-center gap-2 text-blue-800">
                    <i data-lucide="chevrons-right" class="w-10 h-10 text-red-600"></i>
                    <h1 class="text-4xl font-extrabold tracking-tight italic">KEN</h1>
                </div>
                <h2 class="text-xl font-bold text-red-600 tracking-widest uppercase mt-1">Logistics</h2>
            </div>

            <div class="bg-blue-800 text-white py-2 px-4 shadow-inner">
                <p class="text-[11px] font-semibold tracking-[0.2em]">HANDAL . CEPAT . AMAN</p>
            </div>
        </div>

        <div class="p-8">
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Masuk ke Sistem</h3>
                <p class="text-sm text-gray-500 mt-1">Silakan masukkan email dan password Anda</p>
            </div>

            @if ($errors->has('login_error') || $errors->any())
                <div class="mb-5 p-3 rounded-lg bg-red-50 text-red-600 text-sm flex items-start gap-3 border border-red-100">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <span class="font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 {{ $errors->has('email') ? 'text-red-400' : 'text-gray-400' }}"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-10 pr-3 py-2.5 border rounded-lg sm:text-sm transition-colors outline-none
                                {{ $errors->has('email')
                                    ? 'border-red-500 text-red-900 placeholder-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500'
                                    : 'border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600' }}"
                            placeholder="admin@kenlogistics.com">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 {{ $errors->has('password') ? 'text-red-400' : 'text-gray-400' }}"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-10 pr-3 py-2.5 border rounded-lg sm:text-sm transition-colors outline-none
                                {{ $errors->has('password')
                                    ? 'border-red-500 text-red-900 placeholder-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500'
                                    : 'border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600' }}"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center items-center gap-2 py-2.5 px-4 mt-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors">
                    <span>Masuk</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
