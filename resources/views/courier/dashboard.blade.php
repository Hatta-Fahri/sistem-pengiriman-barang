@extends('layouts.app')

@section('header-title', 'Tugas Pengiriman')

@section('content')
<div class="max-w-4xl mx-auto pb-20"> <div class="bg-blue-900 text-white rounded-2xl p-6 mb-6 shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-8 -mt-8 opacity-10">
            <i data-lucide="truck" class="w-48 h-48"></i>
        </div>

        <div class="relative z-10">
            <h2 class="text-2xl font-bold tracking-tight mb-1">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-blue-200 text-sm mb-6">Tetap utamakan keselamatan di jalan.</p>

            <div class="flex flex-col sm:flex-row gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 flex-1 border border-white/10">
                    <p class="text-blue-200 text-xs font-semibold uppercase tracking-wider mb-1">Belum Selesai</p>
                    <p class="text-3xl font-extrabold">5 <span class="text-base font-normal opacity-80">Paket</span></p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 flex-1 border border-white/10">
                    <p class="text-blue-200 text-xs font-semibold uppercase tracking-wider mb-1">Terkirim</p>
                    <p class="text-3xl font-extrabold">12 <span class="text-base font-normal opacity-80">Paket</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-2 mb-6 border-b border-gray-200 pb-2">
        <button class="px-4 py-2 text-sm font-semibold text-blue-700 border-b-2 border-blue-700">Dalam Pengantaran</button>
        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Menunggu Diambil</button>
    </div>

    <div class="space-y-4">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-blue-200 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        Sedang Diantar
                    </span>
                    <h3 class="font-bold text-gray-900 text-lg tracking-tight">KEN-9876543210</h3>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 font-medium">COD</p>
                    <p class="font-bold text-red-600">Rp 150.000</p>
                </div>
            </div>

            <div class="space-y-3 mb-5 border-t border-gray-50 pt-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="user" class="w-5 h-5 text-gray-400 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Ahmad Subarjo</p>
                        <p class="text-sm text-gray-600">0812-3456-7890</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i data-lucide="map-pin" class="w-5 h-5 text-red-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Jl. Merdeka Raya No. 45, RT 02/RW 03, Kec. Siantar Timur, Pematangsiantar (Pagar Hitam depan Indomaret)
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center justify-center p-3 bg-green-50 text-green-600 rounded-xl hover:bg-green-100 transition-colors">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                </a>
                <button class="flex-1 bg-blue-700 text-white font-semibold py-3 rounded-xl hover:bg-blue-800 transition-colors flex justify-center items-center gap-2 shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Selesaikan Pengiriman
                </button>
            </div>
        </div>

        </div>
</div>
@endsection
