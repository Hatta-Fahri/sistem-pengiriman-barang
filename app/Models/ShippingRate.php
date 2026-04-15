<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeRoute($query, $origin, $destination)
    {
        return $query->where('origin_city', $origin)
                     ->where('destination_city', $destination);
    }

    /**
     * Scope untuk memfilter rute berdasarkan Jalur Pengiriman (Konsolidasi Muatan)
     * Contoh penggunaan nanti saat Penjadwalan: ShippingRate::jalur('Lintas Timur')->get();
     */
    public function scopeJalur($query, $jalur)
    {
        return $query->where('jalur_pengiriman', $jalur);
    }
}
