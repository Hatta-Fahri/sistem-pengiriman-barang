@extends('layouts.app')

@section('header-title', 'Detail Resi')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="flex items-center justify-between print:hidden">
            <a href="{{ route('shipments.index') }}"
                class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-colors bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>

            <button onclick="window.print()"
                class="flex items-center gap-2 bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-[0_4px_12px_-4px_rgba(29,78,216,0.5)] hover:bg-blue-800 transition-all">
                <i data-lucide="printer" class="w-5 h-5"></i> Cetak Resi
            </button>
        </div>

        <div
            class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm print:shadow-none print:border-none print:p-0 print:text-black">

            <div class="flex justify-between items-start border-b-2 border-black pb-4 mb-6">
                <div>
                    <h1 class="text-3xl font-black text-blue-800 print:text-black tracking-tighter italic">KEN <span
                            class="text-gray-800 font-bold text-xl not-italic">LOGISTICS</span></h1>
                    <p class="text-sm text-gray-500 print:text-gray-700 mt-1">PT. Ken Ekspres Nusantara</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Nomor Resi (Waybill)</p>
                    <div
                        class="text-2xl font-black font-mono text-gray-900 border-2 border-gray-900 px-3 py-1 inline-block bg-gray-50 print:bg-white">
                        {{ $shipment->tracking_number }}
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Tgl: {{ $shipment->created_at->format('d M Y - H:i') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-6">
                <div class="border border-gray-300 p-4 rounded-xl print:border-gray-400">
                    <div
                        class="text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200 pb-2 mb-2">
                        Data Pengirim</div>
                    <div class="font-bold text-lg text-gray-900">{{ $shipment->sender_name }}</div>
                    <div class="text-sm font-medium text-gray-700 mt-1 flex items-center gap-1">
                        <i data-lucide="phone" class="w-3 h-3 print:hidden"></i> {{ $shipment->sender_phone }}
                    </div>
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                        {{ $shipment->sender_address }}
                    </p>
                </div>

                <div class="border border-gray-300 p-4 rounded-xl print:border-gray-400">
                    <div
                        class="text-xs font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200 pb-2 mb-2">
                        Data Penerima</div>
                    <div class="font-bold text-lg text-gray-900">{{ $shipment->receiver_name }}</div>
                    <div class="text-sm font-medium text-gray-700 mt-1 flex items-center gap-1">
                        <i data-lucide="phone" class="w-3 h-3 print:hidden"></i> {{ $shipment->receiver_phone }}
                    </div>
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                        {{ $shipment->receiver_address }}
                    </p>
                </div>
            </div>

            <div class="border-2 border-gray-800 rounded-xl overflow-hidden mb-8 print:border-gray-900">
                <div
                    class="bg-gray-100 print:bg-gray-200 border-b-2 border-gray-800 grid grid-cols-3 divide-x-2 divide-gray-800">
                    <div class="p-3 text-center">
                        <p class="text-xs font-bold text-gray-500 uppercase">Kota Asal</p>
                        <p class="font-bold text-lg text-gray-900">{{ $shipment->origin_city }}</p>
                    </div>
                    <div class="p-3 text-center flex flex-col justify-center items-center bg-gray-50 print:bg-white">
                        <i data-lucide="truck" class="w-5 h-5 text-gray-400 mb-1"></i>
                        <span
                            class="text-[10px] font-bold uppercase bg-gray-200 px-2 py-0.5 rounded">{{ $shipment->jalur_pengiriman }}</span>
                    </div>
                    <div class="p-3 text-center">
                        <p class="text-xs font-bold text-gray-500 uppercase">Kota Tujuan</p>
                        <p class="font-bold text-lg text-gray-900">{{ $shipment->destination_city }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-4 divide-x border-b border-gray-200">
                    <div class="p-3 col-span-2">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Isi Paket</p>
                        <p class="font-medium text-gray-900">{{ $shipment->item_description }}</p>
                    </div>

                    <div class="p-3 text-center border-r border-gray-200">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Koli</p>
                        <p class="font-bold text-gray-900">{{ $shipment->jumlah_koli }} koli</p>
                    </div>
                    <div class="p-3 text-center">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Berat</p>
                        <p class="font-bold text-gray-900">{{ $shipment->weight }} Kg</p>
                    </div>
                    <div class="p-3 text-right bg-blue-50 print:bg-gray-100">
                        <p class="text-xs font-bold text-blue-600 print:text-black uppercase mb-1">Total Biaya</p>
                        <p class="font-black text-lg text-blue-800 print:text-black">Rp
                            {{ number_format($shipment->shipping_cost, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 text-center pt-4">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-16">Pengirim,</p>
                    <p class="text-sm font-bold text-gray-900 underline decoration-gray-300 underline-offset-4">
                        {{ $shipment->sender_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-16">Kasir / Admin,</p>
                    <p class="text-sm font-bold text-gray-900 underline decoration-gray-300 underline-offset-4">
                        {{ auth()->user()->name ?? 'Admin KEN' }}</p>
                </div>
            </div>

            <div
                class="mt-8 pt-4 border-t border-dashed border-gray-300 text-center text-xs text-gray-400 print:text-gray-500">
                *Resi ini adalah bukti sah pengiriman PT. Ken Ekspres Nusantara. Simpan resi ini untuk pengecekan status
                paket.
            </div>

        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .max-w-4xl,
            .max-w-4xl * {
                visibility: visible;
            }

            .max-w-4xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
@endsection
