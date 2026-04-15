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

    protected $casts = [
        'current_status' => ShipmentStatus::class,
    ];

    // =========================================================
    // RELASI
    // =========================================================

    public function manifest()
    {
        return $this->belongsTo(Manifest::class);
    }

    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    public function proofOfDelivery()
    {
        return $this->hasOne(ProofOfDelivery::class);
    }

    // =========================================================
    // SCOPE PENCARIAN
    // =========================================================

    public function scopePending($query)
    {
        // Menggunakan Enum DIPROSES untuk pencarian data yang siap dijadwalkan
        return $query->where('current_status', ShipmentStatus::DIPROSES)
                     ->whereNull('manifest_id');
    }
}
