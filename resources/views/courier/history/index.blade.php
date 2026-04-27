@extends('layouts.app')

@section('header-title', 'Riwayat Pengiriman')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12" x-data="{ podModalOpen: null }">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Riwayat Tugas</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar paket yang telah selesai Anda proses.</p>
        </div>

        <form action="{{ route('courier.history.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. resi atau penerima..."
                    class="w-full text-sm font-semibold text-gray-700 py-2.5 pl-10 pr-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 shadow-sm transition-colors">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </div>
            <button type="submit" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-4 rounded-xl transition-colors font-bold shadow-sm border border-gray-200 flex items-center justify-center">
                Cari
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($shipments as $resi)
            @php
                $statusVal = $resi->current_status->value ?? $resi->current_status;
                $isSukses = in_array($statusVal, ['Diterima', 'Selesai']);

                $bgColor = $isSukses ? 'bg-green-50' : ($isGagal ? 'bg-red-50' : 'bg-orange-50');
                $textColor = $isSukses ? 'text-green-700' : ($isGagal ? 'text-red-700' : 'text-orange-700');
                $borderColor = $isSukses ? 'border-green-200' : ($isGagal ? 'border-red-200' : 'border-orange-200');
                $icon = $isSukses ? 'check-circle' : ($isGagal ? 'x-circle' : 'alert-circle');
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden flex flex-col hover:border-blue-200 transition-colors">
                <div class="p-5 border-b border-gray-50 flex justify-between items-start gap-4">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold {{ $bgColor }} {{ $textColor }} {{ $borderColor }} border mb-2">
                            <i data-lucide="{{ $icon }}" class="w-3 h-3"></i> {{ $statusVal }}
                        </span>
                        <h3 class="font-black text-gray-900 text-lg tracking-tight">{{ $resi->tracking_number }}</h3>
                        <p class="text-xs font-medium text-gray-500 mt-0.5">
                            Selesai: {{ $resi->updated_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                <div class="p-5 flex-grow bg-gray-50/30">
                    <div class="mb-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Penerima</p>
                        <p class="font-bold text-gray-900 text-sm">{{ $resi->receiver_name }}</p>
                        <p class="text-xs text-gray-500 leading-relaxed mt-0.5 line-clamp-2">{{ $resi->receiver_address }}</p>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tujuan</p>
                            <p class="font-semibold text-gray-700">{{ $resi->destination_city }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Berat</p>
                            <p class="font-semibold text-gray-700">{{ number_format($resi->weight, 1) }} Kg</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-50 bg-white mt-auto flex justify-end gap-2">
                    @if($resi->proofOfDelivery)
                        <button @click="podModalOpen = {{ $resi->id }}" class="flex items-center gap-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg transition-colors">
                            <i data-lucide="image" class="w-4 h-4"></i> Lihat POD
                        </button>
                    @endif
                </div>
            </div>

            @if($resi->proofOfDelivery)
                <div x-show="podModalOpen === {{ $resi->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity">
                    <div @click.away="podModalOpen = null"
                         x-show="podModalOpen === {{ $resi->id }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="bg-white w-full max-w-md rounded-2xl shadow-2xl flex flex-col overflow-hidden">

                        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                <i data-lucide="camera" class="w-4 h-4 text-blue-600"></i> Bukti Pengiriman
                            </h3>
                            <button @click="podModalOpen = null" class="text-gray-400 hover:text-red-500 transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <div class="p-5">
                            @if($resi->proofOfDelivery->photo_path)
                                <div class="rounded-xl overflow-hidden bg-gray-100 border border-gray-200 mb-4">
                                    <img src="{{ asset('storage/' . $resi->proofOfDelivery->photo_path) }}" alt="Foto POD" class="w-full h-auto max-h-64 object-cover">
                                </div>
                            @endif

                            <div class="space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Penerima / Keterangan</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $resi->proofOfDelivery->received_by_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Waktu Submit</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($resi->proofOfDelivery->delivered_at)->format('d F Y, H:i') }} WIB</p>
                                </div>
                                @if($resi->proofOfDelivery->notes)
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">Catatan Kurir</p>
                                        <p class="text-xs text-gray-600">{{ $resi->proofOfDelivery->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-gray-100 border-dashed">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="history" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Riwayat</h3>
                <p class="text-gray-500 text-sm">
                    @if(request('search'))
                        Tidak ada riwayat yang cocok dengan kata kunci tersebut.
                    @else
                        Anda belum menyelesaikan pengiriman apapun.
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('courier.history.index') }}" class="inline-block mt-4 text-sm font-bold text-blue-600 hover:underline">Reset Pencarian</a>
                @endif
            </div>
        @endforelse
    </div>

    @if ($shipments->hasPages())
        <div class="mt-6">
            {{ $shipments->appends(request()->query())->links() }}
        </div>
    @endif

</div>
@endsection
