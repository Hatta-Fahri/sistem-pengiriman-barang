<div class="flex justify-end gap-2">
    <button onclick="editUser({{ json_encode($user) }})"
        class="p-2 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
        <i data-lucide="edit-3" class="w-4 h-4"></i>
    </button>
    @if($user->id !== auth()->id())
        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($user->name) }}?');">
            @csrf @method('DELETE')
            <button type="submit"
                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </form>
    @else
        <span class="p-2 text-gray-200 cursor-not-allowed" title="Tidak bisa hapus akun sendiri">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </span>
    @endif
</div>
