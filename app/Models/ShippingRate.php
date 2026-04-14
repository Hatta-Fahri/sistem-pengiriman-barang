<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Scope untuk mempermudah pencarian tarif berdasarkan rute Asal -> Tujuan
     * Contoh penggunaan di Controller: ShippingRate::route('Medan', 'Siantar')->first();
     */
    public function scopeRoute($query, $origin, $destination)
    {
        return $query->where('origin_city', $origin)
                     ->where('destination_city', $destination);
    }
}
