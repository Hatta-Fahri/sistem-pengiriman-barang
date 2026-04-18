@extends('layouts.app')

@section('header-title', 'Edit Resi Pengiriman')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="max-w-7xl mx-auto space-y-6 pb-12">

    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-900">Edit Resi: {{ $shipment->tracking_number }}</h2>
    </div>

    <form action="{{ route('shipments.update', $shipment->id) }}" method="POST" id="form-resi">
        @csrf
        @method('PUT')

        <input type="hidden" name="jalur_pengiriman" id="jalur_pengiriman" value="{{ $shipment->jalur_pengiriman }}">
        <input type="hidden" name="shipping_cost" id="shipping_cost" value="{{ $shipment->shipping_cost }}">
        <input type="hidden" name="origin_city" id="origin_city" value="{{ $shipment->origin_city }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-6">
                    <i data-lucide="user" class="w-5 h-5 text-blue-500"></i>
                    <h3 class="text-base font-bold text-gray-900">Data Pengirim</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pengirim <span class="text-red-500">*</span></label>
                        <input type="text" name="sender_name" value="{{ $shipment->sender_name }}" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="number" name="sender_phone" value="{{ $shipment->sender_phone }}" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="sender_address" rows="2" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors resize-none">{{ $shipment->sender_address }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-6">
                    <i data-lucide="user-check" class="w-5 h-5 text-green-500"></i>
                    <h3 class="text-base font-bold text-gray-900">Data Penerima</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
                        <input type="text" name="receiver_name" value="{{ $shipment->receiver_name }}" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="number" name="receiver_phone" value="{{ $shipment->receiver_phone }}" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="receiver_address" rows="2" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors resize-none">{{ $shipment->receiver_address }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm mt-6">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i data-lucide="calculator" class="w-5 h-5 text-orange-400"></i>
                    <h3 class="text-base font-bold text-gray-900">Detail Rute & Perhitungan Ongkir</h3>
                </div>
                <label class="inline-flex items-center cursor-pointer bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <input type="checkbox" name="is_min_charge" id="min_charge_toggle" value="1" checked class="sr-only peer">
                    <div class="relative w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-xs font-bold text-gray-700">Min. Charge (20 Kg)</span>
                </label>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Rute Pengiriman <span class="text-red-500">*</span></label>
                    <select name="destination_city" id="destination_city" required class="w-full border border-gray-300 rounded-lg text-sm">
                        <option value="{{ $shipment->destination_city }}" selected="selected">
                            {{ $shipment->origin_city }} ➔ {{ $shipment->destination_city }} (Rute Saat Ini)
                        </option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                    <div class="md:col-span-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Paket <span class="text-red-500">*</span></label>
                            <input type="text" name="item_description" value="{{ $shipment->item_description }}" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors">
                        </div>
                    </div>

                    <div class="md:col-span-3 flex gap-4">
                        <div class="w-1/3">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Koli <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_koli" value="{{ $shipment->jumlah_koli }}" min="1" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors text-center">
                        </div>
                        <div class="w-2/3">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Berat Aktual (Kg) <span class="text-red-500">*</span></label>
                            <input type="number" name="weight" id="weight" step="0.1" min="0.1" value="{{ $shipment->weight }}" required class="w-full border-0 border-b border-gray-200 focus:border-blue-500 focus:ring-0 px-0 py-2 text-sm text-gray-900 bg-transparent transition-colors font-bold">
                        </div>
                    </div>

                    <div class="md:col-span-4 bg-blue-50/80 border border-blue-100 rounded-xl p-4 flex flex-col justify-center">
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-1">Total Biaya Pengiriman</p>
                        <div id="display_cost" class="text-3xl font-black text-blue-800 truncate">
                            Rp {{ number_format($shipment->shipping_cost, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div id="min_charge_warning" class="hidden bg-gray-50 border border-gray-100 rounded-lg p-3 flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-blue-500 mt-0.5"></i>
                    <p class="text-xs text-gray-600 font-medium leading-relaxed">
                        Berat ditimbangan adalah <span id="text_actual_weight" class="font-bold text-gray-900">0</span> Kg.
                        Karena aturan Min Charge Aktif, sistem menghitung biaya berdasarkan batas minimum <span class="font-bold text-gray-900">20 Kg</span>.
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                <a href="{{ route('shipments.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" id="btn_submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors shadow-sm flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {

    $('#destination_city').select2({
        placeholder: "Pilih rute pengiriman...",
        allowClear: true,
        ajax: {
            url: "{{ route('ajax.destinations') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    origin_city: $('#origin_city').val()
                };
            },
            processResults: function (data) {
                return { results: data.results };
            }
        }
    });

    // Panggil fungsi kalkulasi otomatis saat halaman edit pertama kali dibuka
    // biar logic min_charge_warning-nya tersinkronisasi
    calculateTarif();

    $('#destination_city, #weight, #min_charge_toggle').on('change input', function() {
        calculateTarif();
    });

    function resetCalculator() {
        $('#display_cost').text('Rp 0');
        $('#shipping_cost').val('');
        $('#jalur_pengiriman').val('');
        $('#min_charge_warning').addClass('hidden');
        $('#btn_submit').prop('disabled', true);
    }

    function calculateTarif() {
        let origin = $('#origin_city').val();
        let destination = $('#destination_city').val();
        let actualWeight = parseFloat($('#weight').val()) || 0;
        let isMinCharge = $('#min_charge_toggle').is(':checked');

        if(origin && destination && actualWeight > 0) {

            $.ajax({
                url: "{{ route('ajax.rate') }}",
                data: { origin_city: origin, destination_city: destination },
                success: function(res) {
                    if(res.success) {
                        let chargeableWeight = (isMinCharge && actualWeight < 20) ? 20 : actualWeight;
                        let totalCost = res.cost_per_kg * chargeableWeight;

                        let formatRupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(totalCost);

                        $('#display_cost').text(formatRupiah);

                        if (isMinCharge && actualWeight < 20) {
                            $('#text_actual_weight').text(actualWeight);
                            $('#min_charge_warning').removeClass('hidden');
                        } else {
                            $('#min_charge_warning').addClass('hidden');
                        }

                        $('#shipping_cost').val(totalCost);
                        $('#jalur_pengiriman').val(res.jalur_pengiriman);
                        $('#btn_submit').prop('disabled', false);

                    } else {
                        $('#display_cost').text('Data Error');
                        resetCalculator();
                    }
                },
                error: function() {
                    $('#display_cost').text('Error');
                    resetCalculator();
                }
            });
        } else {
            resetCalculator();
        }
    }
});
</script>

<style>
.select2-container .select2-selection--single {
    height: 42px !important;
    border-radius: 0.5rem !important;
    border-color: #E5E7EB !important;
    display: flex;
    align-items: center;
    box-shadow: none !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
}
input[type="text"]:focus, input[type="number"]:focus, textarea:focus {
    box-shadow: none !important;
}
</style>
@endsection
