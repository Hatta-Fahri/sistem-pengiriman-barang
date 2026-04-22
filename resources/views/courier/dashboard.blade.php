@extends('layouts.app')

@section('header-title', 'Tugas Hari Ini')

@section('content')
    <div class="w-full space-y-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Ringkasan Tugas Kurir</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau rute perjalanan dan total muatan paket yang Anda bawa hari ini.</p>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if ($activeManifest)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border-l-4 border-l-blue-600 group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                            <i data-lucide="siren" class="w-5 h-5 text-blue-600"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Status Anda</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dalam Tugas</h3>
                    </div>
                    <div class="mt-4">
                        <span
                            class="text-xs text-blue-600 font-bold bg-blue-50 px-2.5 py-1 rounded-md">{{ $activeManifest->manifest_code }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-orange-50 p-2.5 rounded-xl border border-orange-100">
                            <i data-lucide="package" class="w-5 h-5 text-orange-600"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Muatan</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                                {{ $activeManifest->total_shipments }}</h3>
                            <span class="text-sm text-gray-500 font-bold">Paket</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-green-50 p-2.5 rounded-xl border border-green-100">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Sukses Diantar</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-extrabold text-green-600 tracking-tight">
                                {{ $totalDelivered ?? 0 }}</h3>
                            <span class="text-sm text-gray-500 font-bold">Resi</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-indigo-50 p-2.5 rounded-xl border border-indigo-100">
                            <i data-lucide="truck" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Armada Operasional</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight truncate">
                            {{ $activeManifest->vehicle->license_plate ?? 'Mobil' }}</h3>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <span
                            class="text-xs text-gray-400 font-medium uppercase truncate">{{ $activeManifest->vehicle->type ?? 'Kendaraan Logistik' }}</span>
                    </div>
                </div>

            </div>

            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden flex flex-col md:flex-row items-center justify-between p-6 md:p-8 relative">
                <div class="absolute -right-10 -top-10 opacity-5">
                    <i data-lucide="map" class="w-64 h-64"></i>
                </div>

                <div class="relative z-10 w-full mb-6 md:mb-0">
                    <p class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-2">Rute Pengiriman Hari Ini</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $activeManifest->jalur_pengiriman }}</h3>
                    <p class="text-sm text-gray-500 mt-2 max-w-lg">Buka menu Daftar Paket untuk mulai meng-update status
                        setiap resi yang telah sampai ke tangan pelanggan.</p>
                </div>

                <div class="relative z-10 shrink-0 w-full md:w-auto">
                    <a href="{{ route('courier.shipments') }}"
                        class="w-full md:w-auto inline-flex justify-center items-center gap-2 bg-blue-700 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-800 transition-all">
                        Lihat Daftar Paket <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="md:col-span-2 bg-white rounded-2xl p-16 text-center border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex flex-col items-center justify-center">
                    <div
                        class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4 border border-gray-100">
                        <i data-lucide="coffee" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Tugas Aktif</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Anda sedang tidak ditugaskan pada manifest apapun hari ini.
                        Silakan hubungi Admin Gudang jika ada jadwal yang terlewat.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex flex-col items-center justify-center text-center">
                    <div class="bg-green-50 p-4 rounded-full border border-green-100 mb-4">
                        <i data-lucide="award" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Kinerja Anda</p>
                    <h3 class="text-5xl font-black text-green-600 tracking-tight my-2">{{ $totalDelivered ?? 0 }}</h3>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Resi Selesai</span>
                </div>
            </div>
        @endif

    </div>
@endsection
