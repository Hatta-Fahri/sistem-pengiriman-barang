<div x-show="isOpen"
     x-transition.opacity
     class="fixed inset-0 z-[60] bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4 sm:p-0"
     x-cloak>

    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         @click.outside="isOpen = false"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900" x-text="mode === 'create' ? 'Tambah Rute Baru' : 'Edit Tarif Rute'"></h3>
            <button @click="isOpen = false" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form :action="actionUrl" method="POST">
            @csrf
            <template x-if="mode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="p-6 space-y-5">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kota Asal <span class="text-red-500">*</span></label>
                        <input type="text" name="origin_city" x-model="form.origin_city" :readonly="mode === 'edit'" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-colors sm:text-sm"
                               :class="mode === 'edit' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-900'"
                               placeholder="Contoh: Medan">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kota Tujuan <span class="text-red-500">*</span></label>
                        <input type="text" name="destination_city" x-model="form.destination_city" :readonly="mode === 'edit'" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-colors sm:text-sm"
                               :class="mode === 'edit' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-900'"
                               placeholder="Contoh: Pematangsiantar">
                    </div>
                </div>

                <p x-show="mode === 'edit'" class="text-[11px] text-gray-500 font-medium flex items-center gap-1.5 bg-blue-50 p-2 rounded-lg">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-blue-500"></i>
                    Nama Kota Asal & Tujuan tidak dapat diubah. Silakan hapus dan buat rute baru jika salah ketik.
                </p>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jalur Konsolidasi <span class="text-red-500">*</span></label>
                    <select name="jalur_pengiriman" x-model="form.jalur_pengiriman" required
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-colors sm:text-sm bg-white text-gray-900">
                        <option value="">-- Pilih Jalur --</option>
                        <option value="Lintas Timur">Lintas Timur (T.Tinggi, Siantar, Kisaran, dst)</option>
                        <option value="Lintas Barat">Lintas Barat (Karo, Dairi, Pakpak, dst)</option>
                        <option value="Lintas Utara">Lintas Utara (Binjai, Langkat, Aceh, dst)</option>
                        <option value="Lintas Selatan">Lintas Selatan (Toba, Taput, Sidempuan, dst)</option>
                        <option value="Dalam Kota">Dalam Kota (Medan Sekitarnya)</option>
                    </select>
                    <p class="text-[11px] text-gray-500 mt-1">Digunakan untuk mengelompokkan paket ke dalam truk yang searah.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tarif per Kg (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 font-medium">Rp</span>
                            <input type="number" name="cost_per_kg" x-model="form.cost_per_kg" required min="0"
                                   class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-colors sm:text-sm text-gray-900"
                                   placeholder="5000">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jarak Estimasi (Opsional)</label>
                        <div class="relative">
                            <input type="number" name="estimated_distance_km" x-model="form.estimated_distance_km" min="0" step="0.1"
                                   class="w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-colors sm:text-sm text-gray-900"
                                   placeholder="120">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 font-medium text-sm">KM</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 rounded-b-2xl">
                <button @click="isOpen = false" type="button" class="px-4 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-700 rounded-xl hover:bg-blue-800 focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-sm transition-colors flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span x-text="mode === 'create' ? 'Simpan Rute' : 'Perbarui Tarif'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
