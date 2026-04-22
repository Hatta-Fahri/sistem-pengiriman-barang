<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail Pengiriman - KEN Logistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            /* 👇 Diubah jadi A4 portrait 👇 */
            @page { size: A4 portrait; margin: 15mm; }
            .no-print { display: none; }
            img { max-height: 80px; object-fit: cover; }
        }
    </style>
</head>
<body class="bg-white p-10 text-gray-900" onload="window.print()">

    <div class="flex justify-between items-start border-b-4 border-gray-900 pb-5 mb-8">
        <div>
            <h1 class="text-3xl font-black text-blue-800 print:text-black tracking-tighter italic flex items-center gap-1">
                KEN <span class="text-gray-900 font-bold text-xl not-italic uppercase tracking-widest mt-1">Logistics</span>
            </h1>
            <p class="text-sm text-gray-500 print:text-gray-700 mt-1 font-semibold tracking-wide">PT. KEN EKSPRES NUSANTARA</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-black uppercase tracking-widest text-gray-900">Laporan Detail Pengiriman</h2>
            <p class="text-sm text-gray-600 mt-1">Periode: <span class="font-bold">{{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</span></p>
        </div>
    </div>

    <table class="w-full text-xs border-collapse">
        <thead>
            <tr class="bg-gray-100 border-y-2 border-gray-900">
                <th class="py-3 px-2 text-left uppercase tracking-wider font-bold">No. Resi</th>
                <th class="py-3 px-2 text-left uppercase tracking-wider font-bold">Tgl Diterima</th>
                <th class="py-3 px-2 text-left uppercase tracking-wider font-bold">Pengirim</th>
                <th class="py-3 px-2 text-left uppercase tracking-wider font-bold">Penerima (POD)</th>
                <th class="py-3 px-2 text-left uppercase tracking-wider font-bold">Kendaraan</th>
                <th class="py-3 px-2 text-left uppercase tracking-wider font-bold">Kurir</th>
                <th class="py-3 px-2 text-center uppercase tracking-wider font-bold">Foto POD</th>
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
                            <img src="{{ asset('storage/' . $s->proofOfDelivery->photo_path) }}"
                                 alt="POD"
                                 class="h-16 w-16 object-cover rounded border border-gray-300 mx-auto">
                        @else
                            <span class="text-[10px] bg-red-100 text-red-600 px-2 py-1 rounded font-bold uppercase">No Photo</span>
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

    <div class="mt-20 flex justify-end text-center no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow-sm hover:bg-blue-700">Print / Save as PDF</button>
    </div>
</body>
</html>
