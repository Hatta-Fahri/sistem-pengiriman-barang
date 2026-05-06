<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    // 1. Gunakan guarded agar semua kolom bisa diisi secara massal kecuali 'id' (Primary Key)
    protected $guarded = ['id'];

    // 2. Scope 'route': Filter cepat untuk mencari tarif berdasarkan kombinasi kota asal dan kota tujuan
    //    Contoh penggunaan: ShippingRate::route('Medan', 'Jakarta')->first();
    public function scopeRoute($query, $origin, $destination)
    {
        return $query->where('origin_city', $origin)
                     ->where('destination_city', $destination);
    }

    // 3. Scope 'jalur': Filter cepat untuk mencari tarif berdasarkan jalur pengiriman (Konsolidasi Muatan)
    //    Contoh penggunaan: ShippingRate::jalur('Lintas Timur')->get();
    public function scopeJalur($query, $jalur)
    {
        return $query->where('jalur_pengiriman', $jalur);
    }
}
