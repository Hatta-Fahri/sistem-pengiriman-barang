<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => ShipmentStatus::class,
    ];

    // Relasi: Status ini milik resi/paket yang mana?
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Relasi: Siapa Admin/Kurir yang menekan tombol update status ini?
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
