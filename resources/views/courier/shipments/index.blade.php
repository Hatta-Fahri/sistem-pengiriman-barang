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

            // Progress Bar hanya dihitung jika sudah di titik akhir
            $paketSelesai = $activeManifest->shipments->filter(function($shipment) {
                $status = $shipment->current_status->value ?? $shipment->current_status;
                return in_array($status, ['Diterima', 'Penundaan Pengiriman']);
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
                    $isSelesai = in_array($statusAsli, ['Diterima', 'Penundaan Pengiriman']);

                    // Warna badge disesuaikan dengan Enum terbaru
                    $statusColor = match($statusAsli) {
                        'Diterima' => 'bg-green-50 text-green-700 border-green-200',
                        'Penundaan Pengiriman' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'Dalam Pengantaran' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Tiba di Tujuan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'Dalam Perjalanan' => 'bg-purple-50 text-purple-700 border-purple-200',
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
                            <span>Tekan untuk
                                @if($statusAsli === 'Diterima')
                                    lihat bukti pengiriman
                                @elseif($hasStarted)
                                    detail & ubah status
                                @else
                                    lihat detail paket
                                @endif
                            </span>
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

                            @if($statusAsli === 'Diterima')
                                {{-- Status sudah final: tampilkan ringkasan POD, form dikunci permanen --}}
                                <div class="border-t border-green-100 pt-4 space-y-4">

                                    {{-- Badge Status Final --}}
                                    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3">
                                        <i data-lucide="shield-check" class="w-4 h-4 shrink-0 text-green-600"></i>
                                        <div>
                                            <p class="text-xs font-black text-green-800">Paket Telah Diterima — Status Terkunci</p>
                                            <p class="text-[11px] text-green-600 mt-0.5">Status ini sudah final dan tidak dapat diubah lagi.</p>
                                        </div>
                                    </div>

                                    {{-- Ringkasan Data POD --}}
                                    @if($shipment->proofOfDelivery)
                                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
                                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Bukti Pengiriman (POD)</p>
                                            </div>
                                            <div class="p-4 flex flex-col sm:flex-row gap-4 items-start">
                                                <div class="flex-1 space-y-2">
                                                    <div>
                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Diserahkan Kepada</p>
                                                        <p class="text-sm font-black text-gray-900 mt-0.5">{{ $shipment->proofOfDelivery->received_by_name }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Waktu Diterima</p>
                                                        <p class="text-xs font-semibold text-gray-600 mt-0.5">
                                                            {{ \Carbon\Carbon::parse($shipment->proofOfDelivery->delivered_at)->format('d M Y, H:i') }} WIB
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($shipment->proofOfDelivery->photo_path)
                                                    <a href="{{ $shipment->proofOfDelivery->photo_url }}" target="_blank"
                                                       class="block w-full sm:w-28 h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:scale-105 transition-transform duration-300 shrink-0">
                                                        <img src="{{ $shipment->proofOfDelivery->photo_url }}" alt="Foto POD" class="w-full h-full object-cover">
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            @elseif($hasStarted)
                                <form action="{{ route('courier.shipments.update-status', $shipment->id) }}" method="POST" class="flex flex-col gap-4 border-t border-blue-100 pt-4" x-data="{ statusPilihan: '{{ $statusAsli }}' }">
                                    @csrf @method('PUT')

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Update Status Menjadi:</label>
                                        <select name="current_status" x-model="statusPilihan" required class="w-full text-sm rounded-xl border-gray-300 focus:ring-blue-600 focus:border-blue-600 shadow-sm">
                                            @if($statusAsli === 'Dalam Perjalanan')
                                                <option value="Dalam Perjalanan"> Masih di Jalan (Belum Diantar)</option>
                                            @endif
                                            @if(in_array($statusAsli, ['Dalam Perjalanan', 'Tiba di Kota Tujuan']))
                                                <option value="Tiba di Kota Tujuan"> Telah Tiba di Kota Tujuan</option>
                                            @endif
                                            <option value="Dalam Pengantaran"> OTW ke Rumah (Dalam Pengantaran)</option>
                                            <option value="Diterima"> Paket Diterima Customer</option>
                                            <option value="Penundaan Pengiriman"> Ditunda / Reschedule</option>
                                        </select>
                                    </div>

                                    <div x-show="statusPilihan === 'Diterima'" x-collapse x-data="cameraCapture()" x-init="$watch('statusPilihan', value => { if(value !== 'Diterima') stopCamera() })" class="w-full bg-blue-50/80 p-4 rounded-xl border border-blue-200 space-y-4">

                                        <div>
                                            <label class="block text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-1.5">
                                                Nama Penerima Paket <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="received_by_name" placeholder="Contoh: Pak Budi / Istri Pak Budi" :required="statusPilihan === 'Diterima'"
                                                class="w-full text-sm rounded-lg border-blue-200 focus:ring-blue-600 focus:border-blue-600 shadow-sm">
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-2">
                                                <i data-lucide="camera" class="w-3 h-3 inline"></i> Bukti Pengiriman (POD) <span class="text-red-500">*</span>
                                            </label>

                                            <input type="hidden" name="photo_base64" x-model="photoData" :required="statusPilihan === 'Diterima'">

                                            <div x-show="!isCameraOpen && !hasCaptured" class="grid grid-cols-2 gap-3">
                                                <button type="button" @click="initCamera()" class="h-28 bg-white rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-blue-300 cursor-pointer hover:bg-blue-50 transition-colors shadow-sm focus:outline-none">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                                        <i data-lucide="camera" class="w-5 h-5 text-blue-600"></i>
                                                    </div>
                                                    <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wide">Live Kamera</span>
                                                </button>

                                                <label class="h-28 bg-white rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-blue-300 cursor-pointer hover:bg-blue-50 transition-colors shadow-sm relative">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                                        <i data-lucide="image" class="w-5 h-5 text-blue-600"></i>
                                                    </div>
                                                    <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wide">Pilih Galeri</span>
                                                    <input type="file" accept="image/*" class="hidden" @change="handleFileUpload($event)">
                                                </label>
                                            </div>

                                            <div x-show="isCameraOpen && !hasCaptured" class="relative w-full rounded-xl overflow-hidden bg-black shadow-md border border-gray-200 aspect-video">
                                                <video x-ref="video" class="w-full h-full object-cover" playsinline autoplay></video>
                                                <div class="absolute bottom-4 left-0 w-full flex justify-center items-center gap-6 px-4">
                                                    <button type="button" @click="stopCamera()" class="w-10 h-10 bg-red-500 rounded-full shadow-xl flex items-center justify-center hover:scale-105 active:scale-95 transition-transform text-white">
                                                        <i data-lucide="x" class="w-5 h-5"></i>
                                                    </button>
                                                    <button type="button" @click="capture()" class="w-14 h-14 bg-white rounded-full border-4 border-blue-500 shadow-xl flex items-center justify-center hover:scale-105 active:scale-95 transition-transform">
                                                        <div class="w-10 h-10 bg-blue-600 rounded-full"></div>
                                                    </button>
                                                </div>
                                            </div>

                                            <div x-show="hasCaptured" class="relative w-full rounded-xl overflow-hidden border-2 border-green-400 shadow-md">
                                                <img :src="photoData" class="w-full h-auto object-cover aspect-video">
                                                <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded-lg text-[10px] font-bold shadow-sm flex items-center gap-1">
                                                    <i data-lucide="check" class="w-3 h-3"></i> Siap Dikirim
                                                </div>
                                                <button type="button" @click="retake()" class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm hover:bg-red-700 flex items-center gap-1 transition-colors">
                                                    <i data-lucide="refresh-cw" class="w-3 h-3"></i> Ulangi
                                                </button>
                                            </div>

                                            <canvas x-ref="canvas" class="hidden"></canvas>
                                        </div>

                                    </div>

                                    <div class="text-right mt-2">
                                        <button type="submit" class="w-full sm:w-auto bg-blue-700 text-white px-8 py-3 rounded-xl text-sm font-bold shadow-md hover:bg-blue-800 transition-colors inline-flex items-center justify-center gap-2">
                                            <i data-lucide="save" class="w-5 h-5"></i> SIMPAN PERUBAHAN
                                        </button>
                                    </div>
                                </form>
                            @else
                                {{-- Perjalanan belum dimulai: tampilkan info saja, form dikunci --}}
                                <div class="border-t border-blue-100 pt-4">
                                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3">
                                        <i data-lucide="lock" class="w-4 h-4 shrink-0"></i>
                                        <p class="text-xs font-semibold">Update status belum tersedia. Tekan <strong>Mulai Perjalanan Sekarang</strong> di dashboard terlebih dahulu.</p>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] text-center relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tugas Selesai?</h3>
                <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Pastikan semua paket sudah di-update statusnya menjadi Diterima ,atau Penundaan Pengiriman sebelum menutup manifest hari ini.</p>

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
            <p class="text-gray-500 max-w-md mx-auto">Belum ada jadwal pengiriman yang ditugaskan kepada Anda. Silakan hubungi Admin Gudang.</p>
        </div>
    @endif

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cameraCapture', () => ({
            stream: null,
            isCameraOpen: false,
            hasCaptured: false,
            photoData: '',

            initCamera() {
                // Minta izin dan buka kamera
                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                    .then(stream => {
                        this.stream = stream;
                        this.isCameraOpen = true;
                        this.$refs.video.srcObject = stream;
                    })
                    .catch(err => {
                        alert("Gagal mengakses kamera. Error: " + err);
                    });
            },

            capture() {
                const canvas = this.$refs.canvas;
                const video = this.$refs.video;

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

                this.photoData = canvas.toDataURL('image/jpeg', 0.8);
                this.hasCaptured = true;
                this.stopCamera();
            },

            // 👇 FUNGSI BARU UNTUK HANDLE UPLOAD FILE GALERI 👇
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoData = e.target.result; // Set data Base64
                    this.hasCaptured = true;
                    this.isCameraOpen = false;
                };
                reader.readAsDataURL(file);

                // Reset input value agar user bisa pilih foto yang sama jika klik "Ulangi"
                event.target.value = '';
            },

            retake() {
                this.hasCaptured = false;
                this.photoData = '';
                this.stopCamera(); // Mengembalikan layar ke pilihan awal (Pilih Kamera / Upload)
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }
                this.isCameraOpen = false;
            }
        }))
    })
</script>
@endsection
