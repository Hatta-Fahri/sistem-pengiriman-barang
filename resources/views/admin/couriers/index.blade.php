@extends('layouts.app')

@section('header-title', 'Daftar Kurir')

@section('content')
    <div class="w-full space-y-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Kurir</h2>
                <p class="text-gray-500 text-sm mt-1">Informasi data personil kurir beserta lisensi dan status kerja.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Email</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Password</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">NIK</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">No. Telepon</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Jenis SIM</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($couriers as $courier)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold border border-blue-200">
                                            {{ strtoupper(substr($courier->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $courier->name }}</div>
                                            @if($courier->courier_code)
                                                <div class="text-xs text-gray-400">{{ $courier->courier_code }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $courier->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-gray-400 text-base tracking-widest">••••••••</span>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-mono text-xs">{{ $courier->nik ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $courier->phone ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($courier->sim_type)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            {{ $courier->sim_type }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'Aktif'    => 'bg-green-100 text-green-700 border-green-200',
                                            'Cuti'     => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'Berhenti' => 'bg-red-100 text-red-700 border-red-200',
                                        ];
                                        $colorClass = $statusColors[$courier->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $colorClass }}">
                                        {{ $courier->status ?? 'Aktif' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="users" class="w-12 h-12 mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium text-gray-500">Belum ada data kurir.</p>
                                        <p class="text-sm">Tambahkan kurir melalui menu <strong>Manajemen Pengguna</strong>.</p>
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
@endsection
