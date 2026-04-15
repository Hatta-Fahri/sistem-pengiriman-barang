<a href="{{ Auth::user()->role === 'admin' ? url('/admin/dashboard') : url('/courier/dashboard') }}"
   class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
   {{ request()->is('*/dashboard') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium' }}">
    <i data-lucide="layout-grid" class="w-[18px] h-[18px] {{ request()->is('*/dashboard') ? 'text-blue-600' : '' }}"></i>
    <span class="text-[14px]">Dashboard</span>
</a>

@if(Auth::user()->role === 'admin')

    <div x-data="{ open: true }" class="mb-1">
        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
            <div class="flex items-center gap-3">
                <i data-lucide="package" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
                <span class="text-[14px]">Pengiriman</span>
            </div>
            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform text-gray-400 group-hover:text-blue-400" :class="open ? 'rotate-180' : ''"></i>
        </button>

        <div x-show="open" x-collapse class="pl-10 pr-2 py-1 space-y-1">
            <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-[13px] transition-all bg-white shadow-sm border border-gray-100 text-blue-800 font-bold">
                <span>Semua Resi</span>
            </a>
            <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-[13px] text-gray-500 hover:text-red-700 hover:bg-red-50 transition-all font-medium group">
                <span>Tertunda</span>
                <span class="bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-[10px] font-bold group-hover:bg-red-200">3</span>
            </a>
            <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-[13px] text-gray-500 hover:text-blue-700 hover:bg-blue-50 transition-all font-medium group">
                <span>Selesai</span>
                <span class="bg-blue-100 text-blue-700 py-0.5 px-2 rounded-full text-[10px] font-bold group-hover:bg-blue-200">8</span>
            </a>
        </div>
    </div>

    <div class="pt-6 pb-2 px-3 flex items-center gap-2">
        <div class="w-1 h-3 bg-red-500 rounded-full"></div>
        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Master Data</span>
    </div>

    <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <i data-lucide="map" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
        <span class="text-[14px]">Rute & Tarif</span>
    </a>

    <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <i data-lucide="users" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
        <span class="text-[14px]">Manajemen Kurir</span>
    </a>

    <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <i data-lucide="truck" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
        <span class="text-[14px]">Armada Kendaraan</span>
    </a>
@endif

@if(Auth::user()->role === 'kurir')
    <div class="pt-6 pb-2 px-3 flex items-center gap-2">
        <div class="w-1 h-3 bg-red-500 rounded-full"></div>
        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Operasional</span>
    </div>

    <a href="#" class="flex items-center justify-between px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <div class="flex items-center gap-3">
            <i data-lucide="map-pin" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
            <span class="text-[14px]">Tugas Hari Ini</span>
        </div>
        <span class="bg-blue-100 text-blue-700 py-0.5 px-2 rounded-full text-[10px] font-bold group-hover:bg-blue-200 transition-colors">5</span>
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <i data-lucide="history" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
        <span class="text-[14px]">Riwayat Selesai</span>
    </a>
@endif

<div class="mt-8 px-2">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all font-medium group">
            <i data-lucide="log-out" class="w-[18px] h-[18px] group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-[14px]">Keluar</span>
        </button>
    </form>
</div>
