<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Kurir App - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { overscroll-behavior-y: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-200 flex justify-center items-center min-h-screen">

    <div class="w-full max-w-[400px] h-[100dvh] sm:h-[850px] sm:max-h-[90vh] sm:rounded-[2.5rem] bg-gray-50 flex flex-col relative shadow-2xl overflow-hidden border-4 border-gray-800">

        <header class="bg-[#0f5156] text-white px-5 py-5 flex justify-between items-center z-10 shrink-0 shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#16787f] rounded-full flex items-center justify-center font-bold text-lg shadow-inner">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="font-bold text-sm tracking-wide">{{ Auth::user()->name }}</h1>
                    <p class="text-[11px] text-teal-200 font-medium">Kurir Lapangan</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 hover:bg-[#16787f] rounded-xl transition-colors" title="Keluar">
                    <i data-lucide="log-out" class="w-5 h-5 text-teal-50"></i>
                </button>
            </form>
        </header>

        <main class="flex-1 overflow-y-auto no-scrollbar pb-24 relative scroll-smooth">
            @yield('content')
        </main>

        <nav class="absolute bottom-0 w-full bg-white border-t border-gray-100 flex justify-around items-center h-16 z-20 shrink-0 rounded-b-[2rem] sm:rounded-b-[2.2rem] shadow-[0_-4px_20px_rgba(0,0,0,0.02)]">

            <a href="{{ route('courier.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('courier.dashboard') ? 'text-[#0f5156]' : 'text-gray-400' }}">
                <i data-lucide="home" class="w-5 h-5 mb-1 {{ request()->routeIs('courier.dashboard') ? 'fill-[#0f5156]' : '' }}"></i>
                <span class="text-[10px] font-bold">Home</span>
            </a>

            <a href="#" class="flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-[#16787f] transition-colors">
                <i data-lucide="calendar" class="w-5 h-5 mb-1"></i>
                <span class="text-[10px] font-semibold">Jadwal</span>
            </a>

            <div class="relative w-full flex justify-center -mt-8">
                <button class="bg-[#0f5156] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-[0_4px_15px_rgba(15,81,86,0.4)] border-4 border-gray-50 hover:bg-[#0a3d41] transition-colors transform hover:scale-105">
                    <i data-lucide="qr-code" class="w-6 h-6"></i>
                </button>
            </div>

            <a href="#" class="flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-[#16787f] transition-colors">
                <i data-lucide="history" class="w-5 h-5 mb-1"></i>
                <span class="text-[10px] font-semibold">Riwayat</span>
            </a>

            <a href="#" class="flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-[#16787f] transition-colors">
                <i data-lucide="user" class="w-5 h-5 mb-1"></i>
                <span class="text-[10px] font-semibold">Akun</span>
            </a>

        </nav>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
