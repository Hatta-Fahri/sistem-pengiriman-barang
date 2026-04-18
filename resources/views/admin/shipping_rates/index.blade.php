@extends('layouts.app')

@section('header-title', 'Rute & Tarif')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="shippingRateModal()">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Master Tarif Pengiriman</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola daftar rute, jarak, jalur konsolidasi, dan harga dasar.</p>
        </div>
        <button @click="openCreate()" class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>Tambah Rute Baru</span>
        </button>
    </div>

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 font-semibold tracking-wider">Rute Pengiriman (Asal &rarr; Tujuan)</th>
                        <th class="px-6 py-4 font-semibold tracking-wider">Jalur / Zona</th> <th class="px-6 py-4 font-semibold tracking-wider">Jarak Estimasi</th>
                        <th class="px-6 py-4 font-semibold tracking-wider">Tarif Dasar (Per Kg)</th>
                        <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rates as $rate)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 font-bold text-gray-900">
                                    <span>{{ $rate->origin_city }}</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 text-gray-300"></i>
                                    <span>{{ $rate->destination_city }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <i data-lucide="map" class="w-3 h-3"></i>
                                    {{ $rate->jalur_pengiriman }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $rate->estimated_distance_km ? $rate->estimated_distance_km . ' KM' : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-blue-700">Rp {{ number_format($rate->cost_per_kg, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEdit({{ $rate }})" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Tarif">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>

                                <form action="{{ url('/admin/shipping-rates', $rate->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rute ini? Data yang terkait tidak akan bisa dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Rute">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="map" class="w-12 h-12 mb-3 text-gray-300"></i>
                                    <p class="text-base font-medium text-gray-500">Belum ada data tarif.</p>
                                    <p class="text-sm">Klik tombol "Tambah Rute Baru" untuk mulai mendata.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rates->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $rates->links() }}
            </div>
        @endif
    </div>

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
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kota Asal (Gudang)</label>
                            <div class="w-full px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-100 text-gray-600 font-bold flex items-center gap-2 cursor-not-allowed sm:text-sm">
                                <i data-lucide="map-pin" class="w-4 h-4 text-blue-600"></i>
                                Medan
                            </div>
                            <input type="hidden" name="origin_city" value="Medan">
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
                        Nama Kota Tujuan tidak dapat diubah. Silakan hapus dan buat rute baru jika salah ketik.
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
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('shippingRateModal', () => ({
            isOpen: false,
            mode: 'create',
            actionUrl: '{{ url('/admin/shipping-rates') }}',
            form: {
                destination_city: '',
                jalur_pengiriman: '',
                estimated_distance_km: '',
                cost_per_kg: ''
            },
            openCreate() {
                this.mode = 'create';
                this.actionUrl = '{{ url('/admin/shipping-rates') }}';
                this.form = { destination_city: '', jalur_pengiriman: '', estimated_distance_km: '', cost_per_kg: '' };
                this.isOpen = true;
            },
            openEdit(rate) {
                this.mode = 'edit';
                this.actionUrl = '{{ url('/admin/shipping-rates') }}/' + rate.id;
                this.form = {
                    destination_city: rate.destination_city,
                    jalur_pengiriman: rate.jalur_pengiriman,
                    estimated_distance_km: rate.estimated_distance_km,
                    cost_per_kg: rate.cost_per_kg
                };
                this.isOpen = true;
            }
        }))
    })
</script>
@endpush
@endsection
