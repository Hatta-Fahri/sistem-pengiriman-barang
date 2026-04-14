<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofOfDelivery extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Casting agar delivered_at menjadi format waktu Carbon,
    // mempermudah formatting tanggal di tampilan Blade nanti.
    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    // Relasi: Foto POD ini milik paket/resi yang mana?
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
