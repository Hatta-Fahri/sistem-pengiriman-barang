<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    use HasFactory;

    // 1. Gunakan guarded agar semua kolom bisa diisi secara massal kecuali 'id' (Primary Key)
    protected $guarded = ['id'];

    // 2. Casting otomatis kolom 'status' ke tipe Enum ShipmentStatus
    //    Menjamin konsistensi nilai status di seluruh sistem
    protected $casts = [
        'status' => ShipmentStatus::class,
    ];

    // 3. Relasi ke Shipment: Entri log ini merupakan bagian dari riwayat pergerakan satu paket/resi tertentu
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // 4. Relasi ke User: Mencatat siapa (Admin atau Kurir) yang menekan tombol pembaruan status ini
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
