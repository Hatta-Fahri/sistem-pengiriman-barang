<div id="addManifestModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Buat Jadwal Baru (Draft)</h3>
                <button type="button" onclick="closeModal('addManifestModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('manifests.store') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rute / Jalur Pengiriman <span class="text-red-500">*</span></label>
                        <input type="text" name="jalur_pengiriman" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Gudang Medan - Cabang Siantar">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kendaraan / Truk</label>
                            <select name="vehicle_id" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Tentukan Nanti --</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} ({{ number_format($vehicle->capacity, 0) }} Kg)</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Supir / Kurir</label>
                            <select name="courier_id" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Tentukan Nanti --</option>
                                @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->courier_code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Instruksi khusus untuk perjalanan ini..."></textarea>
                    </div>

                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('addManifestModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editManifestModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Edit Armada / Rute <span id="display_manifest_code" class="text-blue-600 ml-1"></span></h3>
                <button type="button" onclick="closeModal('editManifestModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="editManifestForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-5 space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rute / Jalur Pengiriman <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_jalur" name="jalur_pengiriman" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Kendaraan / Truk</label>
                            <select id="edit_vehicle_id" name="vehicle_id" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Kosongkan --</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} ({{ number_format($vehicle->capacity, 0) }} Kg)</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Supir / Kurir</label>
                            <select id="edit_courier_id" name="courier_id" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Kosongkan --</option>
                                @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->courier_code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                        <textarea id="edit_notes" name="notes" rows="2" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('editManifestModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Update Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) { document.getElementById(modalId).classList.remove('hidden'); }
    function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }

    function editManifest(manifest) {
        document.getElementById('editManifestForm').action = `/admin/manifests/${manifest.id}`;
        document.getElementById('display_manifest_code').innerText = `(${manifest.manifest_code})`;

        document.getElementById('edit_jalur').value = manifest.jalur_pengiriman;
        document.getElementById('edit_vehicle_id').value = manifest.vehicle_id || "";
        document.getElementById('edit_courier_id').value = manifest.courier_id || "";
        document.getElementById('edit_notes').value = manifest.notes || "";

        openModal('editManifestModal');
    }
</script>
