@extends('layouts.app')

@section('header-title', 'Detail Jadwal & Muatan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('manifests.index') }}" class="hover:text-blue-600 font-medium transition-colors">Manifest</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="font-bold text-gray-900">Detail: {{ $manifest->manifest_code }}</span>
        </div>

        <div class="flex items-center gap-3">
            @php
                $statusColor = match($manifest->status) {
                    'Persiapan' => 'bg-gray-100 text-gray-700 border-gray-200',
                    'Ditugaskan' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                    'Sedang Jalan' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'Selesai' => 'bg-green-100 text-green-700 border-green-200',
                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                };
            @endphp
            <span class="px-3 py-1.5 rounded-lg text-xs font-black uppercase border shadow-sm {{ $statusColor }} flex items-center gap-1.5">
                <i data-lucide="info" class="w-4 h-4"></i> Status: {{ $manifest->status }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-100">

        <div class="md:pr-6 space-y-3">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Rute / Jalur</p>
                <p class="font-bold text-gray-900 flex items-start gap-2">
                    <i data-lucide="map" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5"></i>
                    {{ $manifest->jalur_pengiriman }}
                </p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Waktu Keberangkatan</p>
                <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                    {{ $manifest->departed_at ? \Carbon\Carbon::parse($manifest->departed_at)->format('d M Y, H:i') : 'Belum Berangkat' }}
                </p>
            </div>
        </div>

        <div class="md:px-6 pt-4 md:pt-0 space-y-3">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Armada / Truk</p>
                <p class="font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="truck" class="w-4 h-4 text-blue-600"></i>
                    {{ $manifest->vehicle->license_plate ?? 'Tidak ada data' }}
                    <span class="text-xs font-normal text-gray-500">(Max: {{ number_format($manifest->vehicle->capacity ?? 0, 0) }} Kg)</span>
                </p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Supir / Kurir</p>
                <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                    {{ $manifest->courier->name ?? 'Tidak ada data' }}
                </p>
            </div>
        </div>

        <div class="md:pl-6 pt-4 md:pt-0 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Total Muatan Terbawa</p>
            <div class="flex items-end gap-2">
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($manifest->total_weight, 1) }}</h3>
                <span class="text-gray-500 font-medium mb-1">Kg</span>
            </div>
            <p class="text-sm font-semibold text-blue-600 mt-1 flex items-center gap-1.5">
                <i data-lucide="package" class="w-4 h-4"></i> {{ $manifest->total_shipments }} Resi / Paket
            </p>
        </div>

    </div>

    @if($manifest->notes)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
        <i data-lucide="clipboard-list" class="w-5 h-5 text-yellow-600 shrink-0"></i>
        <div>
            <p class="text-xs font-bold text-yellow-800 uppercase tracking-wider mb-0.5">Catatan Perjalanan</p>
            <p class="text-sm text-yellow-700">{{ $manifest->notes }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Daftar Paket di Dalam Truk</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-white text-xs text-gray-400 uppercase font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4">Nomor Resi</th>
                        <th class="px-6 py-4">Penerima & Alamat</th>
                        <th class="px-6 py-4 text-right">Berat</th>
                        <th class="px-6 py-4 text-center">Status Paket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($manifest->shipments as $index => $shipment)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-black text-blue-700 tracking-wide">{{ $shipment->tracking_number }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $shipment->receiver_name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 truncate max-w-xs" title="{{ $shipment->receiver_address }}, {{ $shipment->destination_city }}">
                                {{ $shipment->receiver_address }}, {{ $shipment->destination_city }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-right font-bold">{{ number_format($shipment->weight, 1) }} Kg</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusAsli = $shipment->current_status->value ?? $shipment->current_status;
                                $badgeColor = match($statusAsli) {
                                    'Diterima' => 'bg-green-50 text-green-700 border-green-200',
                                    'Gagal Dikirim', 'Penundaan Pengiriman' => 'bg-red-50 text-red-700 border-red-200',
                                    'Dalam Pengantaran' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default => 'bg-gray-50 text-gray-600 border-gray-200'
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase border {{ $badgeColor }}">
                                {{ $statusAsli }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i data-lucide="package-x" class="w-10 h-10 mx-auto mb-3 text-gray-300"></i>
                            <p>Tidak ada data resi ditemukan di jadwal ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
