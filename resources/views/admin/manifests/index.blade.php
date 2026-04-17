@extends('layouts.app')

@section('header-title', 'Penjadwalan (Manifest)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Data Manifest</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola jadwal rute, alokasi armada, dan persiapan muatan resi.</p>
        </div>
        <a href="{{ route('manifests.create') }}" class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-blue-800 transition-colors">
            <i data-lucide="calendar-plus" class="w-5 h-5"></i>
            <span>Buat Jadwal Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 font-semibold tracking-wider">Kode & Rute</th>
                        <th class="px-6 py-4 font-semibold tracking-wider">Armada & Kurir</th>
                        <th class="px-6 py-4 font-semibold tracking-wider w-64">Kapasitas Muatan</th>
                        <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                        <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($manifests as $manifest)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-black text-blue-700 mb-0.5">{{ $manifest->manifest_code }}</div>
                            <div class="font-bold text-gray-900">{{ $manifest->jalur_pengiriman }}</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">Dibuat: {{ $manifest->created_at->format('d M Y') }}</div>
                        </td>

                        <td class="px-6 py-4">
                            @if($manifest->vehicle || $manifest->courier)
                                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                    <i data-lucide="truck" class="w-4 h-4 text-gray-400"></i>
                                    {{ $manifest->vehicle->license_plate ?? 'Truk Belum Dipilih' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i>
                                    {{ $manifest->courier->name ?? 'Kurir Belum Dipilih' }}
                                </div>
                            @else
                                <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2.5 py-1 rounded-md border border-orange-100">Belum Dialokasikan</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $capacity = $manifest->vehicle->capacity ?? 0;
                                $totalWeight = $manifest->total_weight ?? 0;
                                $percentage = $capacity > 0 ? ($totalWeight / $capacity) * 100 : 0;

                                // Animasi mentok di 100% agar UI tidak jebol
                                $clampedPercentage = $percentage > 100 ? 100 : $percentage;

                                // Penentuan warna (Sama seperti JS: >100 Merah, >=85 Oranye, <85 Biru)
                                $barColor = $percentage > 100 ? 'bg-red-600' : ($percentage >= 85 ? 'bg-orange-500' : 'bg-blue-600');
                                $textColor = $percentage > 100 ? 'text-red-600' : ($percentage >= 85 ? 'text-orange-600' : 'text-blue-700');
                            @endphp

                            <div class="flex justify-between items-end mb-1">
                                <span class="text-xs font-bold text-gray-700">
                                    {{ number_format($totalWeight, 1) }} <span class="text-gray-400 font-normal">/ {{ number_format($capacity, 0) }} Kg</span>
                                </span>
                                <span class="text-[10px] font-black {{ $textColor }}">
                                    {{ number_format($percentage, 1) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ $clampedPercentage }}%"></div>
                            </div>
                            <div class="text-[10px] text-gray-400 mt-1 text-right">{{ $manifest->total_shipments ?? 0 }} Resi Dimuat</div>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($manifest->status) {
                                    'Persiapan' => 'bg-gray-100 text-gray-700 border border-gray-200',
                                    'Sedang Jalan' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                    'Selesai' => 'bg-green-100 text-green-700 border border-green-200',
                                    default => 'bg-gray-100 text-gray-700 border border-gray-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase {{ $statusColor }}">
                                {{ $manifest->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">

                                @if($manifest->status === 'Persiapan')
                                    <form action="{{ route('manifests.berangkatkan', $manifest->id) }}" method="POST" onsubmit="return confirm('Truk sudah siap dan bak ditutup? Berangkatkan sekarang?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors flex items-center gap-1 shadow-sm">
                                            <i data-lucide="send" class="w-3.5 h-3.5"></i> Jalan
                                        </button>
                                    </form>
                                @endif

                                @if($manifest->status === 'Persiapan')
                                    <a href="{{ route('manifests.edit', $manifest->id) }}"
                                       class="px-3 py-1.5 bg-white border border-gray-200 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-50 transition-colors flex items-center gap-1 shadow-sm">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                    </a>
                                @else
                                    <a href="{{ route('manifests.show', $manifest->id) }}"
                                       class="px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-50 transition-colors flex items-center gap-1 shadow-sm">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Detail
                                    </a>
                                @endif

                                @if($manifest->status === 'Persiapan')
                                    <form action="{{ route('manifests.destroy', $manifest->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Jadwal">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic font-medium">Belum ada jadwal keberangkatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($manifests->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $manifests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
