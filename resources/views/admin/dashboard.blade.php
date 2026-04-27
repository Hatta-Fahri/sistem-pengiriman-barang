@extends('layouts.app')

@section('header-title', 'Overview')

@section('content')
    <div class="w-full space-y-6">

        <div>
            <p class="text-gray-500 text-sm mt-1">Pantau performa pengiriman dan operasional KEN Logistics hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:border-blue-200 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-blue-50 p-2.5 rounded-xl border border-blue-100">
                        <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Pengiriman Aktif</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($activeShipments) }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-[11px] font-bold">
                        Sedang Berjalan
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:border-indigo-200 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-indigo-50 p-2.5 rounded-xl border border-indigo-100">
                        <i data-lucide="users" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Kurir</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($totalCouriers) }}</h3>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md text-[11px] font-bold">
                        Terdaftar di Sistem
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:border-green-200 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-green-50 p-2.5 rounded-xl border border-green-100">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Paket Terkirim</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($deliveredShipments) }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 bg-[#D1F4E0] text-[#147D44] px-2 py-0.5 rounded-md text-[11px] font-bold">
                        <i data-lucide="shield-check" class="w-3 h-3"></i> Selesai
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:border-red-200 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-red-50 p-2.5 rounded-xl border border-red-100">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Kendala (Tertunda)</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($delayedShipments) }}</h3>
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
                    <h3 class="text-lg font-bold text-gray-900">Aktivitas Pengiriman Baru</h3>
                    <select class="text-sm border-gray-200 rounded-lg text-gray-500 font-medium focus:ring-blue-500 focus:border-blue-500">
                        <option>7 Hari Terakhir</option>
                    </select>
                </div>

                <div class="h-64 w-full flex items-end gap-2 pt-10">
                    @foreach($chartData as $index => $value)
                        @php
                            // Kalkulasi tinggi batang grafik (minimal 5% agar tetap terlihat jika 0)
                            $height = ($value / $maxChartValue) * 100;
                            if ($height < 5) $height = 5;
                            $isHighest = $value == $maxChartValue && $value > 0;
                        @endphp

                        <div class="w-full rounded-t-sm relative transition-all duration-500 {{ $isHighest ? 'bg-blue-600 shadow-[0_0_15px_rgba(37,99,235,0.3)]' : 'bg-blue-100 hover:bg-blue-200' }}" style="height: {{ $height }}%">
                            <div class="absolute -top-7 w-full text-center text-xs font-bold {{ $isHighest ? 'text-blue-600' : 'text-gray-400 opacity-0 hover:opacity-100' }} transition-opacity">
                                {{ $value }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-3 text-xs text-gray-400 font-medium uppercase tracking-wider">
                    @foreach($chartLabels as $label)
                        <span>{{ $label }}</span>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Performa Kurir (Top 3)</h3>

                <div class="space-y-5">
                    @forelse($topCouriers as $index => $courier)
                        @php
                            $medalColor = match($index) {
                                0 => 'bg-yellow-400',
                                1 => 'bg-gray-300',
                                2 => 'bg-orange-400',
                                default => 'bg-blue-500'
                            };
                        @endphp
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($courier->name) }}&background=EFF6FF&color=1D4ED8"
                                    class="w-10 h-10 rounded-full border border-gray-100 shadow-sm" alt="{{ $courier->name }}">
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 {{ $medalColor }} border-2 border-white rounded-full flex items-center justify-center text-[8px] text-white font-bold">
                                    {{ $index + 1 }}
                               </span>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <h4 class="text-sm font-bold text-gray-900 truncate">{{ $courier->name }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $courier->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">{{ number_format($courier->total_delivered) }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Sukses</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-sm">
                            Belum ada data pengiriman sukses.
                        </div>
                    @endforelse
                </div>

                <button class="w-full mt-6 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Lihat Semua Kurir
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="text-lg font-bold text-gray-900">Update Pengiriman Terkini</h3>
                <a href="{{ route('shipments.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat Semua Data</a>
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
                        @forelse($recentShipments as $shipment)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $shipment->tracking_number }}</div>
                                    <div class="text-gray-500 text-xs mt-0.5">{{ $shipment->sender_name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium">{{ $shipment->destination_city }}</div>
                                    <div class="text-gray-400 text-xs">{{ \Illuminate\Support\Str::limit($shipment->receiver_address, 25) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(optional($shipment->manifest)->courier)
                                        <div class="flex items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($shipment->manifest->courier->name) }}&background=EFF6FF&color=1D4ED8" class="w-6 h-6 rounded-full" alt="">
                                            <span class="font-medium text-gray-900">{{ $shipment->manifest->courier->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs italic">Belum Dijadwalkan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStr = $shipment->current_status->value ?? $shipment->current_status;
                                        $badgeColor = match($statusStr) {
                                            'Diproses', 'Menunggu Jadwal' => 'bg-gray-100 text-gray-600 border-gray-200',
                                            'Dalam Perjalanan', 'Tiba di Tujuan' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'Dalam Pengantaran' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Diterima', 'Selesai' => 'bg-green-50 text-green-700 border-green-200',
                                            'Penundaan Pengiriman' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            default => 'bg-gray-100 text-gray-600 border-gray-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border {{ $badgeColor }}">
                                        @if($statusStr === 'Dalam Pengantaran')
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                        @endif
                                        {{ $statusStr }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('shipments.index') }}" class="text-gray-400 hover:text-blue-600 p-2"><i data-lucide="eye" class="w-4 h-4 inline"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data pengiriman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
