@extends('layouts.app')

@section('header-title', 'Manajemen Armada')

@section('content')
    <div class="w-full space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="text-gray-500 text-sm mt-1">Kelola data truk dan kendaraan operasional KEN Logistics.</p>
            </div>
            <button onclick="openModal('addVehicleModal')"
                class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>Tambah Armada</span>
            </button>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">Plat Nomor</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Jenis Kendaraan</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Kapasitas (Max)</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($vehicles as $vehicle)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 uppercase">{{ $vehicle->license_plate }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium flex items-center gap-2">
                                    <i data-lucide="truck" class="w-4 h-4 text-gray-400"></i>
                                    {{ $vehicle->type }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-bold text-gray-900">{{ number_format($vehicle->capacity, 0, ',', '.') }}</span>
                                    <span class="text-xs text-gray-500">Kg</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'Tersedia' => 'bg-green-100 text-green-700 border-green-200',
                                            'Terjadwal' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'Sedang Digunakan' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Maintenance' => 'bg-red-100 text-red-700 border-red-200',
                                        ];
                                        $statusVal = $vehicle->status->value ?? $vehicle->status;
                                        $colorClass =
                                            $statusColors[$statusVal] ??
                                            'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border {{ $colorClass }}">
                                        {{ $statusVal }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="editVehicle({{ json_encode($vehicle) }})"
                                            class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>

                                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="truck" class="w-12 h-12 mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium text-gray-500">Belum ada armada kendaraan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($vehicles->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('admin.vehicles.partials.form-modal')

@endsection
