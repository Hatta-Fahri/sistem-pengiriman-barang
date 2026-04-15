@extends('layouts.app')

@section('header-title', 'Data Pengiriman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Data Resi Pengiriman</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola semua paket yang masuk dan pantau statusnya.</p>
            </div>
            <a href="{{ route('shipments.create') }}"
                class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>Buat Resi Baru</span>
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">No. Resi</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Rute & Jalur</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Penerima</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($shipments as $resi)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $resi->tracking_number }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $resi->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 font-semibold text-gray-700 mb-1">
                                        <span>{{ $resi->origin_city }}</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3 text-gray-300"></i>
                                        <span>{{ $resi->destination_city }}</span>
                                    </div>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $resi->jalur_pengiriman }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $resi->receiver_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $resi->weight }} Kg</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($resi->current_status === App\Enums\ShipmentStatus::DIPROSES || $resi->current_status?->value === 'Diproses')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                                            Menunggu Jadwal
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                            {{ $resi->current_status->value ?? $resi->current_status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="#"
                                        class="p-2 inline-block text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Lihat Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="package-open" class="w-12 h-12 mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium text-gray-500">Belum ada data resi.</p>
                                        <p class="text-sm">Klik tombol "Buat Resi Baru" untuk memproses paket.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($shipments->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $shipments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
