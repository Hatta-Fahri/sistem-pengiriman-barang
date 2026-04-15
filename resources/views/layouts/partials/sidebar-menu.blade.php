<a href="{{ Auth::user()->role === 'admin' ? url('/admin/dashboard') : url('/courier/dashboard') }}"
    class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
   {{ request()->is('*/dashboard') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium' }}">
    <i data-lucide="layout-grid" class="w-[18px] h-[18px] {{ request()->is('*/dashboard') ? 'text-blue-600' : '' }}"></i>
    <span class="text-[14px]">Dashboard</span>
</a>

@if (Auth::user()->role === 'admin')

    <a href="{{ url('/admin/shipments') }}"
       class="flex items-center justify-between px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->is('admin/shipments*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <div class="flex items-center gap-3">
            <i data-lucide="package" class="w-[18px] h-[18px] {{ request()->is('admin/shipments*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
            <span class="text-[14px]">Pengiriman (Resi)</span>
        </div>
        <span class="bg-orange-100 text-orange-600 py-0.5 px-2 rounded-full text-[10px] font-bold">12</span>
    </a>

    <a href="{{ url('/admin/manifests') }}"
       class="flex items-center justify-between px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->is('admin/manifests*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <div class="flex items-center gap-3">
            <i data-lucide="calendar-clock" class="w-[18px] h-[18px] {{ request()->is('admin/manifests*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
            <span class="text-[14px]">Penjadwalan</span>
        </div>
    </a>

    <div class="pt-6 pb-2 px-3 flex items-center gap-2">
        <div class="w-1 h-3 bg-red-500 rounded-full"></div>
        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Master Data</span>
    </div>

    <a href="{{ url('/admin/shipping-rates') }}"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->is('admin/shipping-rates*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <i data-lucide="map" class="w-[18px] h-[18px] {{ request()->is('admin/shipping-rates*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
        <span class="text-[14px]">Rute & Tarif</span>
    </a>

    <a href="#"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <i data-lucide="users" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
        <span class="text-[14px]">Manajemen Kurir</span>
    </a>

    <a href="#"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <i data-lucide="truck" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
        <span class="text-[14px]">Armada Kendaraan</span>
    </a>

@endif

@if (Auth::user()->role === 'kurir')
    <div class="pt-6 pb-2 px-3 flex items-center gap-2">
        <div class="w-1 h-3 bg-red-500 rounded-full"></div>
        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Operasional</span>
    </div>

    <a href="#"
        class="flex items-center justify-between px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <div class="flex items-center gap-3">
            <i data-lucide="map-pin" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
            <span class="text-[14px]">Tugas Hari Ini</span>
        </div>
        <span
            class="bg-blue-100 text-blue-700 py-0.5 px-2 rounded-full text-[10px] font-bold group-hover:bg-blue-200 transition-colors">5</span>
    </a>
    <a href="#"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 text-gray-500 hover:bg-gray-50 hover:text-blue-700 rounded-xl transition-all font-medium group">
        <i data-lucide="history" class="w-[18px] h-[18px] group-hover:text-blue-600 transition-colors"></i>
        <span class="text-[14px]">Riwayat Selesai</span>
    </a>
@endif

<div class="mt-8 px-2">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
            class="w-full flex items-center gap-3 px-3 py-2.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all font-medium group">
            <i data-lucide="log-out" class="w-[18px] h-[18px] group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-[14px]">Keluar</span>
        </button>
    </form>
</div>
