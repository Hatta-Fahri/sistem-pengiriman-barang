@extends('layouts.app')

@section('header-title', 'Buat Jadwal & Alokasi Muatan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('manifests.index') }}" class="hover:text-blue-600 font-medium">Manifest</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="font-bold text-gray-900">Buat Jadwal Baru</span>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('manifests.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-xs">1</span>
                            Pilih Resi / Muatan
                        </h3>
                    </div>

                    <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white sticky top-0 shadow-sm text-xs text-gray-400 uppercase font-bold z-10">
                                <tr>
                                    <th class="px-6 py-4 w-10"><input type="checkbox" id="selectAll" class="rounded text-blue-600 w-4 h-4 cursor-pointer"></th>
                                    <th class="px-6 py-4">No. Resi</th>
                                    <th class="px-6 py-4">Penerima & Tujuan</th>
                                    <th class="px-6 py-4 text-right">Berat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                @forelse($availableShipments as $shipment)
                                <tr class="hover:bg-blue-50/50 cursor-pointer transition-colors" onclick="document.getElementById('ship_{{ $shipment->id }}').click()">
                                    <td class="px-6 py-4" onclick="event.stopPropagation()">
                                        <input type="checkbox" name="shipment_ids[]" value="{{ $shipment->id }}" id="ship_{{ $shipment->id }}"
                                               data-weight="{{ $shipment->weight }}"
                                               class="shipment-checkbox rounded text-blue-600 border-gray-300 w-4 h-4 cursor-pointer focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4 font-bold text-blue-700">{{ $shipment->tracking_number }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $shipment->receiver_name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $shipment->destination_city }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold">{{ number_format($shipment->weight, 1) }} Kg</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                        <i data-lucide="package-check" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                                        <p>Semua resi sudah dialokasikan ke jadwal.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-6">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-xs">2</span>
                            Detail Keberangkatan
                        </h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Rute / Tujuan <span class="text-red-500">*</span></label>
                            <input type="text" name="jalur_pengiriman" value="{{ old('jalur_pengiriman') }}" required placeholder="Contoh: Lintas Timur (Medan - Kisaran)" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 text-sm shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Truk <span class="text-red-500">*</span></label>
                            <select name="vehicle_id" id="vehicle_select" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 text-sm shadow-sm">
                                <option value="" data-capacity="0">-- Pilih Armada --</option>
                                @foreach($availableVehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" data-capacity="{{ $vehicle->capacity }}">{{ $vehicle->license_plate }} (Max: {{ number_format($vehicle->capacity, 0) }}Kg)</option>
                                @endforeach
                            </select>

                            <div id="capacity_indicator" class="hidden mt-3 p-3 bg-gray-50 rounded-xl border border-gray-200 transition-all">
                                <div class="flex justify-between items-end mb-1.5">
                                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Muatan</span>
                                    <span id="capacity_text" class="text-xs font-black text-blue-700">0 Kg / 0 Kg</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div id="capacity_bar" class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p id="capacity_warning" class="text-[10px] text-red-600 font-bold mt-1.5 hidden flex items-center gap-1">
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i> Overload! Kurangi resi atau ganti truk.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kurir <span class="text-red-500">*</span></label>
                            <select name="courier_id" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 text-sm shadow-sm">
                                <option value="">-- Pilih Supir --</option>
                                @foreach($availableCouriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 text-sm shadow-sm">{{ old('notes') }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <button type="submit" id="btn_submit" class="w-full py-3.5 bg-blue-700 text-white rounded-xl font-black text-sm uppercase tracking-wide hover:bg-blue-800 transition-colors shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                Simpan Jadwal
                            </button>
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
        const vehicleSelect = document.getElementById('vehicle_select');

        // Element UI
        const indicatorDiv = document.getElementById('capacity_indicator');
        const capacityText = document.getElementById('capacity_text');
        const capacityBar = document.getElementById('capacity_bar');
        const capacityWarning = document.getElementById('capacity_warning');
        const btnSubmit = document.getElementById('btn_submit');

        // Fungsi Hitung Kapasitas
        function calculateCapacity() {
            // 1. Hitung total berat resi yang dicentang
            let totalWeight = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    totalWeight += parseFloat(cb.getAttribute('data-weight') || 0);
                }
            });

            // 2. Ambil kapasitas truk yang dipilih
            const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
            const maxCapacity = parseFloat(selectedOption.getAttribute('data-capacity') || 0);

            // 3. Update UI jika ada resi yang dipilih ATAU truk yang dipilih
            if (totalWeight > 0 || maxCapacity > 0) {
                indicatorDiv.classList.remove('hidden');

                // Format angka cantik ala Indonesia (misal: 1.500)
                let displayWeight = totalWeight.toLocaleString('id-ID', {minimumFractionDigits: 1, maximumFractionDigits: 1});
                let displayMax = maxCapacity > 0 ? maxCapacity.toLocaleString('id-ID') : '-';

                capacityText.innerText = displayWeight + ' Kg / ' + displayMax + ' Kg';

                if (maxCapacity > 0) {
                    let percentage = (totalWeight / maxCapacity) * 100;
                    if (percentage > 100) percentage = 100; // Mentok animasi bar di 100%

                    capacityBar.style.width = percentage + '%';

                    // Logika Warna dan Peringatan Overload
                    if (totalWeight > maxCapacity) {
                        capacityText.className = "text-xs font-black text-red-600";
                        capacityBar.className = "bg-red-600 h-1.5 rounded-full transition-all duration-300";
                        capacityWarning.classList.remove('hidden');
                        btnSubmit.disabled = true; // Kunci tombol submit
                    } else if (percentage >= 85) {
                        capacityText.className = "text-xs font-black text-orange-600";
                        capacityBar.className = "bg-orange-500 h-1.5 rounded-full transition-all duration-300";
                        capacityWarning.classList.add('hidden');
                        btnSubmit.disabled = false;
                    } else {
                        capacityText.className = "text-xs font-black text-blue-700";
                        capacityBar.className = "bg-blue-600 h-1.5 rounded-full transition-all duration-300";
                        capacityWarning.classList.add('hidden');
                        btnSubmit.disabled = false;
                    }
                } else {
                    // Jika truk belum dipilih tapi resi sudah dicentang
                    capacityBar.style.width = '0%';
                    capacityText.className = "text-xs font-black text-gray-600";
                    capacityBar.className = "bg-gray-300 h-1.5 rounded-full transition-all duration-300";
                    capacityWarning.classList.add('hidden');
                    btnSubmit.disabled = false;
                }
            } else {
                // Sembunyikan jika kosong
                indicatorDiv.classList.add('hidden');
                btnSubmit.disabled = false;
            }
        }

        // Event Listener untuk "Centang Semua"
        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                calculateCapacity(); // Update hitungan
            });
        }

        // Event Listener untuk setiap Checkbox Resi
        checkboxes.forEach(cb => {
            cb.addEventListener('change', calculateCapacity);
        });

        // Event Listener untuk Dropdown Truk
        if(vehicleSelect) {
            vehicleSelect.addEventListener('change', calculateCapacity);
        }
    });
</script>
@endsection
