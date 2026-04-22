<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Data Pengiriman - KEN Logistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            /* 👇 Diubah jadi A4 portrait 👇 */
            @page { size: A4 portrait; margin: 15mm; }
            .no-print { display: none; }
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
            <h2 class="text-xl font-black uppercase tracking-widest text-gray-900">Laporan Rekapitulasi Data Pengiriman</h2>
            <p class="text-sm text-gray-600 mt-1">Periode: <span class="font-bold">{{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</span></p>
        </div>
    </div>

    <table class="w-full text-xs text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 border-y-2 border-gray-900">
                <th class="py-3 px-2 font-bold uppercase tracking-wider">Tanggal</th>
                <th class="py-3 px-2 font-bold uppercase tracking-wider">No. Resi</th>
                <th class="py-3 px-2 font-bold uppercase tracking-wider">Pengirim</th>
                <th class="py-3 px-2 font-bold uppercase tracking-wider">Penerima</th>
                <th class="py-3 px-2 font-bold uppercase tracking-wider">Rute</th>
                <th class="py-3 px-2 font-bold uppercase tracking-wider text-center">Tonase</th>
                <th class="py-3 px-2 font-bold uppercase tracking-wider">Status</th>
                <th class="py-3 px-2 font-bold uppercase tracking-wider text-right">Ongkir</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($shipments as $s)
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
            @endforeach
        </tbody>
    </table>

    <div class="mt-20 flex justify-end text-center no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow-sm hover:bg-blue-700">Print / Save as PDF</button>
    </div>
</body>
</html>
