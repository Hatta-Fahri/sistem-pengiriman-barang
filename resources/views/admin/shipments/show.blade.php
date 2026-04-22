@extends('layouts.app')

@section('header-title', 'Detail Resi')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between print:hidden mb-6">
            <a href="{{ route('shipments.index') }}"
                class="flex items-center gap-2 text-gray-600 hover:text-blue-700 transition-colors bg-white px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm font-semibold">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>

            <button onclick="window.print()"
                class="flex items-center gap-2 bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-[0_4px_12px_-4px_rgba(29,78,216,0.5)] hover:bg-blue-800 transition-all active:scale-95">
                <i data-lucide="printer" class="w-5 h-5"></i> Cetak Resi
            </button>
        </div>

        <div class="bg-white p-8 sm:p-10 rounded-2xl border border-gray-200 shadow-lg print:shadow-none print:border-none print:p-0 print:m-0 print:text-black">

            <div class="flex flex-col sm:flex-row justify-between items-start border-b-4 border-gray-900 pb-5 mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-blue-800 print:text-black tracking-tighter italic flex items-center gap-1">
                        KEN <span class="text-gray-900 font-bold text-xl not-italic uppercase tracking-widest mt-1">Logistics</span>
                    </h1>
                    <p class="text-xs text-gray-500 print:text-gray-700 mt-1 font-semibold tracking-wide">PT. KEN EKSPRES NUSANTARA</p>
                </div>
                <div class="text-left sm:text-right w-full sm:w-auto">
                    <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest mb-1.5">Nomor Resi (Waybill)</p>
                    <div class="text-2xl sm:text-3xl font-black font-mono text-gray-950 border-2 border-gray-900 px-4 py-1.5 inline-block bg-gray-50 print:bg-white tracking-wider">
                        {{ $shipment->tracking_number }}
                    </div>
                    <p class="text-[11px] text-gray-500 font-bold mt-2">TGL: {{ $shipment->created_at->format('d M Y - H:i') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <div class="border-2 border-gray-200 rounded-xl p-4 print:border-gray-900">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 print:text-gray-600 border-b-2 border-gray-100 print:border-gray-300 pb-2 mb-3">
                        Data Pengirim
                    </div>
                    <div class="font-black text-lg text-gray-900 uppercase">{{ $shipment->sender_name }}</div>
                    <div class="text-sm font-bold text-gray-700 mt-1 flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-3.5 h-3.5 print:hidden text-gray-400"></i> {{ $shipment->sender_phone }}
                    </div>
                    <p class="text-sm text-gray-600 print:text-gray-800 mt-2.5 leading-relaxed font-medium">
                        {{ $shipment->sender_address }}
                    </p>
                </div>

                <div class="border-2 border-gray-200 rounded-xl p-4 print:border-gray-900">
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 print:text-gray-600 border-b-2 border-gray-100 print:border-gray-300 pb-2 mb-3">
                        Data Penerima
                    </div>
                    <div class="font-black text-lg text-gray-900 uppercase">{{ $shipment->receiver_name }}</div>
                    <div class="text-sm font-bold text-gray-700 mt-1 flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-3.5 h-3.5 print:hidden text-gray-400"></i> {{ $shipment->receiver_phone }}
                    </div>
                    <p class="text-sm text-gray-600 print:text-gray-800 mt-2.5 leading-relaxed font-medium">
                        {{ $shipment->receiver_address }}
                    </p>
                </div>
            </div>

            <div class="border-2 border-gray-900 rounded-xl overflow-hidden mb-10 print:border-gray-900">

                <div class="flex flex-col sm:flex-row border-b-2 border-gray-900 bg-gray-50 print:bg-white divide-y-2 sm:divide-y-0 sm:divide-x-2 divide-gray-900">
                    <div class="w-full sm:w-2/5 p-4 text-center">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Kota Asal</p>
                        <p class="font-black text-xl text-gray-900 uppercase">{{ $shipment->origin_city }}</p>
                    </div>
                    <div class="w-full sm:w-1/5 p-3 text-center flex flex-col justify-center items-center bg-white">
                        <i data-lucide="truck" class="w-6 h-6 text-gray-400 mb-1"></i>
                        <span class="text-[9px] font-black uppercase tracking-wider bg-gray-900 text-white px-2 py-1 rounded">{{ $shipment->jalur_pengiriman }}</span>
                    </div>
                    <div class="w-full sm:w-2/5 p-4 text-center">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Kota Tujuan</p>
                        <p class="font-black text-xl text-gray-900 uppercase">{{ $shipment->destination_city }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row divide-y-2 sm:divide-y-0 sm:divide-x-2 divide-gray-900">
                    <div class="w-full sm:w-2/5 p-4">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Isi Paket</p>
                        <p class="font-bold text-gray-900 uppercase">{{ $shipment->item_description }}</p>
                    </div>

                    <div class="w-full sm:w-1/5 p-4 text-center">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Koli</p>
                        <p class="font-black text-lg text-gray-900">{{ $shipment->jumlah_koli }}</p>
                    </div>

                    <div class="w-full sm:w-1/5 p-4 text-center">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Berat</p>
                        <p class="font-black text-lg text-gray-900">{{ number_format($shipment->weight, 1) }} <span class="text-sm">Kg</span></p>
                    </div>

                    <div class="w-full sm:w-1/5 p-4 text-right bg-blue-50/50 print:bg-white flex flex-col justify-center">
                        <p class="text-[10px] font-black text-blue-600 print:text-gray-900 uppercase tracking-widest mb-1">Total Biaya</p>
                        <p class="font-black text-xl text-blue-800 print:text-black">Rp {{ number_format($shipment->shipping_cost, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 text-center pt-4 mb-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-20">Pengirim</p>
                    <p class="text-sm font-black text-gray-900 uppercase relative inline-block">
                        {{ $shipment->sender_name }}
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gray-900"></span>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-20">Petugas / Admin</p>
                    <p class="text-sm font-black text-gray-900 uppercase relative inline-block">
                        {{ auth()->user()->name ?? 'Admin KEN' }}
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gray-900"></span>
                    </p>
                </div>
            </div>

            <div class="mt-10 pt-4 border-t-2 border-dashed border-gray-300 text-center">
                <p class="text-[10px] font-bold text-gray-400 print:text-gray-600 uppercase tracking-widest">
                    Resi ini adalah bukti sah pengiriman PT. Ken Ekspres Nusantara. <br class="hidden sm:block">
                    Lacak paket Anda secara online di website kami.
                </p>
            </div>

        </div>
    </div>

    <style>
        @media print {
            body {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            body * {
                visibility: hidden;
            }
            .max-w-3xl, .max-w-3xl * {
                visibility: visible;
            }
            .max-w-3xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 10px;
            }
        }
    </style>
@endsection
