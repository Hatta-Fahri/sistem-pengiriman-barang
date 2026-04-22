@extends('layouts.app')

@section('header-title', 'Pusat Laporan')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
            <i data-lucide="filter" class="w-5 h-5 text-gray-500"></i>
            <h3 class="text-lg font-bold text-gray-900">Filter & Cetak Laporan</h3>
        </div>

        <div class="p-6 sm:p-8">
            <form action="{{ route('reports.generate') }}" method="GET" class="space-y-8" target="_blank">

                <div>
                    <h4 class="text-sm font-bold text-gray-900 mb-3 border-b border-gray-100 pb-2">1. Pilih Rentang Waktu</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Dari Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" required value="{{ date('Y-m-01') }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sampai Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-gray-900 mb-3 border-b border-gray-100 pb-2">2. Pilih Jenis Laporan</h4>
                    <select name="report_type" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 text-sm bg-gray-50 cursor-pointer">
                        <option value="">-- Silakan Pilih Laporan --</option>
                        <option value="shipment">Laporan Rekapitulasi Data Pengiriman</option>
                        <option value="courier">Laporan Detail Pengiriman</option>
                    </select>
                </div>

                <div class="pt-2">
                    <h4 class="text-sm font-bold text-gray-900 mb-3 border-b border-gray-100 pb-2">3. Cetak & Unduh</h4>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" name="format" value="pdf" class="flex-1 flex items-center justify-center gap-2 py-3.5 px-4 bg-red-50 text-red-700 border border-red-200 rounded-xl font-bold text-sm hover:bg-red-100 transition-colors shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Download format PDF
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
