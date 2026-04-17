@extends('layouts.app')

@section('header-title', 'Daftar & Update Paket')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Paket</h2>
        <p class="text-gray-500 text-sm mt-1">Perbarui status pengiriman secara real-time saat paket sampai di tujuan.</p>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
            <span class="font-medium text-sm">{{ $errors->first() }}</span>
        </div>
    @endif

    @if($activeManifest)
        @php
            $totalPaket = $activeManifest->shipments->count();

            // PERBAIKAN BUG ENUM: Kita filter manual isinya
            $paketSelesai = $activeManifest->shipments->filter(function($shipment) {
                $status = $shipment->current_status->value ?? $shipment->current_status;
                return in_array($status, ['Diterima', 'Gagal Dikirim', 'Penundaan Pengiriman']);
            })->count();

            $sisaPaket = $totalPaket - $paketSelesai;
            $progress = $totalPaket > 0 ? ($paketSelesai / $totalPaket) * 100 : 0;
        @endphp

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <div class="flex justify-between items-end mb-3">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Progress Pengantaran</p>
                    <h3 class="text-2xl font-black text-gray-900">{{ $paketSelesai }} <span class="text-lg text-gray-400 font-medium">/ {{ $totalPaket }} Selesai</span></h3>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $sisaPaket == 0 ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ $sisaPaket == 0 ? 'Semua Selesai!' : $sisaPaket . ' Tersisa' }}
                    </span>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($activeManifest->shipments as $shipment)
                @php
                    $statusAsli = $shipment->current_status->value ?? $shipment->current_status;
                    $isSelesai = in_array($statusAsli, ['Diterima', 'Gagal Dikirim', 'Penundaan Pengiriman']);

                    $statusColor = match($statusAsli) {
                        'Diterima' => 'bg-green-50 text-green-700 border-green-200',
                        'Gagal Dikirim' => 'bg-red-50 text-red-700 border-red-200',
                        'Penundaan Pengiriman' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'Dalam Pengantaran' => 'bg-blue-50 text-blue-700 border-blue-200',
                        default => 'bg-gray-50 text-gray-600 border-gray-200'
                    };
                @endphp

                <div x-data="{ open: false }" class="bg-white rounded-2xl border {{ $isSelesai ? 'border-gray-100 opacity-75' : 'border-blue-100 shadow-sm' }} overflow-hidden transition-all">

                    <div @click="open = !open" class="p-5 cursor-pointer hover:bg-gray-50/50 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-2.5">
                                <i data-lucide="{{ $isSelesai ? 'check-circle-2' : 'box' }}" class="w-5 h-5 {{ $isSelesai ? 'text-green-500' : 'text-blue-600' }}"></i>
                                <span class="font-black text-gray-900 tracking-wide">{{ $shipment->tracking_number }}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase border {{ $statusColor }}">
                                {{ $statusAsli }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Penerima</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $shipment->receiver_name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $shipment->receiver_address }}, {{ $shipment->destination_city }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between text-xs font-semibold text-blue-600">
                            <span>Tekan untuk detail & ubah status</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        </div>
                    </div>

                    <div x-show="open" x-collapse x-cloak>
                        <div class="bg-blue-50/30 p-5 border-t border-gray-100">

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Pengirim</span>
                                    <p class="text-xs font-semibold text-gray-900">{{ $shipment->sender_name }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">No. HP Penerima</span>
                                    <a href="tel:{{ $shipment->receiver_phone ?? '#' }}" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3 h-3"></i> {{ $shipment->receiver_phone ?? '-' }}
                                    </a>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Berat Paket</span>
                                    <p class="text-xs font-semibold text-gray-900">{{ $shipment->weight }} kg</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Catatan Tambahan</span>
                                    <p class="text-xs font-medium text-gray-600">{{ $shipment->note ?? 'Tidak ada catatan.' }}</p>
                                </div>
                            </div>

                            <form action="{{ route('courier.shipments.update-status', $shipment->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3 border-t border-blue-100 pt-4">
                                @csrf @method('PUT')

                                <div class="flex-1">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Update Status Menjadi:</label>
                                    <select name="current_status" required class="w-full text-sm rounded-xl border-gray-300 focus:ring-blue-600 focus:border-blue-600 shadow-sm">
                                        <option value="Dalam Pengantaran" {{ $statusAsli == 'Dalam Pengantaran' ? 'selected' : '' }}>🛵 Sedang OTW (Dalam Pengantaran)</option>
                                        <option value="Diterima" {{ $statusAsli == 'Diterima' ? 'selected' : '' }}>✅ Selesai (Diterima Customer)</option>
                                        <option value="Gagal Dikirim" {{ $statusAsli == 'Gagal Dikirim' ? 'selected' : '' }}>❌ Rumah Kosong (Gagal Dikirim)</option>
                                        <option value="Penundaan Pengiriman" {{ $statusAsli == 'Penundaan Pengiriman' ? 'selected' : '' }}>⏸️ Ditunda / Reschedule</option>
                                    </select>
                                </div>

                                <div class="sm:self-end">
                                    <button type="submit" class="w-full sm:w-auto bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-blue-800 transition-colors flex items-center justify-center gap-2">
                                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-center relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tugas Selesai?</h3>
                <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Pastikan semua paket sudah di-update statusnya menjadi Diterima, Gagal, atau Ditunda sebelum menutup manifest hari ini.</p>

                <form action="{{ route('courier.manifests.complete', $activeManifest->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyelesaikan tugas hari ini? Status armada akan kembali tersedia.');">
                    @csrf
                    <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-black text-white shadow-lg transition-all transform active:scale-95
                        {{ $sisaPaket == 0 ? 'bg-gray-900 hover:bg-black' : 'bg-gray-400 cursor-not-allowed' }}"
                        {{ $sisaPaket > 0 ? 'disabled title="Selesaikan semua resi terlebih dahulu"' : '' }}>

                        <i data-lucide="flag" class="w-5 h-5"></i>
                        SELESAIKAN TUGAS HARI INI
                    </button>
                </form>
            </div>

            @if($sisaPaket > 0)
                <div class="absolute inset-0 bg-gray-50/50 backdrop-blur-[1px] z-20 flex items-center justify-center">
                    <span class="bg-white px-4 py-2 rounded-lg shadow border border-gray-200 text-sm font-bold text-red-600 flex items-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4"></i> Tombol terkunci (Sisa {{ $sisaPaket }} Paket)
                    </span>
                </div>
            @endif
        </div>

    @else
        <div class="bg-white rounded-2xl p-16 text-center border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4 border border-gray-100">
                <i data-lucide="check-square" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Paket</h3>
            <p class="text-gray-500 max-w-md mx-auto">Tidak ada daftar paket yang harus diantar saat ini. Anda bisa bersantai atau tanyakan jadwal ke Admin.</p>
        </div>
    @endif

</div>
@endsection
