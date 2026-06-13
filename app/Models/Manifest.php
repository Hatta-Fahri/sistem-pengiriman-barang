<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manifest extends Model
{
    use HasFactory, SoftDeletes;

    // 1. Gunakan guarded agar semua kolom bisa diisi secara massal kecuali 'id' (Primary Key)
    protected $guarded = ['id'];

    // 2. Casting otomatis kolom tanggal agar bisa dimanipulasi dengan fungsi Carbon (misal: format tampilan)
    protected $casts = [
        'departed_at' => 'datetime',
    ];

    // 3. Relasi ke User (Kurir): Sebuah manifest selalu ditugaskan kepada satu kurir
    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    // 4. Relasi ke Vehicle (Armada): Sebuah manifest menggunakan satu kendaraan pengiriman
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    // 5. Relasi ke Shipment (Resi): Satu manifest bisa memuat banyak resi/paket sekaligus
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'manifest_id');
    }


    // 6. Accessor: Hitung persentase pengisian muatan kendaraan secara otomatis
    //    Digunakan untuk menampilkan progress bar kapasitas di tampilan admin
    public function getLoadPercentageAttribute()
    {
        // Kembalikan 0 jika kendaraan belum dipilih atau kapasitasnya tidak valid
        if (!$this->vehicle || $this->vehicle->capacity <= 0) {
            return 0;
        }

        // Rumus: (Total Berat Resi / Kapasitas Maksimal Truk) x 100
        $percentage = ($this->total_weight / $this->vehicle->capacity) * 100;

        // Batasi nilai persentase maksimal di angka 100% untuk keamanan tampilan UI
        return min($percentage, 100);
    }
}
