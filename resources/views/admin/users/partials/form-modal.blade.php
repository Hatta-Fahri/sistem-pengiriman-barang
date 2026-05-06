{{-- ============================= MODAL TAMBAH PENGGUNA ============================= --}}
<div id="addUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Pengguna Baru</h3>
                <button type="button" onclick="closeModal('addUserModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4 max-h-[75vh] overflow-y-auto">

                    {{-- Pilih Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Role Akun <span class="text-red-500">*</span></label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="role" value="kurir" class="sr-only peer" checked onchange="toggleCourierFields(this.value)">
                                <div class="flex items-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all font-semibold text-gray-600 peer-checked:text-blue-700 text-sm">
                                    <i data-lucide="truck" class="w-4 h-4"></i> Kurir
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="role" value="admin" class="sr-only peer" onchange="toggleCourierFields(this.value)">
                                <div class="flex items-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all font-semibold text-gray-600 peer-checked:text-purple-700 text-sm">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i> Admin
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Field Umum --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="email@kenlogistics.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required minlength="6" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Minimal 6 karakter">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="phone" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="0812...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Berhenti">Berhenti</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" name="address" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Jalan, RT/RW, Kota">
                    </div>

                    {{-- Field Khusus Kurir --}}
                    <div id="courierFields" class="space-y-4 border-t border-dashed border-gray-200 pt-4">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="truck" class="w-3.5 h-3.5"></i> Data Khusus Kurir
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK (KTP) <span class="text-red-500">*</span></label>
                                <input type="number" name="nik" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="No. KTP 16 digit">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. SIM <span class="text-red-500">*</span></label>
                                <input type="number" name="sim_number" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="No. SIM...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis SIM <span class="text-red-500">*</span></label>
                                <select name="sim_type" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="SIM A">SIM A</option>
                                    <option value="SIM B1">SIM B1</option>
                                    <option value="SIM B1 Umum">SIM B1 Umum</option>
                                    <option value="SIM B2">SIM B2</option>
                                    <option value="SIM B2 Umum">SIM B2 Umum</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('addUserModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================= MODAL EDIT PENGGUNA ============================= --}}
<div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-sm" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Edit Pengguna — <span id="edit_display_name" class="text-blue-600"></span></h3>
                <button type="button" onclick="closeModal('editUserModal')" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-md transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf @method('PUT')
                <div class="px-6 py-5 space-y-4 max-h-[75vh] overflow-y-auto">

                    {{-- Field Umum --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_name" name="name" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                            <input type="email" id="edit_email" name="email" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Password</label>
                            <input type="password" name="password" minlength="6" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" id="edit_phone" name="phone" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="edit_status" name="status" required class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Berhenti">Berhenti</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" id="edit_address" name="address" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Field Khusus Kurir (muncul hanya jika role kurir) --}}
                    <div id="editCourierFields" class="hidden space-y-4 border-t border-dashed border-gray-200 pt-4">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="truck" class="w-3.5 h-3.5"></i> Data Khusus Kurir
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK (KTP)</label>
                                <input type="number" id="edit_nik" name="nik" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. SIM</label>
                                <input type="number" id="edit_sim_number" name="sim_number" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis SIM</label>
                                <select id="edit_sim_type" name="sim_type" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="SIM A">SIM A</option>
                                    <option value="SIM B1">SIM B1</option>
                                    <option value="SIM B1 Umum">SIM B1 Umum</option>
                                    <option value="SIM B2">SIM B2</option>
                                    <option value="SIM B2 Umum">SIM B2 Umum</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-700 rounded-xl hover:bg-blue-800 shadow-sm transition-colors">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id)  { document.getElementById(id).classList.remove('hidden'); lucide.createIcons(); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openAddModal() {
        document.querySelector('#addUserModal form').reset();
        toggleCourierFields('kurir');
        openModal('addUserModal');
    }

    function toggleCourierFields(role) {
        const fields = document.getElementById('courierFields');
        role === 'kurir' ? fields.classList.remove('hidden') : fields.classList.add('hidden');
    }

    function editUser(user) {
        document.getElementById('editUserForm').action = `/admin/users/${user.id}`;
        document.getElementById('edit_display_name').innerText = user.name;

        document.getElementById('edit_name').value    = user.name    || '';
        document.getElementById('edit_email').value   = user.email   || '';
        document.getElementById('edit_phone').value   = user.phone   || '';
        document.getElementById('edit_address').value = user.address || '';
        document.getElementById('edit_status').value  = user.status  || 'Aktif';

        const courierFields = document.getElementById('editCourierFields');
        if (user.role === 'kurir') {
            courierFields.classList.remove('hidden');
            document.getElementById('edit_nik').value        = user.nik        || '';
            document.getElementById('edit_sim_number').value = user.sim_number || '';
            document.getElementById('edit_sim_type').value   = user.sim_type   || 'SIM A';
        } else {
            courierFields.classList.add('hidden');
        }

        openModal('editUserModal');
    }
</script>
