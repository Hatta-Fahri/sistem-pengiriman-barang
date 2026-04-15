@extends('layouts.app')

@section('header-title', 'Penjadwalan Pengiriman')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Data Jadwal (Manifest)</h2>
            <p class="text-gray-500 text-sm mt-1">Kelompokkan paket ke dalam truk dan tugaskan kurir.</p>
        </div>
        <a href="#" class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-blue-800 transition-colors">
            <i data-lucide="truck" class="w-5 h-5"></i>
            <span>Buat Jadwal Truk</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <i data-lucide="calendar-clock" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
        <h3 class="text-lg font-bold text-gray-900 mb-1">Fitur Penjadwalan Segera Hadir</h3>
        <p class="text-gray-500">Di sinilah nanti Admin akan memuat resi-resi yang tertunda ke dalam kendaraan.</p>
    </div>
</div>
@endsection
