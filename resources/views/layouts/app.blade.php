<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F4F5F7]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PT. Ken Ekspres Nusantara' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #bfdbfe; }
    </style>
</head>

<body class="h-full overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-[#0B1A42]/20 backdrop-blur-sm lg:hidden" x-cloak></div>

        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-sm transition-transform duration-300 lg:static lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="h-full flex flex-col">

                <div class="px-6 py-6 flex items-center justify-between">
                    <img src="{{ asset('icon.svg') }}" alt="KEN Logistics" class="h-14 w-auto object-contain pointer-events-none">

                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-500 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar mt-2">
                    @include('layouts.partials.sidebar-menu')
                </nav>

                <div class="px-4 py-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2.5 bg-red-50 text-red-600 rounded-xl transition-all font-bold group hover:bg-red-100">
                            <i data-lucide="log-out" class="w-[18px] h-[18px] group-hover:-translate-x-1 transition-transform"></i>
                            <span class="text-[14px]">Keluar</span>
                        </button>
                    </form>
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-3 px-2 py-1">
                        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-blue-950 truncate">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                            <p class="text-xs font-semibold text-gray-500 capitalize">{{ Auth::user()->role ?? 'Role' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            <header class="bg-white/80 backdrop-blur-md h-16 flex items-center justify-between px-4 sm:px-8 flex-shrink-0 z-30 sticky top-0 border-b border-gray-100 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <div class="flex items-center gap-3 sm:gap-4">
                    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-blue-700 p-2 -ml-2 rounded-lg lg:hidden transition-colors">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="font-bold text-blue-950 text-xl tracking-tight">@yield('header-title', 'Overview')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <button class="relative text-gray-400 hover:text-blue-700 transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white box-content"></span>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>
