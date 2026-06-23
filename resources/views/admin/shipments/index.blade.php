@extends('layouts.app')

@section('header-title', 'Data Pengiriman')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ detailModalOpen: null }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Data Resi Pengiriman</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola semua paket yang masuk dan pantau statusnya.</p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto items-center gap-3">

                <form action="{{ route('shipments.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-3">

                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Resi atau Penerima..."
                            class="w-full text-sm font-semibold text-gray-700 py-2.5 pl-10 pr-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 shadow-sm transition-colors placeholder-gray-400">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-56">
                        <select name="status" onchange="this.form.submit()" class="w-full appearance-none bg-white text-sm font-semibold text-gray-700 py-2.5 pl-10 pr-10 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 shadow-sm cursor-pointer hover:border-blue-300 transition-colors">
                            <option value="">Semua Status</option>
                            <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}> Menunggu Jadwal</option>
                            <option value="Dalam Perjalanan" {{ request('status') == 'Dalam Perjalanan' ? 'selected' : '' }}> Dalam Perjalanan</option>
                            <option value="Tiba di KotaTujuan" {{ request('status') == 'Tiba di Tujuan' ? 'selected' : '' }}> Tiba di Kota Tujuan</option>
                            <option value="Dalam Pengantaran" {{ request('status') == 'Dalam Pengantaran' ? 'selected' : '' }}> Dalam Pengantaran</option>
                            <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}> Paket Diterima</option>
                            <option value="Penundaan Pengiriman" {{ request('status') == 'Penundaan Pengiriman' ? 'selected' : '' }}> Ditunda</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                        </div>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>

                    <button type="submit" class="hidden sm:flex items-center justify-center bg-gray-100 text-gray-600 hover:bg-gray-200 px-4 rounded-xl transition-colors font-bold shadow-sm border border-gray-200">
                        Cari
                    </button>
                </form>

                <a href="{{ route('shipments.create') }}"
   class="flex w-full sm:w-auto items-center justify-center gap-1.5 bg-blue-700 text-white px-3 py-1.5 text-sm rounded-lg font-medium shadow-sm hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
    <i data-lucide="plus-circle" class="w-4 h-4"></i>
    <span>Buat Resi</span>
