<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail Pengiriman - KEN Logistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

        /* Area Kertas Putih A4 untuk Tampilan Web */
        .paper-container {
            background-color: white;
            width: 210mm; /* Lebar A4 */
            min-height: 297mm; /* Tinggi minimal A4 */
            margin: 20px auto;
            padding: 15mm;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            position: relative;
            padding-bottom: 40mm; /* Space untuk footer di web */
        }

        /* Aturan Khusus Saat Diprint */
        @media print {
            body { background-color: white; }
            .paper-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
                padding-bottom: 0;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 30mm 15mm; /* Margin bawah dibesarkan untuk footer */
            }
            .no-print { display: none; }
            img { max-height: 80px; object-fit: cover; }

            /* Memaksa elemen footer berada di bagian paling bawah tiap halaman cetak */
            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: white; /* Mencegah tulisan tertumpuk */
            }
            /* Memberi jarak pada tabel agar tidak tertutup footer di halaman terakhir */
            .content-area {
                padding-bottom: 40mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <!-- Container Kertas A4 -->
    <div class="paper-container">

        <!-- HEADER (KOP ATAS) -->
        <div class="text-center mb-10 mt-4">
            <!-- Jika Anda punya gambar logo, hapus baris teks ini dan gunakan tag <img> di bawahnya -->
            <!-- <img src="/path-ke-logo-anda.png" alt="Logo KEN" class="h-24 mx-auto mb-2"> -->

            <p class="text-xs font-bold text-red-400 italic mb-1 uppercase tracking-wider">PT. Kiriman Ekspres Nusantara</p>
            <div class="flex items-center justify-center gap-1">
                <span class="text-5xl font-black text-blue-400 tracking-tighter uppercase">KEN</span>
            </div>
            <div class="text-3xl font-black text-red-400 uppercase tracking-widest mt-[-5px]">Logistics</div>
            <p class="text-[10px] font-bold text-red-400 mt-1 uppercase tracking-widest">
                <span class="text-red-400">Handal</span> . <span class="text-blue-400">Cepat</span> . <span class="text-red-400">Aman</span>
            </p>
        </div>

        <!-- JUDUL LAPORAN -->
        <div class="text-center mb-8 border-b-2 border-gray-200 pb-4">
            <h2 class="text-xl font-black uppercase tracking-widest text-gray-900">Laporan Detail Pengiriman</h2>
            <p class="text-sm text-gray-600 mt-1">Periode: <span class="font-bold">{{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</span></p>
        </div>

        <!-- ISI TABEL -->
        <div class="content-area">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-300">
                        <th class="py-3 px-2 text-left uppercase tracking-wider font-bold text-gray-700">No. Resi</th>
                        <th class="py-3 px-2 text-left uppercase tracking-wider font-bold text-gray-700">Tgl Diterima</th>
                        <th class="py-3 px-2 text-left uppercase tracking-wider font-bold text-gray-700">Pengirim</th>
                        <th class="py-3 px-2 text-left uppercase tracking-wider font-bold text-gray-700">Penerima (POD)</th>
                        <th class="py-3 px-2 text-left uppercase tracking-wider font-bold text-gray-700">Kendaraan</th>
                        <th class="py-3 px-2 text-left uppercase tracking-wider font-bold text-gray-700">Kurir</th>
                        <th class="py-3 px-2 text-center uppercase tracking-wider font-bold text-gray-700">Foto POD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($shipments as $s)
                        <tr>
                            <td class="py-3 px-2 font-bold text-gray-900">{{ $s->tracking_number }}</td>
                            <td class="py-3 px-2 font-medium">
                                {{ $s->proofOfDelivery ? \Carbon\Carbon::parse($s->proofOfDelivery->delivered_at)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-2 uppercase">{{ $s->sender_name }}</td>
                            <td class="py-3 px-2 uppercase font-bold text-gray-800">
                                {{ optional($s->proofOfDelivery)->received_by_name ?? '-' }}
                            </td>
                            <td class="py-3 px-2 uppercase font-bold text-gray-700">
                                {{ optional(optional($s->manifest)->vehicle)->license_plate ?? '-' }}
                            </td>
                            <td class="py-3 px-2 font-bold uppercase">
                                {{ optional(optional($s->manifest)->courier)->name ?? '-' }}
                            </td>
                            <td class="py-3 px-2 text-center">
                                @if($s->proofOfDelivery && $s->proofOfDelivery->photo_path)
                                    <img src="{{ $s->proofOfDelivery->photo_url }}"
                                         alt="POD"
                                         class="h-16 w-16 object-cover rounded border border-gray-300 mx-auto">
                                @else
                                    <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded font-bold uppercase">No Photo</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 italic">Tidak ada data paket yang berhasil dikirim pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- FOOTER (KOP BAWAH) -->
        <div class="print-footer grid grid-cols-2 gap-8 text-[11px] text-[#7196d4] font-medium pt-4 pb-4">
            <!-- Kolom Alamat -->
            <div>
                <p class="font-bold mb-0.5">OFFICE ADDRESS:</p>
                <p class="leading-relaxed">
                    Komplek Spring Ville Residence no 6,<br>
                    Jl. Ekarasmi - Kelurahan Gedung Johor<br>
                    - Kecamatan Medan Johor, Medan,<br>
                    20147, Provinsi Sumatera Utara.
                </p>
            </div>

            <!-- Kolom Kontak -->
            <div class="pl-8">
                <table class="w-full">
                    <tr>
                        <td class="font-bold w-24 align-top">Office Number:</td>
                        <td>+6282275108520</td>
                    </tr>
                    <tr>
                        <td class="font-bold align-top">Office Number:</td>
                        <td>+628126494711</td>
                    </tr>
                    <tr>
                        <td class="font-bold align-top mt-1 block">Website:</td>
                        <td class="mt-1 block">www.kenlogistics.org</td>
                    </tr>
                    <tr>
                        <td class="font-bold align-top">Email:</td>
                        <td>
                            customerservice@kenlogistics.org<br>
                            sinex.iskandar@gmail.com
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
