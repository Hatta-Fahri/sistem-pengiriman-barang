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

    <!-- Lottie Player Script -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        body { font-family: 'Figtree', sans-serif; }
        .bg-grid-pattern {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>
</head>
<body class="antialiased bg-[#f8fafc] flex flex-col min-h-screen overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 py-4 px-6 sm:px-12 flex justify-between items-center relative z-50 shadow-sm sticky top-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-700 rounded-xl flex items-center justify-center text-white shadow-md shadow-blue-700/20">
                <i data-lucide="chevrons-right" class="w-6 h-6"></i>
            </div>
            <div class="flex flex-col justify-center">
                <span class="text-2xl font-black italic tracking-tight text-blue-800 leading-none">KEN</span>
                <span class="text-[10px] font-black text-red-600 tracking-[0.25em] mt-0.5 uppercase">Logistics</span>
            </div>
        </div>

        <div class="hidden sm:flex items-center gap-4 text-xs font-bold">
            @auth
                <a href="{{ url('/dashboard') }}" class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-1.5">
                    <i data-lucide="lock" class="w-3.5 h-3.5"></i> Pegawai
                </a>
            @endauth
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative bg-white overflow-hidden border-b border-gray-100 min-h-[500px] flex items-center justify-center">
        <!-- Background Ornaments Pattern -->
        <div class="absolute inset-0 bg-grid-pattern opacity-30 z-0 pointer-events-none"></div>
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[600px] h-[600px] bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none z-0"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[400px] h-[400px] bg-red-50 rounded-full blur-3xl opacity-50 pointer-events-none z-0"></div>

        <!-- Lottie Background Kiri -->
        <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/4 md:-translate-x-1/6 w-[300px] md:w-[450px] opacity-25 md:opacity-40 pointer-events-none z-0">
            <lottie-player src="{{ asset('animation2.json') }}" background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay></lottie-player>
        </div>

        <!-- Lottie Background Kanan -->
        <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/4 md:translate-x-1/6 w-[300px] md:w-[450px] opacity-25 md:opacity-40 pointer-events-none z-0">
            <lottie-player src="{{ asset('animation3.json') }}" background="transparent" speed="1" style="width: 100%; height: auto;" loop autoplay></lottie-player>
        </div>

        <!-- Konten Pencarian Utama -->
        <div class="max-w-3xl mx-auto px-4 sm:px-6 pt-16 pb-24 relative z-10 text-center w-full">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50/80 backdrop-blur-sm border border-blue-100 text-blue-700 text-xs font-bold tracking-wider mb-6 shadow-sm mx-auto">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-4 tracking-tight leading-[1.1] drop-shadow-sm">
                Pantau Kiriman <br class="hidden sm:block"/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500">Dengan Mudah</span>
            </h1>

            <p class="text-gray-600 text-base md:text-lg mb-10 font-medium max-w-xl mx-auto drop-shadow-sm">
                Masukkan nomor resi KEN Logistics Anda untuk mengetahui status paket secara real-time dan akurat.
            </p>

            <form action="{{ route('tracking.index') }}" method="GET" class="max-w-xl mx-auto relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-500 z-0"></div>

                <div class="relative flex flex-col sm:flex-row items-center glass-panel p-2 rounded-2xl shadow-xl border border-white/60 focus-within:ring-2 focus-within:ring-blue-400 transition-all gap-2 z-10 bg-white/80">
                    <div class="flex items-center flex-1 w-full pl-3">
                        <i data-lucide="box" class="w-5 h-5 text-blue-500"></i>
                        <input type="text" name="resi" value="{{ $resi ?? '' }}" placeholder="Contoh: KEN-20260417-ABCD" required autocomplete="off"
                            class="w-full bg-transparent border-0 focus:ring-0 px-3 py-3 text-base font-bold text-gray-900 placeholder-gray-500 uppercase tracking-wide outline-none">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-blue-700 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-blue-800 hover:shadow-lg hover:-translate-y-0.5 transition-all whitespace-nowrap flex items-center justify-center gap-2">
                        Lacak <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-grow max-w-3xl mx-auto w-full px-4 -mt-10 relative z-20 pb-20">

        <!-- LOGIKA ERROR -->
        @if(isset($error))
            <div class="bg-white rounded-3xl shadow-xl shadow-red-900/5 border border-red-100 p-8 text-center animate-bounce-short">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 rotate-12">
                    <i data-lucide="package-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Resi Tidak Ditemukan</h3>
                <p class="text-gray-500 text-sm font-medium">{{ $error }}</p>
                <a href="{{ route('tracking.index') }}" class="inline-block mt-6 px-6 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition-colors">Coba Lagi</a>
            </div>
        @endif

        <!-- LOGIKA SUCCESS -->
        @if(isset($shipment))
            @php
                // Teks Utama Header Berdasarkan Current Status
                $statusVal = $shipment->current_status->value ?? $shipment->current_status;
                $isFinal   = in_array($statusVal, ['Diterima', 'Selesai']);
                $isTunda   = in_array($statusVal, ['Penundaan Pengiriman', 'Gagal Dikirim', 'Dibatalkan']);

                $displayStatus = match($statusVal) {
                    'Diproses', 'Menunggu Jadwal' => 'Sedang Diproses di Gudang',
                    'Dalam Perjalanan' => 'Dalam Perjalanan',
                    'Tiba di Kota Tujuan' => 'Tiba di Kota Tujuan',
                    'Dalam Pengantaran' => 'Sedang Dalam Pengantaran Kurir',
                    'Diterima' => 'Paket Telah Diterima',
                    'Penundaan Pengiriman' => 'Pengiriman Ditunda',
                    'Dibatalkan' => 'Resi Dibatalkan, Hubungi CS',
                    default => $statusVal
                };
            @endphp

            <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-gray-100 overflow-hidden">

                <!-- Header Status -->
                <div class="p-6 md:p-8 bg-gradient-to-br from-white to-gray-50 border-b border-gray-100">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nomor Resi</p>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-full uppercase tracking-wider">Kargo Ken</span>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $shipment->tracking_number }}</h2>

                    <div class="mt-6 flex items-start gap-4 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                            {{ $isFinal ? 'bg-green-100 text-green-600' : ($isTunda ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600') }}">
                            @if($isFinal)
                                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                            @elseif($isTunda)
                                <i data-lucide="clock" class="w-6 h-6"></i>
                            @else
                                <i data-lucide="truck" class="w-6 h-6"></i>
                            @endif
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Status Saat Ini</p>
                            <h3 class="text-lg md:text-xl font-black
                                {{ $isFinal ? 'text-green-600' : ($isTunda ? 'text-orange-600' : 'text-blue-700') }}">
                                {{ strtoupper($displayStatus) }}
                            </h3>
                            <p class="text-xs font-bold text-gray-500 mt-1 flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ $shipment->updated_at->format('d F Y - H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Pengirim & Penerima -->
                <div class="px-6 py-6 md:px-8 bg-white grid grid-cols-2 gap-6 relative">
                    <div class="absolute left-1/2 top-8 bottom-8 w-px bg-gray-100 hidden sm:block"></div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-500"></i> Asal</p>
                        <p class="font-black text-gray-900 uppercase text-base">{{ $shipment->origin_city }}</p>
                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-gray-500 font-medium bg-gray-50 inline-block px-2 py-1 rounded">Berat: {{ number_format($shipment->weight, 1) }} Kg</p>
                            <p class="text-xs text-gray-500 font-medium bg-gray-50 inline-block px-2 py-1 rounded">Koli: {{ $shipment->jumlah_koli }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5"><i data-lucide="flag" class="w-3.5 h-3.5 text-green-500"></i> Tujuan</p>
                        <p class="font-black text-gray-900 uppercase text-base">{{ $shipment->destination_city }}</p>
                        <p class="text-sm font-bold text-gray-700 mt-1">{{ $shipment->receiver_name }}</p>
                        <p class="text-xs text-gray-500 leading-relaxed mt-0.5 line-clamp-2" title="{{ $shipment->receiver_address }}">{{ $shipment->receiver_address }}</p>
                    </div>
                </div>

                <!-- Timeline Perjalanan (DINAMIS DARI TRACKING LOGS) -->
                <div class="p-6 md:p-8 bg-gray-50 border-t border-gray-100 rounded-b-3xl">
                    <h4 class="text-xs font-black text-gray-400 mb-8 uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="list-tree" class="w-4 h-4"></i> Detail Perjalanan
                    </h4>

                    <div class="relative border-l-2 border-gray-200 ml-4 space-y-8">

                        @forelse($shipment->trackings as $log)
                            @php
                                // Ambil nilai string murni jika berbentuk Enum
                                $logStatus = $log->status->value ?? $log->status;
                                $isOngoing = $loop->last;

                                // Penentuan warna titik (Hijau jika status berlangsung/terakhir, sisanya abu-abu)
                                $dotColor = $isOngoing ? 'bg-green-500 shadow-green-500/30' : 'bg-gray-400';
                                $textColor = $isOngoing ? 'text-green-600' : 'text-gray-500';

                                // Penentuan Icon
                                $icon = match($logStatus) {
                                    'Penundaan Pengiriman', 'Gagal Dikirim' => 'clock',
                                    'Dibatalkan' => 'x',
                                    'Diterima', 'Selesai' => 'check',
                                    'Dalam Pengantaran' => 'truck',
                                    'Dalam Perjalanan', 'Tiba di Tujuan' => 'arrow-right',
                                    default => 'check'
                                };
                            @endphp

                            <div class="relative pl-8">
                                <!-- Titik Timeline -->
                                <span class="absolute -left-[13px] top-0.5 w-6 h-6 rounded-full {{ $dotColor }} flex items-center justify-center ring-4 ring-gray-50 transition-colors shadow-sm">
                                    <i data-lucide="{{ $icon }}" class="w-3 h-3 text-white"></i>
                                </span>

                                <!-- Judul Status -->
                                <h5 class="text-sm font-bold {{ $textColor }}">
                                    {{ $logStatus === 'Diproses' ? 'Resi Dibuat & Barang Diterima' : $logStatus }}
                                </h5>

                                <!-- Lokasi -->
                                @if($log->location)
                                    <p class="text-xs font-medium text-gray-500 mt-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 inline pb-0.5"></i> {{ $log->location }}
                                    </p>
                                @endif

                                <!-- Catatan/Note -->
                                @if($log->notes)
                                    <p class="text-xs {{ $logStatus === 'Penundaan Pengiriman' ? 'text-orange-500' : 'text-gray-500' }} mt-1">
                                        {{ $log->notes }}
                                    </p>
                                @endif

                                <p class="text-[11px] font-bold text-gray-400 mt-1.5">
                                    {{ $log->created_at->format('d M Y, H:i') }} WIB
                                </p>

                                <!-- Kotak Info Kurir (Muncul pas Dalam Pengantaran saja) -->
                                @if($logStatus === 'Dalam Pengantaran' && optional($log->recordedBy)->role === 'kurir')
                                    <div class="mt-3 bg-white border border-gray-100 rounded-xl p-3 inline-flex items-center gap-3.5 shadow-sm pr-8">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                                            <i data-lucide="user" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Petugas Kurir</p>
                                            <p class="text-sm font-black text-gray-900 leading-none mb-1.5">{{ $log->recordedBy->name }}</p>
                                            @if($log->recordedBy->phone)
                                                <a href="tel:{{ $log->recordedBy->phone }}" class="inline-flex w-fit px-2 py-1 bg-blue-50 rounded-md text-[11px] font-bold text-blue-700 hover:bg-blue-100 hover:text-blue-800 transition-colors items-center gap-1.5">
                                                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $log->recordedBy->phone }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Kotak POD (Muncul HANYA di status Diterima) -->
                                @if(in_array($logStatus, ['Diterima', 'Selesai']) && optional($shipment->proofOfDelivery)->photo_path)
                                    <div class="mt-4 bg-white border border-gray-100 shadow-md rounded-2xl p-4 relative overflow-hidden">
                                        <div class="absolute top-0 left-0 w-1 h-full bg-green-500"></div>
                                        <p class="text-sm text-gray-800 font-medium mb-3 pl-2">
                                            Diserahkan kepada: <span class="font-black text-gray-900">{{ $shipment->proofOfDelivery->received_by_name }}</span>
                                        </p>

                                        <div class="rounded-xl overflow-hidden bg-gray-100 max-w-sm border border-gray-200">
                                            <img src="{{ $shipment->proofOfDelivery->photo_url }}" alt="Bukti Penerimaan" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-500">
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-2 italic font-medium pl-2">
                                            <i data-lucide="camera" class="w-3 h-3 inline"></i> Bukti foto dari kurir
                                        </p>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <!-- Empty State jika log tracking benar-benar kosong -->
                            <div class="relative pl-8">
                                <span class="absolute -left-[13px] top-0.5 w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center ring-4 ring-gray-50">
                                    <div class="w-2.5 h-2.5 bg-gray-400 rounded-full"></div>
                                </span>
                                <h5 class="text-sm font-bold text-gray-400">Menunggu Update Sistem</h5>
                                <p class="text-xs text-gray-400 mt-1">Data riwayat pengiriman belum tersedia.</p>
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>
        @endif

        <!-- EMPTY STATE PENCARIAN -->
        @if(!isset($shipment) && !isset($error))
            <div class="text-center pt-8 md:pt-16 pb-8">
                <div class="w-16 h-16 bg-white border border-gray-100 shadow-sm rounded-2xl flex items-center justify-center mx-auto mb-5 text-gray-400">
                    <i data-lucide="map" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2 tracking-tight">Siap Melacak Paket?</h3>
                <p class="text-gray-500 font-medium text-sm max-w-sm mx-auto leading-relaxed">
                    Masukkan nomor resi pada kolom pencarian di atas untuk melihat detail perjalanan paket Anda saat ini.
                </p>
            </div>
        @endif

    </div>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-100 mt-auto py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div>
                <p class="text-gray-800 font-bold text-sm">PT. Ken Ekspres Nusantara</p>
                <p class="text-gray-400 text-xs font-medium mt-1">&copy; {{ date('Y') }} All rights reserved. Handal, Cepat, Aman.</p>
            </div>

            <div class="flex items-center justify-center gap-6 text-xs font-bold">
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
