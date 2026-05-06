<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofOfDelivery extends Model
{
    use HasFactory;

    // 1. Gunakan guarded agar semua kolom bisa diisi secara massal kecuali 'id'
    protected $guarded = ['id'];

    // 2. Casting kolom 'delivered_at' menjadi objek Carbon agar mudah diformat sebagai tanggal/waktu di tampilan Blade
    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    // 3. Relasi ke Shipment: Foto bukti pengiriman ini terhubung ke satu resi paket
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // 4. Accessor: Hasilkan URL foto yang dapat diakses secara publik
    //    Sistem ini mendukung dua sumber penyimpanan: Cloudinary (awan) dan Storage lokal
    public function getPhotoUrlAttribute()
    {
        // Kembalikan null jika foto belum ada
        if (!$this->photo_path) {
            return null;
        }

        // 5. Jika path diawali 'http/https', berarti foto tersimpan di Cloudinary — kembalikan URL langsung
        if (str_starts_with($this->photo_path, 'http')) {
            return $this->photo_path;
        }

        // 6. Jika path adalah path lokal, buat URL publik menggunakan helper asset()
        return asset('storage/' . $this->photo_path);
    }
}
