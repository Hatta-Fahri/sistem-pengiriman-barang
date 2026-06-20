<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    // 1. Manfaatkan trait SoftDeletes agar kendaraan yang dihapus tetap tersimpan di database
    //    Ini menjaga integritas data manifest dan riwayat pengiriman yang pernah menggunakan armada ini
    use HasFactory, SoftDeletes;

    // 2. Gunakan guarded agar semua kolom bisa diisi secara massal kecuali 'id' (Primary Key)
    protected $guarded = ['id'];

    // 3. Casting otomatis kolom 'status' ke tipe Enum VehicleStatus
    //    Ini memastikan nilai status selalu valid dan konsisten di seluruh sistem
    protected $casts = [
        'status' => VehicleStatus::class,
    ];

    // 3. Relasi ke Manifest: Satu armada kendaraan bisa digunakan dalam banyak jadwal pengantaran dari waktu ke waktu
    public function manifests()
    {
        return $this->hasMany(Manifest::class);
    }
}
