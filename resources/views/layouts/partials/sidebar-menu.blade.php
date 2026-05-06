<a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('courier.dashboard') }}"
    class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
   {{ request()->is('*/dashboard') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium' }}">
    <i data-lucide="layout-grid" class="w-[18px] h-[18px] {{ request()->is('*/dashboard') ? 'text-blue-600' : '' }}"></i>
    <span class="text-[14px]">Dashboard</span>
</a>

@if (Auth::user()->role === 'admin')
    <div class="pt-6 pb-2 px-3 flex items-center gap-2">
        <div class="w-1 h-3 bg-blue-500 rounded-full"></div>
        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Operasional</span>
    </div>

    <a href="{{ route('shipments.index') }}"
        class="flex items-center justify-between px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->is('admin/shipments*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <div class="flex items-center gap-3">
            <i data-lucide="package" class="w-[18px] h-[18px] {{ request()->is('admin/shipments*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
            <span class="text-[14px]">Pengiriman</span>
        </div>
    </a>

    <a href="{{ route('manifests.index') }}"
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

    <a href="{{ route('shipping-rates.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->is('admin/shipping-rates*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <i data-lucide="map" class="w-[18px] h-[18px] {{ request()->is('admin/shipping-rates*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
        <span class="text-[14px]">Rute & Tarif</span>
    </a>

    {{-- Manajemen Pengguna (CRUD Admin & Kurir) --}}
    <a href="{{ route('users.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->is('admin/users*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <i data-lucide="shield-check" class="w-[18px] h-[18px] {{ request()->is('admin/users*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
        <span class="text-[14px]">Manajemen Pengguna</span>
    </a>

    <a href="{{ route('vehicles.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
    {{ request()->is('admin/vehicles*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <i data-lucide="truck" class="w-[18px] h-[18px] {{ request()->is('admin/vehicles*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
        <span class="text-[14px]">Armada Kendaraan</span>
    </a>

    <div class="pt-6 pb-2 px-3 flex items-center gap-2">
        <div class="w-1 h-3 bg-purple-500 rounded-full"></div>
        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Analitik</span>
    </div>

    <a href="{{ route('reports.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl transition-all
    {{ request()->is('admin/reports*') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <i data-lucide="bar-chart-2" class="w-[18px] h-[18px] {{ request()->is('admin/reports*') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
        <span class="text-[14px]">Pusat Laporan</span>
    </a>
@endif

@if (Auth::user()->role === 'kurir')
    <div class="pt-6 pb-2 px-3 flex items-center gap-2">
        <div class="w-1 h-3 bg-orange-500 rounded-full"></div>
        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Tugas Lapangan</span>
    </div>

    <a href="{{ route('courier.shipments') }}"
        class="flex items-center justify-between px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->routeIs('courier.shipments') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <div class="flex items-center gap-3">
            <i data-lucide="list-checks" class="w-[18px] h-[18px] {{ request()->routeIs('courier.shipments') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
            <span class="text-[14px]">Daftar & Update Paket</span>
        </div>
    </a>

    <a href="{{ route('courier.history.index') }}"
        class="flex items-center justify-between px-3 py-2.5 mb-1 rounded-xl transition-all
       {{ request()->routeIs('courier.history.index') ? 'bg-blue-50 text-blue-800 font-bold shadow-sm border border-blue-100/50' : 'text-gray-500 hover:bg-gray-50 hover:text-blue-700 font-medium group' }}">
        <div class="flex items-center gap-3">
            <i data-lucide="history" class="w-[18px] h-[18px] {{ request()->routeIs('courier.history.index') ? 'text-blue-600' : 'group-hover:text-blue-600 transition-colors' }}"></i>
            <span class="text-[14px]">Riwayat Tugas Selesai</span>
        </div>
    </a>
@endif
