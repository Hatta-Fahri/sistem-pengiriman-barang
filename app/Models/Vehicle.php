<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    // Mengizinkan semua kolom diisi secara massal kecuali 'id'
    protected $guarded = ['id'];

    // Relasi: Kendaraan ini dipakai oleh kurir (user) siapa saja?
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
