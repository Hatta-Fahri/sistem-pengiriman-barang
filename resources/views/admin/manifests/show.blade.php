@extends('layouts.app')

@section('header-title', 'Dispatch Room (Persiapan Muatan)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('manifests.index') }}" class="hover:text-blue-600 transition-colors font-medium">Manifest</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="font-bold text-gray-900">Persiapan: {{ $manifest->manifest_code }}</span>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl font-bold border border-blue-100 flex items-center gap-2 shadow-sm">
            <i data-lucide="map-pin" class="w-5 h-5"></i>
            Tujuan: {{ $manifest->jalur_pengiriman }}
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5 text-red-500"></i>
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('manifests.generate', $manifest->id) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-xs">1</span>
                            Pilih Resi / Barang (Checklist)
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 ml-8">Centang paket yang akan dimuat ke dalam mobil.</p>
                    </div>

                    <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white sticky top-0 shadow-sm text-xs text-gray-400 uppercase font-bold z-10">
                                <tr>
                                    <th class="px-6 py-4 w-10 border-b border-gray-100">
                                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-4 border-b border-gray-100">No. Resi</th>
                                    <th class="px-6 py-4 border-b border-gray-100">Penerima & Tujuan</th>
                                    <th class="px-6 py-4 border-b border-gray-100 text-right">Berat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($availableShipments as $shipment)
                                <tr class="hover:bg-blue-50/50 transition-colors cursor-pointer" onclick="document.getElementById('ship_{{ $shipment->id }}').click()">
                                    <td class="px-6 py-4" onclick="event.stopPropagation()">
                                        <input type="checkbox" name="shipment_ids[]" value="{{ $shipment->id }}" id="ship_{{ $shipment->id }}"
                                               class="shipment-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 font-bold text-blue-700">{{ $shipment->tracking_number }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $shipment->receiver_name }}</div>
                                        <div class="text-xs text-gray-500">Tujuan: {{ $shipment->destination_city }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold">{{ number_format($shipment->weight, 1) }} Kg</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                        <i data-lucide="package-open" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Tidak ada resi yang berstatus 'Diproses' di gudang.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-xs">2</span>
                            Alokasi Armada
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Mobil (Tersedia) <span class="text-red-500">*</span></label>
                            <select name="vehicle_id" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($availableVehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->type }} ({{ $vehicle->license_plate }}) @if(isset($vehicle->capacity)) - Max: {{ number_format($vehicle->capacity, 0) }}Kg @endif</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kurir (Belum Bertugas) <span class="text-red-500">*</span></label>
                            <select name="courier_id" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">-- Pilih Kurir --</option>
                                @foreach($availableCouriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->courier_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <button type="submit" class="w-full py-3.5 bg-blue-700 text-white rounded-xl font-black text-sm uppercase tracking-wide hover:bg-blue-800 transition-colors shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                                <i data-lucide="rocket" class="w-5 h-5"></i>
                                Simpan & Berangkatkan
                            </button>
                            <p class="text-[10px] text-gray-400 text-center mt-3 font-medium leading-relaxed">
                                Resi akan diubah menjadi "Sedang Dikirim" dan Armada menjadi "Dalam Tugas" / "Sedang Jalan".
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.shipment-checkbox');

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });
        }
    });
</script>
@endsection
