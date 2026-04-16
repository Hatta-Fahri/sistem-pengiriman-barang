<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manifest extends Model
{
    use HasFactory, SoftDeletes;

    // Menggunakan guarded agar kita tidak perlu repot menulis fillable satu per satu
    protected $guarded = ['id'];

    // Casting tipe data tanggal
    protected $casts = [
        'departed_at' => 'datetime',
        'arrived_at'  => 'datetime',
    ];

    // ==========================================
    // RELASI DATABASE
    // ==========================================

    // Relasi balik ke Kurir (User)
    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    // Relasi balik ke Armada (Vehicle)
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    // Relasi ke Shipment (Resi/Paket)
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'manifest_id');
    }


    public function getLoadPercentageAttribute()
    {

        if (!$this->vehicle || $this->vehicle->capacity <= 0) {
            return 0;
        }

        // Rumus: (Total Berat Resi / Kapasitas Maksimal Truk) x 100
        $percentage = ($this->total_weight / $this->vehicle->capacity) * 100;

        // Cegah persentase melebihi 100% untuk kebutuhan tampilan UI
        return min($percentage, 100);
    }
}
