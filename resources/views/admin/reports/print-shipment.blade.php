<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Pengiriman - KEN Logistics</title>
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
                margin: 15mm 15mm 30mm 15mm;
            }
            .no-print { display: none; }

            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: white;
            }
            .content-area {
                padding-bottom: 40mm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="max-w-[210mm] mx-auto flex justify-end mt-6 mb-2 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow-sm hover:bg-blue-700 transition-colors">
            Cetak / PDF
        </button>
    </div>

    <div class="paper-container">

        <!-- HEADER (KOP ATAS) -->
        <div class="text-center mb-10 mt-4">
            <p class="text-xs font-bold text-red-400 italic mb-1 uppercase tracking-wider">PT. Kiriman Ekspres Nusantara</p>
            <div class="flex items-center justify-center gap-1">
                <span class="text-5xl font-black text-blue-400 tracking-tighter uppercase">KEN</span>
            </div>
            <div clas   s="text-3xl font-black text-red-400 uppercase tracking-widest mt-[-5px]">Logistics</div>
            <p class="text-[10px] font-bold text-red-400 mt-1 uppercase tracking-widest">
                <span class="text-red-400">Handal</span> . <span class="text-blue-400">Cepat</span> . <span class="text-red-400">Aman</span>
            </p>
        </div>

        <!-- JUDUL LAPORAN -->
        <div class="text-center mb-8 border-b-2 border-gray-200 pb-4">
            <h2 class="text-xl font-black uppercase tracking-widest text-gray-900">Laporan Rekapitulasi Data Pengiriman</h2>
            <p class="text-sm text-gray-600 mt-1">Periode: <span class="font-bold">{{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</span></p>
        </div>

        <!-- ISI TABEL -->
        <div class="content-area">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-300">
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-gray-700">Tanggal</th>
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-gray-700">No. Resi</th>
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-gray-700">Pengirim</th>
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-gray-700">Penerima</th>
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-gray-700">Rute</th>
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-center text-gray-700">Tonase</th>
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-gray-700">Status</th>
                        <th class="py-3 px-2 font-bold uppercase tracking-wider text-right text-gray-700">Ongkir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($shipments as $s)
                    <tr>
                        <td class="py-3 px-2 font-medium">{{ $s->created_at->format('d/m/y') }}</td>
                        <td class="py-3 px-2 font-bold text-gray-900">{{ $s->tracking_number }}</td>
                        <td class="py-3 px-2 uppercase">{{ $s->sender_name }}</td>
                        <td class="py-3 px-2 uppercase">{{ $s->receiver_name }}</td>
                        <td class="py-3 px-2 uppercase font-medium">{{ $s->origin_city }} → {{ $s->destination_city }}</td>
                        <td class="py-3 px-2 text-center font-bold">{{ number_format($s->weight, 1) }} Kg</td>
                        <td class="py-3 px-2 font-medium">{{ $s->current_status->value ?? $s->current_status }}</td>
                        <td class="py-3 px-2 text-right font-bold text-gray-900">Rp {{ number_format($s->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500 italic">Tidak ada data pengiriman pada periode ini.</td>
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
