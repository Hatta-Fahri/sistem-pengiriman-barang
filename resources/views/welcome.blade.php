<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Paket - KEN Logistics</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Figtree', sans-serif; }
        .bg-grid-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="antialiased bg-[#f8fafc] flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-100 py-3 px-6 sm:px-12 flex justify-center sm:justify-start items-center relative z-10 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-700 rounded-lg flex items-center justify-center text-white shadow-sm">
                <i data-lucide="chevrons-right" class="w-5 h-5"></i>
            </div>
            <div class="flex flex-col justify-center">
                <span class="text-xl font-black italic tracking-tight text-blue-800 leading-none">KEN</span>
                <span class="text-[9px] font-black text-red-500 tracking-[0.2em] mt-0.5 uppercase">Logistics</span>
            </div>
        </div>
    </nav>

    <div class="bg-white border-b border-gray-100 bg-grid-pattern relative">
        <div class="absolute inset-0 bg-gradient-to-b from-white/40 to-gray-50/90 pointer-events-none"></div>

        <div class="pt-16 pb-24 px-4 text-center relative z-10">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-3 tracking-tight">Lacak Status Pengiriman</h1>
                <p class="text-gray-500 text-base mb-10 font-medium">Masukkan nomor resi KEN Logistics Anda untuk mengetahui posisi paket secara real-time.</p>

                <form action="{{ route('tracking.index') }}" method="GET" class="max-w-2xl mx-auto relative">
                    <div class="flex flex-col sm:flex-row items-center bg-white p-2 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 focus-within:ring-2 focus-within:ring-blue-100 transition-all gap-2">
                        <div class="flex items-center flex-1 w-full pl-2">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                            <input type="text" name="resi" value="{{ $resi ?? '' }}" placeholder="Contoh: KEN-20260417-ABCD" required autocomplete="off"
                                class="w-full bg-transparent border-0 focus:ring-0 px-3 py-3 text-base font-bold text-gray-900 placeholder-gray-400 uppercase">
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-700 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-blue-800 transition-colors whitespace-nowrap shadow-sm">
                            Cari Resi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="flex-grow max-w-2xl mx-auto w-full px-4 -mt-10 relative z-20 pb-20">

        @if(isset($error))
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-red-100 p-8 text-center animate-bounce-short">
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="package-x" class="w-7 h-7"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Resi Tidak Ditemukan</h3>
                <p class="text-gray-500 text-sm">{{ $error }}</p>
            </div>
        @endif

        @if(isset($shipment))
            @php
                // LOGIKA STATUS TIMELINE (BERDASARKAN ENUM ASLI)
                $statusVal = $shipment->current_status->value ?? $shipment->current_status;

                // Titik 1: Resi Dibuat
                $isDiprosesActive = in_array($statusVal, ['Diproses', 'Menunggu Jadwal']);
                $isDiprosesPast   = in_array($statusVal, ['Dalam Perjalanan', 'Tiba di Tujuan', 'Dalam Pengantaran', 'Diterima', 'Gagal Dikirim', 'Penundaan Pengiriman']);

                // Titik 2: Dalam Perjalanan
                $isPerjalananActive = in_array($statusVal, ['Dalam Perjalanan', 'Tiba di Tujuan']);
                $isPerjalananPast   = in_array($statusVal, ['Dalam Pengantaran', 'Diterima', 'Gagal Dikirim', 'Penundaan Pengiriman']);

                // Titik 3: Dalam Pengantaran
                $isPengantaranActive = $statusVal === 'Dalam Pengantaran';
                $isPengantaranPast   = in_array($statusVal, ['Diterima', 'Gagal Dikirim', 'Penundaan Pengiriman']);

                // Titik 4: Final
                $isFinal   = in_array($statusVal, ['Diterima', 'Gagal Dikirim', 'Penundaan Pengiriman']);
                $isSelesai = $statusVal === 'Diterima';
                $isGagal   = $statusVal === 'Gagal Dikirim';
                $isTunda   = $statusVal === 'Penundaan Pengiriman';

                // Logika Warna: Ongoing = Hijau, Lewat = Abu-abu (Termasuk kalau final, abu-abu semua)
                $s1_color = $isDiprosesActive ? 'bg-green-500' : ($isDiprosesPast ? 'bg-gray-400' : 'bg-gray-200');
                $s2_color = $isPerjalananActive ? 'bg-green-500' : ($isPerjalananPast ? 'bg-gray-400' : 'bg-gray-200');
                $s3_color = $isPengantaranActive ? 'bg-green-500' : ($isPengantaranPast ? 'bg-gray-400' : 'bg-gray-200');
                $s4_color = $isFinal ? 'bg-gray-400' : 'bg-gray-200';

                // Teks Status Utama
                $displayStatus = match($statusVal) {
                    'Diproses', 'Menunggu Jadwal' => 'Sedang Diproses di Gudang',
                    'Dalam Perjalanan' => 'Dalam Perjalanan Antar Kota',
                    'Tiba di Tujuan' => 'Tiba di Kota Tujuan',
                    'Dalam Pengantaran' => 'Sedang Dalam Pengantaran Kurir',
                    'Diterima' => 'Paket Telah Diterima',
                    'Gagal Dikirim' => 'Pengiriman Gagal',
                    'Penundaan Pengiriman' => 'Pengiriman Ditunda',
                    default => $statusVal
                };
            @endphp

            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 overflow-hidden">

                <div class="p-6 md:p-8 bg-white border-b border-gray-100">
                    <p class="text-sm font-bold text-gray-500 mb-1">Nomor Resi Anda</p>
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ $shipment->tracking_number }}</h2>

                    <div class="mt-5 flex items-start gap-3">
                        @if($isFinal)
                            @if($isSelesai)
                                <i data-lucide="check-circle-2" class="w-8 h-8 text-gray-400"></i>
                            @elseif($isGagal)
                                <i data-lucide="x-circle" class="w-8 h-8 text-red-500"></i>
                            @elseif($isTunda)
                                <i data-lucide="clock" class="w-8 h-8 text-orange-500"></i>
                            @endif
                        @else
                            <i data-lucide="truck" class="w-8 h-8 text-green-500"></i>
                        @endif

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Status Saat Ini</p>
                            <h3 class="text-xl font-bold
                                {{ $isFinal ? ($isSelesai ? 'text-gray-800' : ($isGagal ? 'text-red-700' : 'text-orange-600')) : 'text-green-600' }}">
                                {{ strtoupper($displayStatus) }}
                            </h3>
                            @if($isFinal)
                                <p class="text-sm font-bold text-gray-500 mt-1">
                                    Update Terakhir: {{ $shipment->updated_at->format('d F Y - H:i') }} WIB
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 md:px-8 bg-gray-50 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kota Asal</p>
                        <p class="font-bold text-gray-900 uppercase text-sm">{{ $shipment->origin_city }}</p>
                        <p class="text-xs text-gray-500 mt-1 font-medium">Berat: {{ number_format($shipment->weight, 1) }} Kg</p>
                        <p class="text-xs text-gray-500 font-medium">Koli: {{ $shipment->jumlah_koli }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dikirim Ke</p>
                        <p class="font-bold text-gray-900 uppercase text-sm">{{ $shipment->destination_city }}</p>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $shipment->receiver_name }}</p>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $shipment->receiver_address }}</p>
                    </div>
                </div>

                <div class="p-6 md:p-8 bg-white border-t border-gray-100">
                    <h4 class="text-xs font-black text-gray-400 mb-6 uppercase tracking-widest">Riwayat Perjalanan</h4>

                    <div class="relative border-l-2 border-gray-200 ml-3 space-y-8">

                        <div class="relative pl-6">
                            <span class="absolute -left-[11px] top-1 w-5 h-5 rounded-full {{ $s1_color }} flex items-center justify-center ring-4 ring-white transition-colors">
                                @if($isDiprosesActive || $isDiprosesPast)
                                    <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                @else
                                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                @endif
                            </span>
                            <h5 class="text-sm font-bold {{ $isDiprosesActive ? 'text-green-600' : ($isDiprosesPast ? 'text-gray-600' : 'text-gray-400') }}">Resi Dibuat & Barang Diterima</h5>
                            <p class="text-xs {{ $isDiprosesActive ? 'text-green-500' : 'text-gray-500' }} mt-1">Gudang Pusat {{ $shipment->origin_city }}</p>
                            <p class="text-[11px] font-bold {{ $isDiprosesActive ? 'text-green-600' : 'text-gray-400' }} mt-1">
                                {{ $shipment->created_at->format('d M Y, H:i') }} WIB
                            </p>
                        </div>

                        <div class="relative pl-6">
                            <span class="absolute -left-[11px] top-1 w-5 h-5 rounded-full {{ $s2_color }} flex items-center justify-center ring-4 ring-white transition-colors">
                                @if($isPerjalananActive || $isPerjalananPast)
                                    <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                @else
                                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                @endif
                            </span>
                            <h5 class="text-sm font-bold {{ $isPerjalananActive ? 'text-green-600' : ($isPerjalananPast ? 'text-gray-600' : 'text-gray-400') }}">Dalam Perjalanan</h5>
                            <p class="text-xs {{ $isPerjalananActive ? 'text-green-500' : 'text-gray-500' }} mt-1">
                                Paket sedang dibawa menuju {{ $shipment->destination_city }}
                            </p>
                            @if($isPerjalananActive || $isPerjalananPast)
                                @php
                                    $waktuJalan = optional($shipment->manifest)->departed_at ?? optional($shipment->manifest)->created_at;
                                @endphp
                                @if($waktuJalan)
                                    <p class="text-[11px] font-bold {{ $isPerjalananActive ? 'text-green-600' : 'text-gray-400' }} mt-1">
                                        {{ \Carbon\Carbon::parse($waktuJalan)->format('d M Y, H:i') }} WIB
                                    </p>
                                @endif
                            @endif
                        </div>

                        <div class="relative pl-6">
                            <span class="absolute -left-[11px] top-1 w-5 h-5 rounded-full {{ $s3_color }} flex items-center justify-center ring-4 ring-white transition-colors">
                                @if($isPengantaranActive || $isPengantaranPast)
                                    <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                @else
                                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                @endif
                            </span>
                            <h5 class="text-sm font-bold {{ $isPengantaranActive ? 'text-green-600' : ($isPengantaranPast ? 'text-gray-600' : 'text-gray-400') }}">Sedang Dalam Pengantaran</h5>
                            <p class="text-xs {{ $isPengantaranActive ? 'text-green-500' : 'text-gray-500' }} mt-1">
                                Kurir sedang menuju ke alamat penerima.
                            </p>

                            @if(($isPengantaranActive || $isPengantaranPast) && optional($shipment->manifest)->courier)
                                <div class="mt-3 bg-gray-50 border border-gray-100 rounded-xl p-3 flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-200 text-gray-700 rounded-full flex items-center justify-center">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase">Petugas Kurir</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $shipment->manifest->courier->name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($isPengantaranActive)
                                <p class="text-[11px] font-bold text-green-600 mt-2">
                                    {{ $shipment->updated_at->format('d M Y, H:i') }} WIB
                                </p>
                            @endif
                        </div>

                        <div class="relative pl-6">
                            <span class="absolute -left-[11px] top-1 w-5 h-5 rounded-full {{ $s4_color }} flex items-center justify-center ring-4 ring-white transition-colors">
                                @if($isFinal)
                                    <i data-lucide="{{ $isSelesai ? 'check' : ($isGagal ? 'x' : 'clock') }}" class="w-3 h-3 text-white"></i>
                                @else
                                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                @endif
                            </span>

                            <h5 class="text-sm font-bold {{ $isFinal ? 'text-gray-900' : 'text-gray-400' }}">
                                @if($isSelesai)
                                    Paket Telah Diterima
                                @elseif($isGagal)
                                    Pengiriman Gagal
                                @elseif($isTunda)
                                    Pengiriman Ditunda
                                @else
                                    Menunggu Pengantaran Selesai
                                @endif
                            </h5>

                            @if($isSelesai && optional($shipment->proofOfDelivery)->photo_path)
                                <div class="mt-3 bg-white border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] rounded-2xl p-4">
                                    <p class="text-sm text-gray-800 font-medium mb-3">
                                        Diserahkan kepada: <span class="font-bold text-gray-900">{{ $shipment->proofOfDelivery->received_by_name }}</span>
                                    </p>

                                    <div class="rounded-xl overflow-hidden bg-gray-100 max-w-sm border border-gray-200">
                                        <img src="{{ asset('storage/' . $shipment->proofOfDelivery->photo_path) }}" alt="Bukti Penerimaan" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-500">
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-2 italic">Foto dari petugas kurir lapangan</p>
                                </div>
                            @else
                                <p class="text-xs {{ $isFinal ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                    @if($isGagal)
                                        Paket tidak dapat diserahkan (Alamat tidak valid / penerima tidak ada).
                                    @elseif($isTunda)
                                        Jadwal pengantaran dijadwalkan ulang karena kendala cuaca/operasional.
                                    @else
                                        Paket belum sampai di tangan penerima.
                                    @endif
                                </p>
                            @endif

                            @if($isFinal)
                                <p class="text-[11px] font-bold text-gray-500 mt-2">
                                    {{ $shipment->updated_at->format('d M Y, H:i') }} WIB
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        @endif

        @if(!isset($shipment) && !isset($error))
            <div class="text-center pt-10">
                <div class="w-16 h-16 bg-white border border-gray-100 shadow-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="search" class="w-8 h-8 text-gray-400"></i>
                </div>
                <p class="text-gray-400 font-medium text-sm">Cek riwayat perjalanan paket Anda di sini.</p>
            </div>
        @endif

    </div>

    <footer class="bg-white border-t border-gray-100 mt-auto py-6">
        <div class="max-w-4xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-gray-400 text-xs font-medium">&copy; {{ date('Y') }} PT. Ken Ekspres Nusantara. All rights reserved.</p>

            <div class="flex items-center gap-4 text-xs font-bold">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-800 transition-colors bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-lg">Masuk ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-600 transition-colors flex items-center gap-1.5">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i> Portal Pegawai
                    </a>
                @endauth
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
