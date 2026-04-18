@extends('layouts.app')

@section('header-title', 'Manajemen Kurir')

@section('content')
    <div class="w-full space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="text-gray-500 text-sm mt-1">Kelola data personil kurir, lisensi berkendara, dan status kerja.</p>
            </div>
            <button onclick="openModal('addCourierModal')"
                class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                <span>Tambah Kurir</span>
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
                            <th class="px-6 py-4 font-semibold tracking-wider">Profil Kurir</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Kontak</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Lisensi (SIM)</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status & Operasional</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($couriers as $courier)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 uppercase">{{ $courier->courier_code ?? 'KRR-NEW' }}
                                    </div>
                                    <div class="text-gray-700 font-medium">{{ $courier->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">NIK: {{ $courier->nik ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $courier->phone ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $courier->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $courier->sim_type ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $courier->sim_number ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        // 1. Warna untuk status kepegawaian
                                        $statusColors = [
                                            'Aktif' => 'bg-green-100 text-green-700 border-green-200',
                                            'Cuti' => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'Berhenti' => 'bg-red-100 text-red-700 border-red-200',
                                        ];
                                        $colorClass =
                                            $statusColors[$courier->status] ??
                                            'bg-gray-100 text-gray-700 border-gray-200';

                                        // 2. Cek apakah kurir ini sedang berada di manifest yang belum selesai
                                        $isBertugas = \App\Models\Manifest::where('courier_id', $courier->id)
                                            ->whereIn('status', ['Persiapan', 'Sedang Jalan'])
                                            ->exists();
                                    @endphp

                                    <div class="flex flex-col items-start gap-1.5">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border {{ $colorClass }}">
                                            {{ $courier->status ?? 'Aktif' }}
                                        </span>

                                        @if ($courier->status === 'Aktif')
                                            @if ($isBertugas)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200"
                                                    title="Sedang mengantar atau persiapan muatan">
                                                    <i data-lucide="truck" class="w-3 h-3 mr-1"></i> Sedang Bertugas
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200"
                                                    title="Siap dijadwalkan">
                                                    <i data-lucide="check-circle-2" class="w-3 h-3 mr-1"></i> Tersedia
                                                    (Standby)
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="editCourier({{ json_encode($courier) }})"
                                            class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-colors"
                                            title="Edit Akun">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>

                                        <form action="{{ route('couriers.destroy', $courier->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menonaktifkan akun kurir ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus Akun">
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
                                        <i data-lucide="users" class="w-12 h-12 mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium text-gray-500">Belum ada data kurir.</p>
                                        <p class="text-sm">Klik tombol "Tambah Kurir" untuk memasukkan data personil.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($couriers->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $couriers->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('admin.couriers.partials.form-modal')

@endsection