</a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 shadow-sm">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                <span class="font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
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
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $resi->jalur_pengiriman }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $resi->receiver_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $resi->weight }} Kg | {{ $resi->jumlah_koli }} Koli</div>
                                </td>

                                <!-- 👇 PERUBAHAN WARNA STATUS DI TABEL 👇 -->
                                <td class="px-6 py-4">
                                    @php
                                        $statusStr = $resi->current_status->value ?? $resi->current_status;

                                        $badgeColor = match($statusStr) {
                                            'Diproses' => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'Terjadwal' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'Penundaan Pengiriman', 'Gagal Dikirim' => 'bg-red-100 text-red-700 border-red-200',
                                            'Diterima', 'Selesai' => 'bg-green-100 text-green-700 border-green-200',
                                            'Dalam Perjalanan', 'Tiba di KotaTujuan', 'Dalam Pengantaran' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                                        };

                                        $displayText = $statusStr === 'Diproses' ? 'Menunggu Jadwal' : $statusStr;
                                    @endphp

                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border {{ $badgeColor }}">
                                        {{ $displayText }}
                                    </span>
                                </td>
                                <!-- 👆 AKHIR PERUBAHAN 👆 -->

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <button @click="detailModalOpen = {{ $resi->id }}"
                                            class="p-2 inline-block text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                            title="Lihat Detail Lengkap">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>

                                        @php
                                            $isPending = ($resi->current_status === App\Enums\ShipmentStatus::DIPROSES || $resi->current_status?->value === 'Diproses');
                                        @endphp

                                        @if ($isPending && $resi->manifest_id === null)
                                            <a href="{{ route('shipments.edit', $resi->id) }}"
                                                class="p-2 inline-block text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors"
                                                title="Edit Resi">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>

                                            <form action="{{ route('shipments.destroy', $resi->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus resi {{ $resi->tracking_number }} ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Resi">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('shipments.show', $resi->id) }}"
                                            class="p-2 inline-block text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Cetak Resi">
                                            <i data-lucide="printer" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="package-open" class="w-12 h-12 mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium text-gray-500">Data resi tidak ditemukan.</p>
                                        @if(request('search') || request('status'))
                                            <p class="text-sm mt-1">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                                            <a href="{{ route('shipments.index') }}" class="mt-3 text-blue-600 hover:underline text-sm font-bold">Reset Pencarian</a>
                                        @else
                                            <p class="text-sm mt-1">Klik tombol "Buat Resi Baru" untuk memproses paket pertama Anda.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($shipments->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $shipments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        @foreach ($shipments as $resi)
            <div x-show="detailModalOpen === {{ $resi->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">

                <div @click.away="detailModalOpen = null"
                     x-show="detailModalOpen === {{ $resi->id }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-gray-100">

                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="box" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-3">
                                    <h3 class="font-black text-gray-900 text-lg leading-tight">{{ $resi->tracking_number }}</h3>
                                    <a href="{{ route('tracking.index', ['resi' => $resi->tracking_number]) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm border border-blue-100 hover:border-transparent"
                                       title="Lihat di Halaman Lacak Publik">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Buka Tracking</span>
                                    </a>
                                </div>
                                <p class="text-xs text-gray-500 font-medium mt-0.5">Data Lengkap Pengiriman</p>
                            </div>
                        </div>
                        <button @click="detailModalOpen = null" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto space-y-6 bg-gray-50/30">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-4 border border-gray-100 rounded-xl shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><i data-lucide="arrow-up-right" class="w-3 h-3 text-blue-500"></i> Pengirim</p>
                                <p class="font-bold text-gray-900">{{ $resi->sender_name }}</p>
                                <p class="text-sm text-blue-600 font-semibold mb-1">{{ $resi->sender_phone }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $resi->sender_address }}, {{ $resi->origin_city }}</p>
                            </div>
                            <div class="bg-white p-4 border border-gray-100 rounded-xl shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><i data-lucide="map-pin" class="w-3 h-3 text-orange-500"></i> Penerima</p>
                                <p class="font-bold text-gray-900">{{ $resi->receiver_name }}</p>
                                <p class="text-sm text-blue-600 font-semibold mb-1">{{ $resi->receiver_phone }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $resi->receiver_address }}, {{ $resi->destination_city }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50/50 border-b border-gray-100">
                                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Informasi Barang</p>
                                </div>
                                <ul class="p-4 space-y-3 text-sm">
                                    <li class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                                        <span class="text-gray-500">Berat & Koli</span>
                                        <span class="font-bold text-gray-900">{{ number_format($resi->weight, 1) }} Kg ({{ $resi->jumlah_koli }} Koli)</span>
                                    </li>
                                    <li class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                                        <span class="text-gray-500">Isi Paket</span>
                                        <span class="font-bold text-gray-900 text-right">{{ $resi->item_description }}</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span class="text-gray-500">Tanggal Dibuat</span>
                                        <span class="font-semibold text-gray-700">{{ $resi->created_at->format('d M Y, H:i') }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-white border border-blue-100 rounded-xl shadow-sm overflow-hidden">
                                <div class="px-4 py-3 bg-blue-50/50 border-b border-blue-100">
                                    <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Detail Operasional</p>
                                </div>
                                <div class="p-4 text-sm">
                                    @if($resi->manifest_id && $resi->manifest)
                                        <ul class="space-y-3">
                                            <li class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                                                <span class="text-gray-500 flex items-center gap-1"><i data-lucide="user" class="w-3.5 h-3.5"></i> Kurir</span>
                                                <span class="font-bold text-gray-900">{{ optional($resi->manifest->courier)->name ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                                                <span class="text-gray-500 flex items-center gap-1"><i data-lucide="truck" class="w-3.5 h-3.5"></i> Kendaraan</span>
                                                <span class="font-bold text-gray-900 uppercase">{{ optional($resi->manifest->vehicle)->license_plate ?? '-' }}</span>
                                            </li>
                                            <li class="flex justify-between border-b border-dashed border-gray-100 pb-2">
                                                <span class="text-gray-500 flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> Tgl Jalan</span>
                                                <span class="font-semibold text-gray-700">{{ $resi->manifest->departed_at ? \Carbon\Carbon::parse($resi->manifest->departed_at)->format('d M Y, H:i') : '-' }}</span>
                                            </li>

                                            <!-- 👇 PERUBAHAN WARNA STATUS DI DALAM MODAL 👇 -->
                                            <li class="flex justify-between">
                                                <span class="text-gray-500 flex items-center gap-1"><i data-lucide="activity" class="w-3.5 h-3.5"></i> Status</span>
                                                @php
                                                    $modalStatusStr = $resi->current_status->value ?? $resi->current_status;
                                                    $modalTextColor = match($modalStatusStr) {
                                                        'Diproses' => 'text-orange-600',
                                                        'Terjadwal' => 'text-purple-600',
                                                        'Penundaan Pengiriman', 'Gagal Dikirim' => 'text-red-600',
                                                        'Diterima', 'Selesai' => 'text-green-600',
                                                        'Dalam Perjalanan', 'Tiba di Tujuan', 'Dalam Pengantaran' => 'text-blue-600',
                                                        default => 'text-gray-600'
                                                    };
                                                @endphp
                                                <span class="font-bold {{ $modalTextColor }}">{{ $modalStatusStr === 'Diproses' ? 'Menunggu Jadwal' : $modalStatusStr }}</span>
                                            </li>
                                            <!-- 👆 AKHIR PERUBAHAN 👆 -->

                                        </ul>
                                    @else
                                        <div class="h-full flex flex-col items-center justify-center text-center py-4">
                                            <i data-lucide="clock" class="w-8 h-8 text-gray-300 mb-2"></i>
                                            <p class="text-gray-500 font-medium">Belum Dijadwalkan</p>
                                            <p class="text-xs text-gray-400">Resi belum masuk manifest / truk.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        @if(in_array(($resi->current_status->value ?? $resi->current_status), ['Selesai', 'Diterima']) && $resi->proofOfDelivery)
                            <div class="bg-green-50/50 border border-green-200 rounded-xl shadow-sm overflow-hidden mt-4">
                                <div class="px-4 py-3 bg-green-100/50 border-b border-green-200 flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-green-600"></i>
                                    <p class="text-[11px] font-bold text-green-700 uppercase tracking-wider">Proof of Delivery (Bukti Kirim)</p>
                                </div>
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Diserahkan Kepada</p>
                                            <p class="text-base font-black text-gray-900">{{ $resi->proofOfDelivery->received_by_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Waktu Diterima</p>
                                            <p class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($resi->proofOfDelivery->delivered_at)->format('l, d F Y - H:i') }} WIB</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        @if($resi->proofOfDelivery->photo_path)
                                            <a href="{{ $resi->proofOfDelivery->photo_url }}" target="_blank" class="block w-full sm:w-48 h-32 rounded-lg border-2 border-white shadow-md overflow-hidden hover:scale-105 transition-transform duration-300">
                                                <img src="{{ $resi->proofOfDelivery->photo_url }}" alt="Foto POD" class="w-full h-full object-cover">
                                            </a>
                                        @else
                                            <div class="w-full sm:w-48 h-32 bg-gray-100 rounded-lg flex items-center justify-center border border-dashed border-gray-300 text-gray-400 text-xs font-medium">
                                                Tidak ada foto
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
