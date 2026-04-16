<div id="addCourierModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900" id="modal-title">Tambah Data Kurir</h3>
                <button type="button" onclick="closeModal('addCourierModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('couriers.store') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK (KTP) <span class="text-red-500">*</span></label>
                            <input type="number" name="nik" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 1209381...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="budi@kenlogistics.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon/WA <span class="text-red-500">*</span></label>
                            <input type="number" name="phone" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="0812...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. SIM <span class="text-red-500">*</span></label>
                            <input type="number" name="sim_number" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis SIM <span class="text-red-500">*</span></label>
                            <select name="sim_type" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="SIM A">SIM A</option>
                                <option value="SIM B1">SIM B1</option>
                                <option value="SIM B1 Umum">SIM B1 Umum</option>
                                <option value="SIM B2">SIM B2</option>
                                <option value="SIM B2 Umum">SIM B2 Umum</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="address" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Jalan, RT/RW, Kota">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Berhenti">Berhenti</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required minlength="6" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Minimal 6 karakter">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('addCourierModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editCourierModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Edit Data Kurir <span id="display_courier_code" class="text-blue-600 ml-1"></span></h3>
                <button type="button" onclick="closeModal('editCourierModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="editCourierForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_name" name="name" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK (KTP) <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_nik" name="nik" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" id="edit_email" name="email" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon/WA <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_phone" name="phone" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. SIM <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_sim_number" name="sim_number" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis SIM <span class="text-red-500">*</span></label>
                            <select id="edit_sim_type" name="sim_type" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="SIM A">SIM A</option>
                                <option value="SIM B1">SIM B1</option>
                                <option value="SIM B1 Umum">SIM B1 Umum</option>
                                <option value="SIM B2">SIM B2</option>
                                <option value="SIM B2 Umum">SIM B2 Umum</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_address" name="address" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="edit_status" name="status" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Berhenti">Berhenti</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Password</label>
                            <input type="password" name="password" minlength="6" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Kosongkan jika tidak diubah">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('editCourierModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) { document.getElementById(modalId).classList.remove('hidden'); }
    function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }

    function editCourier(courier) {
        document.getElementById('editCourierForm').action = `/admin/couriers/${courier.id}`;

        // Header Text
        document.getElementById('display_courier_code').innerText = courier.courier_code ? `(${courier.courier_code})` : '';

        // Isi Data
        document.getElementById('edit_name').value = courier.name;
        document.getElementById('edit_email').value = courier.email;
        document.getElementById('edit_nik').value = courier.nik;
        document.getElementById('edit_phone').value = courier.phone;
        document.getElementById('edit_sim_number').value = courier.sim_number;
        document.getElementById('edit_sim_type').value = courier.sim_type || 'SIM A';
        document.getElementById('edit_address').value = courier.address;
        document.getElementById('edit_status').value = courier.status || 'Aktif';

        openModal('editCourierModal');
    }
</script>
