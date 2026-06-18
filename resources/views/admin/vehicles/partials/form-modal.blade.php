<div id="addVehicleModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900" id="modal-title">Tambah Armada Baru</h3>
                <button type="button" onclick="closeModal('addVehicleModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plat Nomor <span class="text-red-500">*</span></label>
                        <input type="text" name="license_plate" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 uppercase" placeholder="Contoh: BK 1234 AB">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <input type="text" name="type" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Mitsubishi L300 / Truk Fuso">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Maksimal (Kg) <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" required min="1" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 2000">
                        <p class="text-xs text-gray-500 mt-1">Kapasitas ini akan digunakan untuk validasi saat pembuatan Manifest jadwal.</p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('addVehicleModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Simpan Armada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editVehicleModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Edit Armada</h3>
                <button type="button" onclick="closeModal('editVehicleModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="editVehicleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plat Nomor <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_license_plate" name="license_plate" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_type" name="type" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Maksimal (Kg) <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_capacity" name="capacity" required min="1" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Kendaraan <span class="text-red-500">*</span></label>
                        <select id="edit_status" name="status" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Sedang Digunakan">Sedang Digunakan</option>
                            <option value="Maintenance">Maintenance (Perbaikan)</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('editVehicleModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Update Armada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Fungsi pintar untuk menyuntikkan data ke dalam form Edit
    function editVehicle(vehicle) {
        // 1. Ubah Action URL Form agar mengarah ke ID yang benar
        const form = document.getElementById('editVehicleForm');
        form.action = `/admin/vehicles/${vehicle.id}`;

        // 2. Isi kotak input dengan data lama
        document.getElementById('edit_license_plate').value = vehicle.license_plate;
        document.getElementById('edit_type').value = vehicle.type;
        document.getElementById('edit_capacity').value = vehicle.capacity;
        document.getElementById('edit_status').value = vehicle.status;

        // 3. Tampilkan modalnya
        openModal('editVehicleModal');
    }
</script>
