@extends('layouts.app')

@section('header-title', 'Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Ringkasan Bisnis</h2>
        <p class="text-gray-500 text-sm mt-1">Pantau performa pengiriman dan pendapatan KEN Logistics hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] relative overflow-hidden group hover:border-blue-200 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                    <i data-lucide="wallet" class="w-5 h-5 text-gray-600"></i>
                </div>
                <button class="text-gray-400 hover:text-gray-600"><i data-lucide="more-horizontal" class="w-5 h-5"></i></button>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Pendapatan</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">Rp 12.8M</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center gap-1 bg-[#D1F4E0] text-[#147D44] px-2 py-0.5 rounded-md text-[11px] font-bold">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> +12.5%
                </span>
                <span class="text-xs text-gray-400 font-medium">vs bulan lalu</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:border-blue-200 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                    <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Pengiriman Aktif</p>
                <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">512</h3>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-[11px] font-bold">
                    24 Kurir Jalan
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:border-blue-200 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-green-50 p-2.5 rounded-xl border border-green-100">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Paket Terkirim</p>
                <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">8,409</h3>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center gap-1 bg-[#D1F4E0] text-[#147D44] px-2 py-0.5 rounded-md text-[11px] font-bold">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> +4.2%
                </span>
                <span class="text-xs text-gray-400 font-medium">minggu ini</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:border-red-200 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-red-50 p-2.5 rounded-xl border border-red-100">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Kendala Pengiriman</p>
                <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">14</h3>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center gap-1 bg-[#FFE2E5] text-[#F64E60] px-2 py-0.5 rounded-md text-[11px] font-bold">
                    Segera Tindak Lanjuti
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">Aktivitas Pengiriman</h3>
                <select class="text-sm border-gray-200 rounded-lg text-gray-500 font-medium focus:ring-blue-500 focus:border-blue-500">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                </select>
            </div>

            <div class="h-64 w-full flex items-end gap-2 pt-10">
                <div class="w-full bg-blue-50 rounded-t-sm h-[30%] relative hover:bg-blue-100 transition-colors"><div class="absolute -top-6 w-full text-center text-xs text-gray-400 opacity-0 hover:opacity-100">40</div></div>
                <div class="w-full bg-blue-100 rounded-t-sm h-[50%] relative hover:bg-blue-200 transition-colors"></div>
                <div class="w-full bg-blue-200 rounded-t-sm h-[40%] relative hover:bg-blue-300 transition-colors"></div>
                <div class="w-full bg-blue-400 rounded-t-sm h-[70%] relative hover:bg-blue-500 transition-colors"></div>
                <div class="w-full bg-blue-600 rounded-t-sm h-[90%] relative shadow-[0_0_15px_rgba(37,99,235,0.3)]"><div class="absolute -top-7 w-full text-center text-xs font-bold text-blue-600">120</div></div>
                <div class="w-full bg-blue-300 rounded-t-sm h-[60%] relative hover:bg-blue-400 transition-colors"></div>
                <div class="w-full bg-blue-100 rounded-t-sm h-[45%] relative hover:bg-blue-200 transition-colors"></div>
            </div>
            <div class="flex justify-between mt-3 text-xs text-gray-400 font-medium">
                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Performa Kurir (Top 3)</h3>
            <div class="space-y-5">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=EFF6FF&color=1D4ED8" class="w-10 h-10 rounded-full border border-gray-100" alt="Budi">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-yellow-400 border-2 border-white rounded-full flex items-center justify-center text-[8px] text-white font-bold">1</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-900">Budi Santoso</h4>
                        <p class="text-xs text-gray-500">Area: Siantar Utara</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">142</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wide">Paket</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name=Arif+R&background=F3F4F6&color=374151" class="w-10 h-10 rounded-full border border-gray-100" alt="Arif">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-gray-300 border-2 border-white rounded-full flex items-center justify-center text-[8px] text-white font-bold">2</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-900">Arif Rahman</h4>
                        <p class="text-xs text-gray-500">Area: Siantar Barat</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">118</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wide">Paket</p>
                    </div>
                </div>
            </div>

            <button class="w-full mt-6 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Lihat Semua Kurir</button>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="text-lg font-bold text-gray-900">Update Pengiriman Terkini</h3>
            <button class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat Semua Data</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 font-semibold tracking-wider">No. Resi & Pengirim</th>
                        <th class="px-6 py-4 font-semibold tracking-wider">Tujuan</th>
                        <th class="px-6 py-4 font-semibold tracking-wider">Kurir</th>
                        <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                        <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">KEN-89342011</div>
                            <div class="text-gray-500 text-xs mt-0.5">PT. Maju Mundur</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">Medan Belawan</div>
                            <div class="text-gray-400 text-xs">Sumatera Utara</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-400 text-xs italic">Menunggu Pick-up</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                Diproses
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-blue-600"><i data-lucide="eye" class="w-4 h-4"></i></button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">KEN-10293847</div>
                            <div class="text-gray-500 text-xs mt-0.5">Toko Pakaian Makmur</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">Siantar Timur</div>
                            <div class="text-gray-400 text-xs">Pematangsiantar</div>
                        </td>
                        <td class="px-6 py-4 flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=EFF6FF&color=1D4ED8" class="w-6 h-6 rounded-full" alt="">
                            <span class="font-medium text-gray-900">Budi S.</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                Dalam Pengantaran
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-blue-600"><i data-lucide="eye" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
