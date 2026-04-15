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

    @include('admin.shipping_rates.partials.form-modal')

</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('shippingRateModal', () => ({
            isOpen: false,
            mode: 'create', // 'create' atau 'edit'
            actionUrl: '{{ url('/admin/shipping-rates') }}',
            form: {
                origin_city: '',
                destination_city: '',
                jalur_pengiriman: '', // State baru
                estimated_distance_km: '',
                cost_per_kg: ''
            },
            openCreate() {
                this.mode = 'create';
                this.actionUrl = '{{ url('/admin/shipping-rates') }}';
                this.form = { origin_city: '', destination_city: '', jalur_pengiriman: '', estimated_distance_km: '', cost_per_kg: '' };
                this.isOpen = true;
            },
            openEdit(rate) {
                this.mode = 'edit';
                this.actionUrl = '{{ url('/admin/shipping-rates') }}/' + rate.id;
                this.form = {
                    origin_city: rate.origin_city,
                    destination_city: rate.destination_city,
                    jalur_pengiriman: rate.jalur_pengiriman, // Bind data ke form saat edit
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
