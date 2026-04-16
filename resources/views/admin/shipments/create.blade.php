@extends('layouts.app')

@section('header-title', 'Buat Resi Baru')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        <div class="flex items-center gap-4">
            <a href="{{ route('shipments.index') }}"
                class="p-2 bg-white rounded-xl border border-gray-200 text-gray-500 hover:text-blue-700 hover:bg-blue-50 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Buat Resi Baru</h2>
                <p class="text-sm text-gray-500">Input data pengiriman dan kalkulasi tarif pintar (Auto-Calculate).</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                <div class="font-bold mb-1 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i> Terjadi kesalahan input:
                </div>
                <ul class="list-disc list-inside text-sm ml-7">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('shipments.store') }}" method="POST" class="space-y-6" id="resiForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-50">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Data Pengirim</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengirim <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="sender_name" value="{{ old('sender_name') }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50/50"
                                placeholder="Contoh: Budi Santoso">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp / HP <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="sender_phone" value="{{ old('sender_phone') }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50/50"
                                placeholder="0812xxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span
                                    class="text-red-500">*</span></label>
                            <textarea name="sender_address" required rows="2"
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50/50">{{ old('sender_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-50">
                        <div class="p-2 bg-green-50 rounded-lg text-green-600">
                            <i data-lucide="user-check" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Data Penerima</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring-green-500 bg-gray-50/50"
                                placeholder="Contoh: Andi Wijaya">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp / HP <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="receiver_phone" value="{{ old('receiver_phone') }}" required
                                class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring-green-500 bg-gray-50/50"
                                placeholder="0813xxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span
                                    class="text-red-500">*</span></label>
                            <textarea name="receiver_address" required rows="2"
                                class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring-green-500 bg-gray-50/50">{{ old('receiver_address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-50">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                            <i data-lucide="calculator" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Detail Rute & Perhitungan Ongkir</h3>
                    </div>

                    <label
                        class="relative inline-flex items-center cursor-pointer bg-gray-50 px-3 py-2 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors">
                        <input type="checkbox" id="minChargeToggle" class="sr-only peer" checked>
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[10px] after:left-[14px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                        <span class="ml-3 text-sm font-bold text-gray-700">Min. Charge (20 Kg)</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">

                    <div class="lg:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Rute Pengiriman <span
                                class="text-red-500">*</span></label>
                        <select id="routeSelect" required
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 text-gray-900 font-medium">
                            <option value="">-- Cari dan Pilih Rute (Kota Asal ➔ Kota Tujuan) --</option>
                            @foreach ($shippingRates as $rate)
                                <option value="{{ $rate->id }}" data-origin="{{ $rate->origin_city }}"
                                    data-dest="{{ $rate->destination_city }}" data-price="{{ $rate->cost_per_kg }}"
                                    data-jalur="{{ $rate->jalur_pengiriman }}">
                                    {{ $rate->origin_city }} ➔ {{ $rate->destination_city }} --- (Tarif Dasar: Rp
                                    {{ number_format($rate->cost_per_kg, 0, ',', '.') }}/Kg)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="origin_city" id="originInput">
                    <input type="hidden" name="destination_city" id="destInput">
                    <input type="hidden" name="jalur_pengiriman" id="jalurInput">

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Paket <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="item_description" required
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50/50"
                            placeholder="Contoh: Dokumen, Pakaian, Sparepart">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Koli <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_koli" value="1" min="1" required
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50/50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berat Aktual Timbangan (Kg) <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.1" name="weight" id="weightInput" required
                            class="w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 font-bold text-lg text-orange-700 bg-orange-50"
                            placeholder="0">
                    </div>

                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                        <label class="block text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-1">Total Biaya
                            Pengiriman</label>
                        <div class="flex items-center gap-1">
                            <span class="text-blue-800 font-bold text-xl">Rp</span>
                            <input type="text" id="displayCost" readonly
                                class="w-full bg-transparent border-none p-0 text-blue-800 font-black text-2xl focus:ring-0 cursor-not-allowed"
                                placeholder="0">
                        </div>
                        <input type="hidden" name="shipping_cost" id="actualCost">
                    </div>
                </div>

                <div id="calculationNote"
                    class="mt-4 text-sm text-gray-500 italic hidden bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <i data-lucide="info" class="w-4 h-4 inline-block mr-1 text-blue-500"></i>
                    <span id="noteText">Sistem mendeteksi berat di bawah 20 Kg. Karena <b>Min Charge Aktif</b>, maka tarif
                        dihitung berdasarkan 20 Kg.</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('shipments.index') }}"
                    class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-blue-700 text-white font-bold hover:bg-blue-800 shadow-[0_8px_20px_-6px_rgba(29,78,216,0.5)] hover:shadow-blue-700/40 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Simpan & Cetak Resi
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const routeSelect = document.getElementById('routeSelect');
            const weightInput = document.getElementById('weightInput');
            const minChargeToggle = document.getElementById('minChargeToggle');

            const displayCost = document.getElementById('displayCost');
            const actualCost = document.getElementById('actualCost');

            // Input tersembunyi
            const originInput = document.getElementById('originInput');
            const destInput = document.getElementById('destInput');
            const jalurInput = document.getElementById('jalurInput');

            // Note UI
            const calculationNote = document.getElementById('calculationNote');
            const noteText = document.getElementById('noteText');

            function calculateCost() {
                const selectedOption = routeSelect.options[routeSelect.selectedIndex];

                // Reset jika belum pilih rute
                if (!selectedOption.value) {
                    displayCost.value = "0";
                    actualCost.value = "";
                    calculationNote.classList.add('hidden');
                    return;
                }

                // 1. Tarik Data dari Master Data Rute
                const pricePerKg = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const origin = selectedOption.getAttribute('data-origin') || '';
                const dest = selectedOption.getAttribute('data-dest') || '';
                const jalur = selectedOption.getAttribute('data-jalur') || '';

                // Simpan ke input hidden untuk dikirim ke Controller
                originInput.value = origin;
                destInput.value = dest;
                jalurInput.value = jalur;

                // 2. Ambil Input Kasir
                const actualWeight = parseFloat(weightInput.value) || 0;
                const isMinChargeActive = minChargeToggle.checked;

                // 3. LOGIKA INTI (Sesuai Diagram)
                let chargeableWeight = actualWeight; // Default: Berat Bayar = Berat Aktual
                calculationNote.classList.add('hidden'); // Sembunyikan pesan bantuan

                if (isMinChargeActive && actualWeight > 0) {
                    // max(BeratAktual, 20)
                    chargeableWeight = Math.max(actualWeight, 20);

                    // Jika sistem "menyelamatkan" harga (Berat aktual < 20)
                    if (actualWeight < 20) {
                        calculationNote.classList.remove('hidden');
                        noteText.innerHTML =
                            `Berat ditimbangan adalah <b>${actualWeight} Kg</b>. Karena aturan Min Charge Aktif, sistem menghitung biaya berdasarkan batas minimum <b>20 Kg</b>.`;
                    }
                }

                // 4. Kalkulasi Akhir
                const total = chargeableWeight * pricePerKg;

                // 5. Tampilkan ke Layar
                actualCost.value = total;
                displayCost.value = new Intl.NumberFormat('id-ID').format(total);
            }

            // Pemicu (Trigger) - Setiap kali nilai ini berubah, fungsi di atas akan berlari!
            routeSelect.addEventListener('change', calculateCost);
            weightInput.addEventListener('input', calculateCost);
            minChargeToggle.addEventListener('change', calculateCost);
        });
    </script>
@endsection
