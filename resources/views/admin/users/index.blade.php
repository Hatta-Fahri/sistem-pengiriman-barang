@extends('layouts.app')

@section('header-title', 'Manajemen Pengguna')

@section('content')
    <div class="w-full space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Pengguna</h2>
                <p class="text-gray-500 text-sm mt-1">Buat dan kelola akun Admin maupun Kurir dalam satu tempat.</p>
            </div>
            <button onclick="openAddModal()"
                class="flex items-center gap-2 bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-blue-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                <span>Tambah Pengguna</span>
            </button>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Tab Filter: hanya Admin dan Kurir --}}
        <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-2xl p-1.5 shadow-sm w-full sm:w-auto">
            <a href="{{ route('users.index', ['role' => 'admin']) }}"
                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all
                {{ $roleFilter === 'admin' ? 'bg-purple-600 text-white shadow' : 'text-gray-500 hover:bg-gray-100' }}">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                Admin
                <span class="text-xs font-bold px-1.5 py-0.5 rounded-md {{ $roleFilter === 'admin' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">
                    {{ $totalAdmin }}
                </span>
            </a>
            <a href="{{ route('users.index', ['role' => 'kurir']) }}"
                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all
                {{ $roleFilter === 'kurir' ? 'bg-blue-600 text-white shadow' : 'text-gray-500 hover:bg-gray-100' }}">
                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                Kurir
                <span class="text-xs font-bold px-1.5 py-0.5 rounded-md {{ $roleFilter === 'kurir' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">
                    {{ $totalKurir }}
                </span>
            </a>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">

                    {{-- Header kolom berubah tergantung tab aktif --}}
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50">
                        @if($roleFilter === 'kurir')
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Email</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Password</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">NIK</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">No. Telepon</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Jenis SIM</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                            </tr>
                        @else
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Role</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Email</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                            </tr>
                        @endif
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)

                            {{-- ======= BARIS TAB KURIR ======= --}}
                            @if($roleFilter === 'kurir')
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold border border-blue-200 shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                                @if($user->courier_code)
                                                    <div class="text-xs text-gray-400">{{ $user->courier_code }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-gray-400 text-base tracking-widest">••••••••</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-700">{{ $user->nik ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $user->phone ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->sim_type)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                {{ $user->sim_type }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = ['Aktif' => 'bg-green-100 text-green-700 border-green-200', 'Cuti' => 'bg-orange-100 text-orange-700 border-orange-200', 'Berhenti' => 'bg-red-100 text-red-700 border-red-200'];
                                            $colorClass = $statusColors[$user->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $colorClass }}">
                                            {{ $user->status ?? 'Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @include('admin.users.partials.action-buttons', ['user' => $user])
                                    </td>
                                </tr>

                            {{-- ======= BARIS TAB ADMIN ======= --}}
                            @else
                                <tr class="hover:bg-purple-50/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-bold border border-purple-200 shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                            <i data-lucide="shield-check" class="w-3 h-3"></i> Admin
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @include('admin.users.partials.action-buttons', ['user' => $user])
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="{{ $roleFilter === 'kurir' ? 8 : 4 }}" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="{{ $roleFilter === 'kurir' ? 'truck' : 'shield-check' }}" class="w-12 h-12 mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium text-gray-500">
                                            Belum ada akun {{ $roleFilter === 'kurir' ? 'Kurir' : 'Admin' }}.
                                        </p>
                                        <p class="text-sm">Klik "Tambah Pengguna" untuk membuat akun baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('admin.users.partials.form-modal')

@endsection
