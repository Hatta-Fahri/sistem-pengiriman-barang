<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    // Opsional (Tapi sangat disarankan):
    // Memastikan current_status selalu di-casting menjadi Enum
    protected $casts = [
        'current_status' => ShipmentStatus::class,
    ];

    // Relasi: Siapa kurir yang membawa paket ini?
    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    // Relasi: Apa saja riwayat perjalanan (status) paket ini?
    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    // Relasi: Mana bukti foto pengirimannya? (1 Paket = 1 Bukti)
    public function proofOfDelivery()
    {
        return $this->hasOne(ProofOfDelivery::class);
    }
}
